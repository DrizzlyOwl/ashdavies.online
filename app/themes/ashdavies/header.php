<?php

namespace Ash;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_theme_dir() . '/dist/favicon.svg'); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_theme_dir() . '/dist/favicon-180.png'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skiplink" href="#contentarea">
    <?php esc_html_e('Skip to start of content', 'ashdavies'); ?>
</a>
<div id="app">
    <header class="site-header">
        <div class="wrapper | site-header__wrapper">
            <p class="site-header__help">
                <a class="site-header__brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <img class="site-header__mark" src="<?php echo esc_url(get_theme_dir() . '/dist/favicon.svg'); ?>" alt="" width="28" height="28" decoding="async">
                    <span><?php esc_html_e('Ash Davies', 'ashdavies'); ?></span>
                </a>
            </p>
            <?php wp_nav_menu([
                'menu' => 'primary',
                'container' => 'nav',
                'container_aria_label' => esc_attr__('Primary Navigation', 'ashdavies'),
                'menu_class' => 'nav',
                'theme_location' => 'primary',
            ]); ?>
        </div>
    </header>
