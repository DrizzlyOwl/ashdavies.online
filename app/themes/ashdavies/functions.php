<?php

/**
 * Theme functions
 */

namespace Ash;

use DOMDocument;

defined('ABSPATH') || exit;

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 960;
    }

    add_theme_support('title-tag');
    add_theme_support('html5', [
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('yoast-seo-breadcrumbs');

    add_editor_style('editor.css');

    register_nav_menu('primary', __('Primary Menu', 'ashdavies'));
});

add_action('init', function () {
    // Add missing features to Post Types
    add_post_type_support('page', ['excerpt', 'thumbnail']);

    unregister_block_pattern_category('buttons');
    unregister_block_pattern_category('columns');
    unregister_block_pattern_category('gallery');
    unregister_block_pattern_category('header');
    unregister_block_pattern_category('text');

    wp_register_style(
        '@ash/main',
        get_theme_dir() . '/style.css',
        [],
        filemtime(get_template_directory() . '/style.css'),
        'all'
    );

    wp_register_script(
        '@ash/theme-toggle',
        get_theme_dir() . '/dist/theme-toggle.js',
        [],
        filemtime(get_template_directory() . '/dist/theme-toggle.js'),
        ['in_footer' => true, 'strategy' => 'defer']
    );

    wp_register_script(
        '@ash/reveal',
        get_theme_dir() . '/dist/reveal.js',
        [],
        filemtime(get_template_directory() . '/dist/reveal.js'),
        ['in_footer' => true, 'strategy' => 'defer']
    );
});

function theme_dir()
{
    echo esc_url(get_theme_dir());
}

function get_theme_dir()
{
    return get_stylesheet_directory_uri();
}

/**
 * Enqueue the stylesheet & JS
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('@ash/main');
    wp_enqueue_script('@ash/theme-toggle');
    wp_enqueue_script('@ash/reveal');
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
});

/**
 * Remove the default theme styles that get shipped with WP
 */
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

/**
 * Parse <h2> headings in content, injecting deterministic unique IDs where missing,
 * and returning both the updated HTML and extracted anchor metadata in a single pass.
 *
 * @param string $content
 * @return array{content: string, anchors: array<int, array{id: string, title: string}>}
 */
function parse_heading_anchors(string $content = ''): array
{
    if (empty($content) || !strlen(trim($content))) {
        return ['content' => '', 'anchors' => []];
    }

    static $cache = [];
    $cache_key = md5($content);
    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    // Convert HTML entities to UTF-8 symbols
    $enforce_utf8 = mb_encode_numericentity(
        htmlspecialchars_decode(
            htmlentities($content, ENT_NOQUOTES, 'UTF-8', false),
            ENT_NOQUOTES
        ),
        [0x80, 0x10FFFF, 0, ~0],
        'UTF-8'
    );

    $dom = new DOMDocument('1.1', 'UTF-8');
    $dom->loadHTML('<div>' . $enforce_utf8 . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);

    /**
     * IMPORTANT
     * This is required to handle special characters - in this case apostrophes.
     * It replaces HTML entity number with the right single quotation mark NOT the standard single quotation mark.
     */
    $content = str_replace('&#8217;', '’', $content);

    $anchors = [];
    $seen_ids = [];
    $index = 0;
    $search_offset = 0;

    foreach ($dom->getElementsByTagName('h2') as $anchor) {
        /** @var \DOMElement $anchor */
        $index++;
        $old_html = $dom->saveHTML($anchor);
        $title = $anchor->textContent;

        if ($anchor->hasAttribute('id')) {
            $id = $anchor->getAttribute('id');
            $seen_ids[$id] = true;
            $anchors[] = [
                'id' => $id,
                'title' => $title,
            ];
            $pos = strpos($content, $old_html, $search_offset);
            if ($pos !== false) {
                $search_offset = $pos + strlen($old_html);
            }
            continue;
        }

        $str = preg_replace('/[^a-z0-9]/i', '-', $title);
        $base_id = sanitize_title($str);
        if (empty($base_id)) {
            $base_id = 'section-' . $index;
        }

        $id = $base_id;
        $suffix = 2;
        while (isset($seen_ids[$id])) {
            $id = $base_id . '-' . $suffix;
            $suffix++;
        }
        $seen_ids[$id] = true;

        $anchor->setAttribute('id', $id);
        $new_html = $dom->saveHTML($anchor);
        $pos = strpos($content, $old_html, $search_offset);
        if ($pos !== false) {
            $content = substr_replace($content, $new_html, $pos, strlen($old_html));
            $search_offset = $pos + strlen($new_html);
        }

        $anchors[] = [
            'id' => $id,
            'title' => $title,
        ];
    }

    $cache[$cache_key] = [
        'content' => $content,
        'anchors' => $anchors,
    ];

    return $cache[$cache_key];
}

/**
 * When Post content is being displayed, update the headings to use ID anchors if they are missing
 *
 * @param string $content
 * @return string
 */
function set_heading_anchors(string $content = '')
{
    $parsed = parse_heading_anchors($content);
    return $parsed['content'];
}

