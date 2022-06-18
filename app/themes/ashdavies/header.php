<?php

namespace Ash;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="manifest" href="<?php theme_dir(); ?>/dist/manifest.json">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skiplink" href="#contentarea">
    <?php _e("Skip to start of content", 'ashdavies'); ?>
</a>
<div id="app">
    <header class="site-header">
        <div class="wrapper">
            <p class="site-header__help"><?php _e("Pages"); ?></p>
            <?php wp_nav_menu([
                'container' => 'nav',
                'menu_class' => 'nav'
            ]); ?>
        </div>
    </header>
