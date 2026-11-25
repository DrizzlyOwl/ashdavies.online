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
    <script>
        (function () {
            try {
                var theme = localStorage.getItem('ash-theme');
                if (theme === 'light' || theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', theme);
                }
            } catch (e) {}
        })();
    </script>
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
            <div class="site-header__controls">
                <?php wp_nav_menu([
                    'menu' => 'primary',
                    'container' => 'nav',
                    'container_aria_label' => esc_attr__('Primary Navigation', 'ashdavies'),
                    'menu_class' => 'nav',
                    'theme_location' => 'primary',
                ]); ?>
                <button
                    type="button"
                    class="theme-toggle"
                    id="theme-toggle"
                    aria-pressed="false"
                >
                    <span class="theme-toggle__icon theme-toggle__icon--moon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z"/></svg>
                    </span>
                    <span class="theme-toggle__icon theme-toggle__icon--sun" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    </span>
                    <span><?php esc_html_e('Dark mode', 'ashdavies'); ?></span>
                </button>
            </div>
        </div>
    </header>
