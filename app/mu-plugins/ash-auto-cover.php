<?php

/*
Plugin Name: Ash Auto Cover
Description: Automatically generates deterministic, excerpt-seeded geometric WebP featured images for posts without one
Author: Ash Davies
Version: 0.1.0
*/

namespace Ash\AutoCover;

use WP_Post;

defined('ABSPATH') || exit;

const COVER_VERSION = 'v1';
const RENDER_WIDTH  = 2400;
const RENDER_HEIGHT = 1260;
const OUTPUT_WIDTH  = 1200;
const OUTPUT_HEIGHT = 630;

/**
 * Generate or refresh an auto-cover featured image after a post is saved.
 */
add_action('wp_after_insert_post', function (int $post_id, WP_Post $post): void {
    static $running = [];

    if (isset($running[$post_id])) {
        return;
    }

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if ($post->post_type !== 'post' || $post->post_status !== 'publish') {
        return;
    }

    if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
        return;
    }

    $running[$post_id] = true;
    try {
        ensure_post_cover($post);
    } finally {
        unset($running[$post_id]);
    }
}, 20, 2);

/**
 * Ensure the given post has a featured image, generating one from its excerpt if unset
 * (or regenerating if the existing thumbnail was auto-generated and the excerpt changed).
 */
function ensure_post_cover(WP_Post $post, bool $force = false): ?int
{
    $post_id = (int) $post->ID;
    $seed_text = extract_seed_text($post);
    $seed_hash = md5(COVER_VERSION . ':' . $seed_text);

    $existing_thumb_id = (int) get_post_thumbnail_id($post_id);
    if ($existing_thumb_id > 0) {
        $is_auto = (bool) get_post_meta($existing_thumb_id, '_ash_auto_cover', true);
        if (!$is_auto) {
            return null;
        }

        $existing_hash = (string) get_post_meta($existing_thumb_id, '_ash_auto_cover_hash', true);
        if (!$force && $existing_hash === $seed_hash) {
            return $existing_thumb_id;
        }
    }

    $rendered = render_geometric_cover($seed_text);
    if ($rendered === null) {
        return null;
    }

    $ext = $rendered['ext'];
    $mime = $rendered['mime'];
    $filename = sanitize_file_name(sprintf('auto-cover-%d-%s.%s', $post_id, substr($seed_hash, 0, 8), $ext));

    $upload = wp_upload_bits($filename, null, $rendered['bytes']);
    if (!empty($upload['error']) || empty($upload['file'])) {
        return null;
    }

    $attachment_id = wp_insert_attachment([
        'guid'           => $upload['url'],
        'post_mime_type' => $mime,
        'post_title'     => sprintf(
            /* translators: %s: post title */
            __('Cover illustration for %s', 'ashdavies'),
            $post->post_title ?: (string) $post_id
        ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ], $upload['file'], $post_id);

    if (is_wp_error($attachment_id) || !$attachment_id) {
        @unlink($upload['file']);
        return null;
    }

    if (!function_exists('wp_generate_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (is_array($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    update_post_meta($attachment_id, '_ash_auto_cover', '1');
    update_post_meta($attachment_id, '_ash_auto_cover_hash', $seed_hash);

    set_post_thumbnail($post_id, $attachment_id);

    // Remove superseded auto-generated attachment so Media Library stays clean
    if ($existing_thumb_id > 0 && $existing_thumb_id !== $attachment_id) {
        wp_delete_attachment($existing_thumb_id, true);
    }

    return (int) $attachment_id;
}

/**
 * Extract the seed string from the post excerpt, falling back to content/title when empty.
 */
function extract_seed_text(WP_Post $post): string
{
    $excerpt = trim(wp_strip_all_tags((string) $post->post_excerpt));
    if ($excerpt !== '') {
        return $excerpt;
    }

    $content_snippet = trim(wp_trim_words(wp_strip_all_tags((string) $post->post_content), 55, ''));
    $title = trim((string) $post->post_title);

    $combined = trim($title . ' ' . $content_snippet);
    return $combined !== '' ? $combined : ('post-' . (int) $post->ID);
}

/**
 * Classify excerpt text into a visual motif family.
 */
function classify_motif(string $text): string
{
    $haystack = strtolower($text);

    $groups = [
        'infra' => [
            'docker', 'container', 'containers', 'terraform', 'azure', 'aws', 'cloud',
            'linux', 'ubuntu', 'kubernetes', 'ci/cd', 'pipeline', 'server', 'deploy',
            'infrastructure', 'module', 'iac', 'devops',
        ],
        'accessibility' => [
            'wcag', 'accessibility', 'a11y', 'contrast', 'focus', 'css', 'grid',
            'design', 'typography', 'ui', 'theme', 'html', 'aria', 'keyboard', 'screen reader',
        ],
        'security' => [
            'security', 'hardening', 'owasp', 'xss', 'csrf', 'auth', '2fa', 'tls',
            'ssl', 'smtp', 'permission', 'permissions', 'non-root', 'privacy',
            'immutable', 'wordpress', 'php', 'vulnerability',
        ],
    ];

    $scores = ['infra' => 0, 'accessibility' => 0, 'security' => 0];
    foreach ($groups as $motif => $keywords) {
        foreach ($keywords as $kw) {
            if (strpos($haystack, $kw) !== false) {
                $scores[$motif]++;
            }
        }
    }

    arsort($scores);
    $top_motif = (string) array_key_first($scores);
    return $scores[$top_motif] > 0 ? $top_motif : 'general';
}

/**
 * Create a pure local deterministic PRNG from a seed string.
 *
 * @return callable(int, int): int
 */
function make_rng(string $seed): callable
{
    $counter = 0;

    return static function (int $min, int $max) use ($seed, &$counter): int {
        if ($min >= $max) {
            return $min;
        }
        $digest = hash('sha256', $seed . ':' . $counter, true);
        $counter++;
        $unpacked = unpack('Nval', $digest);
        $num = (int) ($unpacked['val'] ?? 0);
        return $min + ($num % ($max - $min + 1));
    };
}

/**
 * Render a 2x supersampled geometric cover and encode to WebP (with PNG fallback).
 *
 * @return array{bytes: string, ext: string, mime: string}|null
 */
function render_geometric_cover(string $seed_text): ?array
{
    $w = RENDER_WIDTH;
    $h = RENDER_HEIGHT;

    $canvas = imagecreatetruecolor($w, $h);
    if (!$canvas) {
        return null;
    }

    imagealphablending($canvas, true);
    imagesavealpha($canvas, true);

    $rng = make_rng($seed_text);
    $motif = classify_motif($seed_text);

    // Site palette
    $c_blue    = imagecolorallocate($canvas, 0x17, 0x50, 0x73);
    $c_teal    = imagecolorallocate($canvas, 0x2e, 0x6e, 0x62);
    $c_peach   = imagecolorallocate($canvas, 0xf2, 0xc0, 0x63);
    $c_orange  = imagecolorallocate($canvas, 0xf2, 0xc1, 0x2e);
    $c_papyrus = imagecolorallocate($canvas, 0xf1, 0xed, 0xd9);
    $c_ink     = imagecolorallocate($canvas, 0x1d, 0x23, 0x27);
    $c_sky     = imagecolorallocate($canvas, 0x7c, 0xc4, 0xf5);
    $c_white   = imagecolorallocate($canvas, 0xff, 0xff, 0xff);
    $c_grey    = imagecolorallocate($canvas, 0xf6, 0xf8, 0xfa);

    $palette = [
        'blue'    => $c_blue,
        'teal'    => $c_teal,
        'peach'   => $c_peach,
        'orange'  => $c_orange,
        'papyrus' => $c_papyrus,
        'ink'     => $c_ink,
        'sky'     => $c_sky,
        'white'   => $c_white,
        'grey'    => $c_grey,
    ];

    switch ($motif) {
        case 'infra':
            draw_infra_motif($canvas, $w, $h, $rng, $palette);
            break;
        case 'accessibility':
            draw_accessibility_motif($canvas, $w, $h, $rng, $palette);
            break;
        case 'security':
            draw_security_motif($canvas, $w, $h, $rng, $palette);
            break;
        default:
            draw_general_motif($canvas, $w, $h, $rng, $palette);
            break;
    }

    // Downsample 2x -> 1x for crisp anti-aliased edges
    $out = imagecreatetruecolor(OUTPUT_WIDTH, OUTPUT_HEIGHT);
    if (!$out) {
        imagedestroy($canvas);
        return null;
    }
    imagecopyresampled($out, $canvas, 0, 0, 0, 0, OUTPUT_WIDTH, OUTPUT_HEIGHT, $w, $h);
    imagedestroy($canvas);

    ob_start();
    if (function_exists('imagewebp')) {
        $ok = imagewebp($out, null, 88);
        $ext = 'webp';
        $mime = 'image/webp';
    } else {
        $ok = imagepng($out, null, 6);
        $ext = 'png';
        $mime = 'image/png';
    }
    $bytes = ob_get_clean();
    imagedestroy($out);

    if (!$ok || !is_string($bytes) || $bytes === '') {
        return null;
    }

    return [
        'bytes' => $bytes,
        'ext'   => $ext,
        'mime'  => $mime,
    ];
}

/**
 * Draw a filled rounded rectangle.
 */
function fill_rounded_rect($img, int $x, int $y, int $w, int $h, int $radius, int $color): void
{
    $radius = min($radius, (int) floor($w / 2), (int) floor($h / 2));
    imagefilledrectangle($img, $x + $radius, $y, $x + $w - $radius, $y + $h, $color);
    imagefilledrectangle($img, $x, $y + $radius, $x + $w, $y + $h - $radius, $color);
    $d = $radius * 2;
    imagefilledellipse($img, $x + $radius, $y + $radius, $d, $d, $color);
    imagefilledellipse($img, $x + $w - $radius, $y + $radius, $d, $d, $color);
    imagefilledellipse($img, $x + $radius, $y + $h - $radius, $d, $d, $color);
    imagefilledellipse($img, $x + $w - $radius, $y + $h - $radius, $d, $d, $color);
}

/**
 * Draw a stroked ring (annulus) by drawing an outer and inner thickness.
 */
function stroke_ring($img, int $cx, int $cy, int $diameter, int $thickness, int $color): void
{
    $steps = max(1, $thickness);
    for ($i = 0; $i < $steps; $i++) {
        $d = $diameter - ($i * 2);
        if ($d <= 0) {
            break;
        }
        imageellipse($img, $cx, $cy, $d, $d, $color);
    }
}

/**
 * Draw a subtle background dot matrix.
 */
function draw_dot_grid($img, int $x0, int $y0, int $cols, int $rows, int $gap, int $dot_d, int $color): void
{
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            imagefilledellipse($img, $x0 + $c * $gap, $y0 + $r * $gap, $dot_d, $dot_d, $color);
        }
    }
}

/**
 * Infra/Cloud motif: connected modular blocks and nodes.
 *
 * @param callable(int, int): int $rng
 * @param array<string, int> $p
 */
function draw_infra_motif($img, int $w, int $h, callable $rng, array $p): void
{
    $bg_choices = [$p['ink'], $p['blue']];
    $bg = $bg_choices[$rng(0, 1)];
    imagefilledrectangle($img, 0, 0, $w, $h, $bg);

    $soft_ring = imagecolorallocatealpha($img, 0x7c, 0xc4, 0xf5, 102);
    $soft_dot  = imagecolorallocatealpha($img, 0xf1, 0xed, 0xd9, 98);
    $line_clr  = imagecolorallocatealpha($img, 0xf1, 0xed, 0xd9, 65);

    $ring_x = $rng(0, 1) === 0 ? $w - 260 : 260;
    $ring_y = $rng(140, $h - 140);
    stroke_ring($img, $ring_x, $ring_y, $rng(760, 1040), 56, $soft_ring);

    draw_dot_grid($img, 140, 140, 12, 6, 180, 14, $soft_dot);

    // 4 connected nodes
    $nodes = [
        ['x' => $rng(260, 420),   'y' => $rng(420, 620), 'w' => 380, 'h' => 240, 'clr' => $p['blue'] === $bg ? $p['teal'] : $p['blue']],
        ['x' => $rng(920, 1080),  'y' => $rng(170, 300), 'w' => 420, 'h' => 250, 'clr' => $p['peach']],
        ['x' => $rng(940, 1120),  'y' => $rng(700, 840), 'w' => 400, 'h' => 240, 'clr' => $p['papyrus']],
        ['x' => $rng(1580, 1740), 'y' => $rng(420, 620), 'w' => 380, 'h' => 250, 'clr' => $p['teal']],
    ];

    imagesetthickness($img, 10);
    $c0 = [$nodes[0]['x'] + (int) ($nodes[0]['w'] / 2), $nodes[0]['y'] + (int) ($nodes[0]['h'] / 2)];
    $c1 = [$nodes[1]['x'] + (int) ($nodes[1]['w'] / 2), $nodes[1]['y'] + (int) ($nodes[1]['h'] / 2)];
    $c2 = [$nodes[2]['x'] + (int) ($nodes[2]['w'] / 2), $nodes[2]['y'] + (int) ($nodes[2]['h'] / 2)];
    $c3 = [$nodes[3]['x'] + (int) ($nodes[3]['w'] / 2), $nodes[3]['y'] + (int) ($nodes[3]['h'] / 2)];

    imageline($img, $c0[0], $c0[1], $c1[0], $c1[1], $line_clr);
    imageline($img, $c0[0], $c0[1], $c2[0], $c2[1], $line_clr);
    imageline($img, $c1[0], $c1[1], $c3[0], $c3[1], $line_clr);
    imageline($img, $c2[0], $c2[1], $c3[0], $c3[1], $line_clr);

    foreach ($nodes as $n) {
        fill_rounded_rect($img, $n['x'], $n['y'], $n['w'], $n['h'], 36, $n['clr']);
        $inner = ($n['clr'] === $p['papyrus'] || $n['clr'] === $p['peach'])
            ? imagecolorallocatealpha($img, 0x1d, 0x23, 0x27, 92)
            : imagecolorallocatealpha($img, 0xff, 0xff, 0xff, 90);
        fill_rounded_rect($img, $n['x'] + 46, $n['y'] + 56, $n['w'] - 92, 34, 17, $inner);
        fill_rounded_rect($img, $n['x'] + 46, $n['y'] + 116, (int) (($n['w'] - 92) * 0.65), 34, 17, $inner);
    }
}

/**
 * Accessibility/UI motif: focus-ringed card frame + contrast swatch tiles.
 *
 * @param callable(int, int): int $rng
 * @param array<string, int> $p
 */
function draw_accessibility_motif($img, int $w, int $h, callable $rng, array $p): void
{
    $warm_bg = $rng(0, 1) === 0;
    imagefilledrectangle($img, 0, 0, $w, $h, $warm_bg ? $p['papyrus'] : $p['blue']);

    $grid_clr = $warm_bg
        ? imagecolorallocatealpha($img, 0x1d, 0x23, 0x27, 114)
        : imagecolorallocatealpha($img, 0xf1, 0xed, 0xd9, 108);

    imagesetthickness($img, 3);
    for ($gx = 240; $gx < $w; $gx += 240) {
        imageline($img, $gx, 90, $gx, $h - 90, $grid_clr);
    }

    $flip = $rng(0, 1) === 1;
    $card_x = $flip ? 1320 : 260;
    $card_y = $rng(210, 290);
    $card_w = 820;
    $card_h = 740;

    // Focus halo around UI card
    $ring_clr = $warm_bg ? $p['blue'] : $p['peach'];
    fill_rounded_rect($img, $card_x - 32, $card_y - 32, $card_w + 64, $card_h + 64, 56, $ring_clr);
    fill_rounded_rect($img, $card_x - 14, $card_y - 14, $card_w + 28, $card_h + 28, 44, $warm_bg ? $p['papyrus'] : $p['blue']);
    fill_rounded_rect($img, $card_x, $card_y, $card_w, $card_h, 36, $p['white']);

    // UI skeleton inside card
    $bar_muted = imagecolorallocatealpha($img, 0x5c, 0x64, 0x70, 65);
    fill_rounded_rect($img, $card_x + 80, $card_y + 110, 440, 56, 28, $p['ink']);
    fill_rounded_rect($img, $card_x + 80, $card_y + 220, 640, 34, 17, $bar_muted);
    fill_rounded_rect($img, $card_x + 80, $card_y + 284, 520, 34, 17, $bar_muted);
    fill_rounded_rect($img, $card_x + 80, $card_y + 470, 320, 116, 24, $p['peach']);

    // 2x2 contrast swatches
    $sw_x0 = $flip ? 280 : 1280;
    $sw_y0 = $rng(220, 290);
    $swatches = [
        [$sw_x0,       $sw_y0,       $warm_bg ? $p['blue'] : $p['papyrus']],
        [$sw_x0 + 420, $sw_y0,       $p['teal']],
        [$sw_x0,       $sw_y0 + 390, $p['ink']],
        [$sw_x0 + 420, $sw_y0 + 390, $p['peach']],
    ];
    foreach ($swatches as [$sx, $sy, $sclr]) {
        fill_rounded_rect($img, $sx, $sy, 370, 340, 40, $sclr);
        $dot = ($sclr === $p['peach'] || $sclr === $p['papyrus']) ? $p['ink'] : $p['white'];
        imagefilledellipse($img, $sx + 185, $sy + 170, 116, 116, $dot);
    }
}

/**
 * Security motif: layered protective concentric arcs and balanced strata.
 *
 * @param callable(int, int): int $rng
 * @param array<string, int> $p
 */
function draw_security_motif($img, int $w, int $h, callable $rng, array $p): void
{
    $bg = $rng(0, 1) === 0 ? $p['blue'] : $p['ink'];
    imagefilledrectangle($img, 0, 0, $w, $h, $bg);

    $cx = $rng(1360, 1680);
    $cy = (int) ($h / 2);

    $ring_a = imagecolorallocatealpha($img, 0x2e, 0x6e, 0x62, 42);
    $ring_b = imagecolorallocatealpha($img, 0xf1, 0xed, 0xd9, 64);
    stroke_ring($img, $cx, $cy, 1560, 88, $ring_a);
    stroke_ring($img, $cx, $cy, 1120, 64, $ring_b);
    stroke_ring($img, $cx, $cy, 720, 44, $p['peach']);

    imagefilledellipse($img, $cx, $cy, 400, 400, $p['papyrus']);
    imagefilledellipse($img, $cx, $cy, 190, 190, $p['teal']);

    // Layered horizontal strata bars on left
    $bar_x = $rng(180, 260);
    $bars = [
        ['y' => 300, 'w' => $rng(620, 820), 'clr' => $p['papyrus']],
        ['y' => 480, 'w' => $rng(480, 720), 'clr' => $p['teal']],
        ['y' => 660, 'w' => $rng(680, 880), 'clr' => $p['peach']],
        ['y' => 840, 'w' => $rng(420, 640), 'clr' => $p['sky']],
    ];
    foreach ($bars as $b) {
        fill_rounded_rect($img, $bar_x, $b['y'], $b['w'], 96, 48, $b['clr']);
    }
}

/**
 * General geometric motif: balanced Bauhaus shapes and arcs.
 *
 * @param callable(int, int): int $rng
 * @param array<string, int> $p
 */
function draw_general_motif($img, int $w, int $h, callable $rng, array $p): void
{
    $bgs = [$p['blue'], $p['teal'], $p['ink']];
    $bg = $bgs[$rng(0, 2)];
    imagefilledrectangle($img, 0, 0, $w, $h, $bg);

    $soft = imagecolorallocatealpha($img, 0xf1, 0xed, 0xd9, 96);
    draw_dot_grid($img, 160, 160, 10, 5, 220, 16, $soft);

    $c1_x = $rng(480, 900);
    $c1_y = $rng(340, 880);
    $c1_d = $rng(560, 820);
    imagefilledellipse($img, $c1_x, $c1_y, $c1_d, $c1_d, $p['papyrus']);

    $c2_x = $rng(1400, 1920);
    $c2_y = $rng(260, 900);
    $c2_d = $rng(440, 700);
    imagefilledellipse($img, $c2_x, $c2_y, $c2_d, $c2_d, $p['peach']);
    stroke_ring($img, $c2_x, $c2_y, $c2_d + 160, 36, $p['sky']);

    $pill_clr = $bg === $p['teal'] ? $p['blue'] : $p['teal'];
    fill_rounded_rect($img, $rng(220, 460), $rng(180, 320), $rng(680, 920), 140, 70, $pill_clr);
    fill_rounded_rect($img, $rng(1020, 1360), $rng(820, 960), $rng(640, 880), 120, 60, $p['orange']);
}

/**
 * WP-CLI command to backfill covers for existing published posts without a featured image.
 */
if (defined('WP_CLI') && WP_CLI) {
    \WP_CLI::add_command('ash generate-covers', function (array $args, array $assoc_args): void {
        $force = isset($assoc_args['force']);
        $posts = get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ]);

        $count = 0;
        foreach ($posts as $post) {
            if ($post instanceof WP_Post) {
                $id = ensure_post_cover($post, $force);
                if ($id) {
                    $count++;
                    \WP_CLI::log(sprintf('Generated cover #%d for post #%d (%s)', $id, $post->ID, $post->post_title));
                }
            }
        }
        \WP_CLI::success(sprintf('Processed %d post(s); generated %d cover(s).', count($posts), $count));
    });
}
