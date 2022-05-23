<?php

/**
 * Theme functions
 */

namespace Ash;

use DOMDocument;

$env = "production";

if (strpos(get_bloginfo('url'), ".local")) {
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

    // wp_register_script(
    //     '@ash/main',
    //     get_theme_dir() . '/dist/scripts.min.js',
    //     [],
    //     filemtime(get_template_directory() . '/dist/scripts.min.js'),
    //     true
    // );

    if (is_admin() == false) {
        wp_deregister_script('wp-embed');
        wp_deregister_style('wp-block-library');
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
    wp_enqueue_style('@ash/main');
    // wp_enqueue_script('@ash/main');
});

/**
 * Enqueue assets for the Gutenberg editor
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('@ash/gutenberg');
});


/**
 * When Post content is being displayed, update the headings to use ID anchors if they are missing
 *
 * @param string $content
 * @return string
 */
function set_heading_anchors(string $content = '')
{
    // Abort early if there is no content to parse
    if (empty($content)) {
        return '';
    }

    // If there is any content entered in to this post
    if (strlen(trim($content))) {
        // Convert HTML entities to UTF-8 symbols
        $enforce_utf8 = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

        // Instantiate a new DOMDocument HTML parser
        $dom = new DOMDocument('1.1', 'UTF-8');

        $dom->loadHTML($enforce_utf8, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);

        /**
         * IMPORTANT
         * This is required to handle special characters - in this case apostrophes.
         * It replaces HTML entity number with the right single quotation mark NOT the standard single quotation mark.
         */
        $content = str_replace('&#8217;', '’', $content);

        // Iterate through each <h2> Tag
        foreach ($dom->getElementsByTagName('h2') as $anchor) {
            /**
             * @var \DOMElement $anchor
             */

            // Get the OLD html node
            $old_html = $dom->saveHTML($anchor);

            // Get the title text between the opening and closing tags
            $title = $anchor->textContent;

            // Determine whether the <h2> is missing an ID attribute
            if (!$anchor->hasAttribute('id')) {
                // Create a pseudo-id out of the <h2> text
                $str = preg_replace('/[^a-z0-9]/i', '-', $title);
                $id = sanitize_title($str);

                // Set the new attribute
                $anchor->setAttribute('id', $id);

                // Update the old html with the new html
                $new_html = $dom->saveHTML($anchor);
                $content = str_replace($old_html, $new_html, $content);
            }
        }
    }

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
    $anchors = [];

    if ($post_id == 0) {
        $post_id = get_the_ID();
    }

    $post = get_post($post_id);

    // Exit early if the Post cannot be loaded
    if (!is_a($post, 'WP_Post')) {
        return [];
    }

    // Exit early if there is no Post Content
    if (empty($post->post_content)) {
        return [];
    }

    // Format the content to add in missing anchors
    $content = set_heading_anchors($post->post_content);

    // Instantiate a new DOMDocument HTML parser
    $dom = new DOMDocument('1.1', 'UTF-8');

    $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR);

    // Iterate through each <h2> Tag
    foreach ($dom->getElementsByTagName('h2') as $anchor) {
        // Get the title text between the opening and closing tags
        $title = $anchor->textContent;

        // Determine whether the <h2> is missing an ID attribute
        if ($anchor->hasAttribute('id')) {
            // Add this anchor to an array for passing to a component
            $anchors[] = [
                'id' => $anchor->getAttribute('id'),
                'title' => $title
            ];
        }
    }

    return $anchors;
}


/**
 * Apply an id html attributes to all <h2> tags
 */
add_filter('the_content', 'Ash\set_heading_anchors', 10, 1);
