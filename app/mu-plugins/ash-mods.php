<?php

/*
Plugin Name: Ash Mods
Description: Site-wide hooks, mail transport, feed disabling, and head cleanup
Author: Ash Davies
Version: 0.2.0
*/

namespace Ash\Plugin;

defined('ABSPATH') || exit;

/**
 * Clean up <head> and HTTP headers
 */
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wp_generator');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

/**
 * Disable WordPress emojis (including WordPress 6.4+ / 7.x wp_enqueue_emoji_styles)
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('embed_head', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
remove_action('admin_enqueue_scripts', 'wp_enqueue_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('emoji_svg_url', '__return_false');
add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
});

/**
 * Disable RSS / Atom feeds completely
 */
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

$disable_feed = function () {
    wp_die(
        sprintf(
            /* translators: %s: home page URL */
            wp_kses(
                __('RSS feeds are disabled on this site. <a href="%s">Return to the homepage</a>.', 'ashdavies'),
                ['a' => ['href' => []]]
            ),
            esc_url(home_url('/'))
        ),
        esc_html__('Feeds Disabled', 'ashdavies'),
        ['response' => 410]
    );
};

foreach ([
    'do_feed',
    'do_feed_rdf',
    'do_feed_rss',
    'do_feed_rss2',
    'do_feed_atom',
    'do_feed_rss2_comments',
    'do_feed_atom_comments',
] as $feed_hook) {
    add_action($feed_hook, $disable_feed, 1);
}

/**
 * Remove the website URL field from comment forms
 */
add_filter('comment_form_default_fields', function (array $fields): array {
    unset($fields['url']);
    return $fields;
});

/**
 * Ensure PHPMailer inherits SMTP credentials from environment variables
 */
add_action('phpmailer_init', function ($phpmailer) {
    $hostname = getenv('SMTP_HOSTNAME');
    if (empty($hostname)) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = $hostname;
    $phpmailer->Port = (int) (getenv('SMTP_PORT') ?: 587);

    $username = getenv('SMTP_USER');
    $password = getenv('SMTP_PASSWORD');
    if ($username !== false && $username !== '') {
        $phpmailer->Username = $username;
    }
    if ($password !== false && $password !== '') {
        $phpmailer->Password = $password;
    }

    $from = getenv('SMTP_FROM');
    $from_name = getenv('SMTP_FROM_NAME');
    if (!empty($from)) {
        $phpmailer->From = $from;
    }
    if (!empty($from_name)) {
        $phpmailer->FromName = $from_name;
    }

    // Local development uses a plain SMTP mail catcher (Mailpit) with no
    // authentication or TLS. Set SMTP_INSECURE=1 in the local environment to
    // opt out of auth/TLS. Production leaves this unset and behaves as before.
    if (getenv('SMTP_INSECURE')) {
        $phpmailer->SMTPAuth = false;
        $phpmailer->SMTPSecure = '';
        $phpmailer->SMTPAutoTLS = false;
    } else {
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->SMTPAutoTLS = true;
    }

    // Never log SMTP debug traffic in production/staging
    if (in_array(wp_get_environment_type(), ['local', 'development'], true)) {
        $phpmailer->SMTPDebug = 2;
        $phpmailer->Debugoutput = function ($str) {
            static $logging = true;
            if ($logging === false && strpos($str, 'SERVER -> CLIENT') !== false) {
                $logging = true;
            }
            if ($logging) {
                error_log('SMTP ' . $str);
            }
            if (strpos($str, 'SERVER -> CLIENT: 354') !== false) {
                $logging = false;
            }
        };
    }
});

// Prevent WordPress from overriding the SMTP FROM address (Office 365 / SES compatibility)
add_filter('wp_mail_from', function ($email) {
    $from = getenv('SMTP_FROM');
    if (!empty($from)) {
        $sanitized = sanitize_email($from);
        if (!empty($sanitized)) {
            return $sanitized;
        }
    }
    return $email;
}, 10, 1);

add_filter('wp_mail_from_name', function ($name) {
    $from_name = getenv('SMTP_FROM_NAME');
    return !empty($from_name) ? $from_name : $name;
}, 10, 1);
