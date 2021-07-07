<?php

/**
 * Theme functions
 */

namespace Ash;

$env = "production";
if (strpos(WP_HOME, ".local")) {
    $env = "development";
}
define('WP_ENVIRONMENT_TYPE', $env);

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wp_generator');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

add_action('init', function () {
    // Define the global content width
    if (!isset($content_width)) {
        $content_width = 960;
    }

    // Theme support registrations
    add_theme_support('admin-bar');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', [
        'comment-list',
        'comment-form',
        // 'search-form',
        // 'gallery',
        // 'caption',
        // 'style',
        // 'script'
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('yoast-seo-breadcrumbs');

    // Add missing features to Post Types
    add_post_type_support('page', ['excerpt', 'thumbnail']);

    unregister_block_pattern_category('buttons');
    unregister_block_pattern_category('columns');
    unregister_block_pattern_category('gallery');
    unregister_block_pattern_category('header');
    unregister_block_pattern_category('text');

    $block_patterns = [
        'core/text-two-columns',
        'core/two-buttons',
        'core/two-images',
        'core/text-two-columns-with-images',
        'core/text-three-columns-buttons',
        'core/large-header',
        'core/large-header-button',
        'core/three-buttons',
        'core/heading-paragraph',
        'core/quote',
    ];
    foreach ($block_patterns as $pattern) {
        unregister_block_pattern($pattern);
    }

    wp_register_style(
        '@ash/main',
        get_theme_dir() . '/style.css',
        [],
        filemtime(get_template_directory() . '/style.css'),
        'screen'
    );

    wp_register_style(
        '@ash/gutenberg',
        get_theme_dir() . '/gutenberg.css',
        [],
        filemtime(get_template_directory() . '/gutenberg.css'),
        'screen'
    );

    wp_register_script(
        '@ash/main',
        get_theme_dir() . '/dist/scripts.min.js',
        [],
        filemtime(get_template_directory() . '/dist/scripts.min.js'),
        true
    );

    wp_deregister_script('wp-embed');
    wp_deregister_style('wp-block-library');
});

function theme_dir()
{
    echo get_theme_dir();
}

function get_theme_dir()
{
    return get_stylesheet_directory_uri();
}

/**
 * Enqueue the stylesheet & JS
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('@ash/main');
});

/**
 * Enqueue assets for the Gutenberg editor
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('@ash/gutenberg');
});
