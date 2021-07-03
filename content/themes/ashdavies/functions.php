<?php

/**
 * Theme functions
 */

namespace Ash;

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
    wp_enqueue_style('@ash/main');
    wp_enqueue_script('@ash/main');
});

/**
 * Enqueue assets for the Gutenberg editor
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('@ash/gutenberg');
});
