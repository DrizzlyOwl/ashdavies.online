<?php

/**
 * Theme functions
 */

namespace Ash;

add_action('init', function () {
    add_post_type_support('page', 'excerpt');
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
    wp_enqueue_style(
        '@ash/main',
        get_theme_dir() . '/style.css',
        [],
        filemtime(get_template_directory() . '/style.css'),
        'screen'
    );

    wp_enqueue_script(
        '@ash/main',
        get_theme_dir() . '/dist/scripts.min.js',
        [],
        filemtime(get_template_directory() . '/dist/scripts.min.js'),
        true
    );
});
