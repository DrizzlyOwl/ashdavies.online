<?php

/**
 * Frontpage template
 */

namespace Ash;

get_header();

the_post();
?>
<main id="contentarea">
    <div class="masthead">
        <header class="masthead__inner">
            <div class="avatar">
                <div class="avatar__wrap">
                    <img class="avatar__image" src="<?php theme_dir(); ?>/dist/profile.jpg" alt="Picture of Ash Davies"
                        width="200" height="200">
                </div>
            </div>
            <div class="masthead__copy">
                <h1 class="masthead__heading"><?php the_title(); ?></h1>
                <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <a href="https://github.com/DrizzlyOwl" aria-label="Connect with me on GitHub @DrizzlyOwl" class="masthead__connect">
                    <img src="<?php theme_dir(); ?>/dist/github.svg" alt="GitHub icon" width="20" height="20">
                    <span >
                        <?php _e("GitHub", 'ashdavies'); ?>
                    </span>
                </a>
                <a href="https://steamcommunity.com/id/drizzlyowl" aria-label="Find me on Steam @DrizzlyOwl" class="masthead__connect masthead__connect--steam">
                    <img src="<?php theme_dir(); ?>/dist/steam.svg" alt="Steam icon" width="20" height="20">
                    <span >
                        <?php _e("Steam", 'ashdavies'); ?>
                    </span>
                </a>
            </div>
        </header>
        <div class="masthead__shim masthead__shim--circle masthead__shim--top-left"></div>
        <div class="masthead__shim masthead__shim--circle masthead__shim--bottom-right"></div>
    </div>
    <div class="skillbox">
        <h2 id="coreskills" class="skillbox__title"><?php _e("My core skills", 'ashdavies'); ?></h2>
        <div class="skillbox__wrap">
            <article class="skill">
                <figure class="skill__icon skill__icon--azure">
                    <img src="<?php theme_dir(); ?>/dist/azure.svg" alt="Microsoft Azure icon">
                    <figcaption class="screen-reader-text"><?php _e("Azure", 'ashdavies'); ?></figcaption>
                </figure>
                <h3 class="skill__heading"><?php _e("Microsoft Azure", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php _e("I continue to develop my skills within the Azure ecosystem,", 'ashdavies'); ?>
                    <?php _e("supporting dxw with their technical operations.", 'ashdavies'); ?>
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--sys">
                    <img src="<?php theme_dir(); ?>/dist/ubuntu.svg" alt="Ubuntu icon">
                    <figcaption class="screen-reader-text"><?php _e("Ubuntu", 'ashdavies'); ?>
                </figure>
                <h3 class="skill__heading"><?php _e("Linux SysAdmin", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php _e("I'm no stranger to getting stuck in with CLI tooling,", 'ashdavies'); ?>
                    <?php _e("crafting my own bash scripts or managing linux servers.", 'ashdavies'); ?>
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--terraform">
                    <img src="<?php theme_dir(); ?>/dist/terraform.svg" alt="Terraform icon">
                    <figcaption class="screen-reader-text"><?php _e("Terraform", 'ashdavies'); ?></figcaption>
                </figure>
                <h3 class="skill__heading"><?php _e("Terraform", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php _e("I'm readily able to write, and deploy clean, maintainable", 'ashdavies'); ?>
                    <?php _e("Infrastructure as Code for AWS and Azure.", 'ashdavies'); ?>
                </p>
            </article>
        </div>
    </div>
    <article <?php post_class("contentarea"); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content">
                    <?php the_content(); ?>
                </div>
                <aside class="content-sidebar-group__sidebar">
                    <div class="avatar avatar--centered">
                        <div class="avatar__wrap">
                            <img class="avatar__image"  src="<?php theme_dir(); ?>/dist/drizzlyowl.jpeg" alt="An illustration of a wet owl" width="200" height="200">
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>
</main>
<?php
get_footer();
