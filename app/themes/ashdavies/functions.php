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
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('yoast-seo-breadcrumbs');

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
 * When Post content is being displayed, update the headings to use ID anchors if they are missing
 *
 * @param string $content
 * @return string
 */
function set_heading_anchors(string $content = '')
{
    if (empty($content) || !strlen(trim($content))) {
        return '';
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

    $seen_ids = [];
    $index = 0;
    $search_offset = 0;

    foreach ($dom->getElementsByTagName('h2') as $anchor) {
        /** @var \DOMElement $anchor */
        $index++;
        $old_html = $dom->saveHTML($anchor);
        $title = $anchor->textContent;

        if ($anchor->hasAttribute('id')) {
            $seen_ids[$anchor->getAttribute('id')] = true;
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
    }

    $cache[$cache_key] = $content;
    return $content;
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

    $content = set_heading_anchors($post->post_content);
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

    $anchors = [];
    foreach ($dom->getElementsByTagName('h2') as $anchor) {
        if ($anchor->hasAttribute('id')) {
            $anchors[] = [
                'id' => $anchor->getAttribute('id'),
                'title' => $anchor->textContent,
            ];
        }
    }

    $anchors_cache[$post_id] = $anchors;
    return $anchors;
}

/**
 * Apply an id html attribute to all <h2> tags
 */
add_filter('the_content', 'Ash\set_heading_anchors', 10, 1);

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
