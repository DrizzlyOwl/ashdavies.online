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
                    <img class="avatar__image" src="<?php theme_dir(); ?>/dist/profile.jpg" alt="Picture of Ash Davies" width="200" height="200">
                </div>
            </div>
            <div class="masthead__copy">
                <h1 class="masthead__heading"><?php the_title(); ?></h1>
                <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <p class="masthead__connect">
                    <img src="<?php theme_dir(); ?>/dist/twitter.svg" alt="Twitter icon" width="20" height="20">
                    <a href="https://twitter.com/DrizzlyOwl" aria-label="Connect with me on Twitter @DrizzlyOwl">
                        <?php _e("DrizzlyOwl", 'ashdavies'); ?>
                    </a>
                </p>
            </div>
        </header>
    </div>
    <div class="skillbox">
        <h2 id="coreskills" class="skillbox__title"><?php _e("My core skills", 'ashdavies'); ?></h2>
        <div class="skillbox__wrap">
            <article class="skill">
                <figure class="skill__icon skill__icon--wp">
                    <img src="<?php theme_dir(); ?>/dist/wp.svg" alt="WordPress icon">
                    <figcaption class="screen-reader-text"><?php _e("WordPress", 'ashdavies'); ?></figcaption>
                </figure>
                <h3 class="skill__heading"><?php _e("WordPress", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php _e("I continue to build and maintain bespoke themes and plugins for the NHS, Police, local
                    councils and other public sector entities. It's my bread and butter.", 'ashdavies'); ?>
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--php">
                    <img src="<?php theme_dir(); ?>/dist/php.svg" alt="PHP icon">
                    <figcaption class="screen-reader-text">
                        <abbr title="<?php _e("PHP Hypertext Preprocessor", 'ashdavies'); ?>">
                            <?php _e("PHP", 'ashdavies'); ?>
                        </abbr>
                    </figcaption>
                </figure>
                <h3 class="skill__heading"><?php _e("PHP", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php
                    _e("I'm no stranger to frameworks like ", 'ashdavies');
                    echo wp_sprintf(
                        "<a href='%s' rel='nofollow'>%s</a>",
                        "https://laravel.com/",
                        __("Laravel", 'ashdavies')
                    );
                    _e(" and ", 'ashdavies');
                    echo wp_sprintf(
                        "<a href='%s' rel='nofollow'>%s</a>",
                        "https://symfony.com/",
                        __("Symfony", 'ashdavies')
                    );
                    _e(" and love to get stuck in to some <abbr title='Object-oriented Programming'>OOP</abbr> once in
                    a while.", 'ashdavies');
                    ?>
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--sys">
                    <img src="<?php theme_dir(); ?>/dist/ubuntu.svg" alt="Ubuntu icon">
                    <figcaption class="screen-reader-text"><?php _e("Ubuntu enterprise", 'ashdavies'); ?></figcaption>
                </figure>
                <h3 class="skill__heading"><?php _e("Ubuntu", 'ashdavies'); ?></h3>
                <p class="skill__text">
                    <?php
                    _e("I'm adept at using <abbr title='Command Line Interface'>CLI</abbr> . I'm responsible for the
                    upkeep, configuration, and reliable operation of Cloud Hosting within Mixd.", 'ashdavies');
                    ?>
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
