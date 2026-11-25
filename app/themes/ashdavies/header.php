<?php

namespace Ash;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <p class="site-header__help"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Ash Davies', 'ashdavies'); ?></a></p>
            <?php wp_nav_menu([
                'menu' => 'primary',
                'container' => 'nav',
                'container_aria_label' => esc_attr__('Primary Navigation', 'ashdavies'),
                'menu_class' => 'nav',
                'theme_location' => 'primary',
            ]); ?>
        </div>
    </header>
