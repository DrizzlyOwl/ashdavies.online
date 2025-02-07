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
                        width="500" height="500">
                </div>
            </div>
            <div class="masthead__copy">
                <h1 class="masthead__heading"><?php the_title(); ?></h1>
                <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <a href="https://www.linkedin.com/in/drizzlyowl/" aria-label="Connect with me on LinkedIn" class="masthead__connect">
                    <img src="<?php theme_dir(); ?>/dist/linkedin.svg" alt="LinkedIn icon" width="20" height="20">
                    <span>
                        <?php _e("Connect on LinkedIn", 'ashdavies'); ?>
                    </span>
                </a>
                <a href="/cv" aria-label="Read about my skills" class="masthead__connect">
                    <img src="<?php theme_dir(); ?>/dist/document.svg" alt="File icon" width="20" height="20">
                    <span>
                        <?php _e("View My Resume", 'ashdavies'); ?>
                    </span>
                </a>
                <a href="https://github.com/DrizzlyOwl" aria-label="Connect with me on GitHub" class="masthead__connect">
                    <img src="<?php theme_dir(); ?>/dist/github.svg" alt="GitHub icon" width="20" height="20">
                    <span >
                        <?php _e("See My Work", 'ashdavies'); ?>
                    </span>
                </a><br>
                <a href="https://steamcommunity.com/id/drizzlyowl" aria-label="Find me on Steam" class="masthead__connect masthead__connect--steam">
                    <img src="<?php theme_dir(); ?>/dist/steam.svg" alt="Steam icon" width="20" height="20">
                    <span >
                        <?php _e("See What I'm Playing", 'ashdavies'); ?>
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
            <div class="skill">
                <figure class="skill__icon skill__icon--azure">
                    <img src="<?php theme_dir(); ?>/dist/azure.svg" alt="Microsoft Azure icon">
                    <figcaption class="screen-reader-text"><?php _e("Azure", 'ashdavies'); ?></figcaption>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--sys">
                    <img src="<?php theme_dir(); ?>/dist/ubuntu.svg" alt="Ubuntu icon">
                    <figcaption class="screen-reader-text"><?php _e("Ubuntu", 'ashdavies'); ?>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--terraform">
                    <img src="<?php theme_dir(); ?>/dist/terraform.svg" alt="Terraform icon">
                    <figcaption class="screen-reader-text"><?php _e("Terraform", 'ashdavies'); ?></figcaption>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--docker">
                    <img src="<?php theme_dir(); ?>/dist/docker.svg" alt="Docker icon">
                    <figcaption class="screen-reader-text"><?php _e("Docker", 'ashdavies'); ?></figcaption>
                </figure>
            </div>
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