/**
 * Get Heading IDs from the Post Content of a given Post ID
 *
 * @param int $post_id
 * @return array
 */
function get_heading_anchors(int $post_id = 0)
{
    if ($post_id === 0) {
        $post_id = (int) get_the_ID();
    }

    static $anchors_cache = [];
    if (isset($anchors_cache[$post_id])) {
        return $anchors_cache[$post_id];
    }

    $post = get_post($post_id);
    if (!is_a($post, 'WP_Post') || empty($post->post_content)) {
        return [];
    }

    $parsed = parse_heading_anchors($post->post_content);
    $anchors_cache[$post_id] = $parsed['anchors'];

    return $anchors_cache[$post_id];
}

/**
 * Apply an id html attribute to all <h2> tags
 */
add_filter('the_content', 'Ash\set_heading_anchors', 10, 1);

/**
 * Return cached navigation data for the site footer
 *
 * @return array{
 *     categories: array<int, array{name: string, url: string}>,
 *     tags: array<int, array{name: string, url: string}>,
 *     posts: array<int, array{title: string, url: string}>,
 *     pages: array<int, array{title: string, url: string}>
 * }
 */
function get_footer_nav_data(): array
{
    static $local_cache = null;
    if ($local_cache !== null) {
        return $local_cache;
    }

    $transient_key = 'ash_footer_nav_data';
    $cached = get_transient($transient_key);
    if (is_array($cached)) {
        $local_cache = $cached;
        return $local_cache;
    }

    $data = [
        'categories' => [],
        'tags' => [],
        'posts' => [],
        'pages' => [],
    ];

    $categories = get_terms(['taxonomy' => 'category']);
    if (!is_wp_error($categories) && !empty($categories)) {
        foreach ($categories as $term) {
            $term_link = get_term_link($term);
            if (!is_wp_error($term_link)) {
                $data['categories'][] = [
                    'name' => $term->name,
                    'url' => $term_link,
                ];
            }
        }
    }

    $tags = get_terms(['taxonomy' => 'post_tag', 'number' => 10]);
    if (!is_wp_error($tags) && !empty($tags)) {
        foreach ($tags as $term) {
            $term_link = get_term_link($term);
            if (!is_wp_error($term_link)) {
                $data['tags'][] = [
                    'name' => $term->name,
                    'url' => $term_link,
                ];
            }
        }
    }

    $recent_posts = wp_get_recent_posts([
        'post_status' => 'publish',
        'numberposts' => 15,
    ], OBJECT);
    if (!empty($recent_posts)) {
        foreach ($recent_posts as $recent_post) {
            $permalink = get_permalink($recent_post);
            if ($permalink) {
                $data['posts'][] = [
                    'title' => get_the_title($recent_post),
                    'url' => $permalink,
                ];
            }
        }
    }

    $pages = get_pages(['number' => 10]);
    if (!empty($pages)) {
        foreach ($pages as $page_item) {
            $permalink = get_permalink($page_item);
            if ($permalink) {
                $data['pages'][] = [
                    'title' => get_the_title($page_item),
                    'url' => $permalink,
                ];
            }
        }
    }

    set_transient($transient_key, $data, DAY_IN_SECONDS);
    $local_cache = $data;

    return $local_cache;
}

/**
 * Invalidate cached footer navigation data when content or terms change
 */
function flush_footer_nav_cache(): void
{
    delete_transient('ash_footer_nav_data');
}

add_action('save_post', 'Ash\flush_footer_nav_cache');
add_action('deleted_post', 'Ash\flush_footer_nav_cache');
add_action('created_term', 'Ash\flush_footer_nav_cache');
add_action('edited_term', 'Ash\flush_footer_nav_cache');
add_action('delete_term', 'Ash\flush_footer_nav_cache');

/**
 * Determine whether the current post's last modified date is older than the given range
 */
function is_post_older_than(string $range): bool
{
    $modified = get_the_modified_time('Ymd');
    if (empty($modified)) {
        return false;
    }

    $last_updated = date_create_immutable_from_format('Ymd', (string) $modified);
    $interval = date_interval_create_from_date_string($range);

    if ($last_updated === false || $interval === false) {
        return false;
    }

    $cutoff = date_create_immutable('now')->sub($interval);

    return $cutoff->format('Ymd') >= $last_updated->format('Ymd');
}

/**
 * Return estimated reading time in minutes, falling back to word count (~200 wpm)
 * when the `_estimated-reading-time-minutes` meta key is absent.
 */
function get_reading_time(int $post_id = 0): int
{
    if ($post_id === 0) {
        $post_id = (int) get_the_ID();
    }

    if ($post_id <= 0) {
        return 0;
    }

    $meta = (int) get_post_meta($post_id, '_estimated-reading-time-minutes', true);
    if ($meta > 0) {
        return $meta;
    }

    $post = get_post($post_id);
    if (!$post || empty($post->post_content)) {
        return 0;
    }

    $words = str_word_count(wp_strip_all_tags($post->post_content));
    return $words > 0 ? max(1, (int) ceil($words / 200)) : 0;
}
