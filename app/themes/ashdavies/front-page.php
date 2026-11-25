<?php

/**
 * Frontpage template
 */

namespace Ash;

get_header();

if (have_posts()) {
    the_post();
}
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead">
        <header class="masthead__inner">
            <div class="avatar">
                <div class="avatar__wrap">
                    <img class="avatar__image" src="<?php echo esc_url(get_theme_dir() . '/dist/profile.jpg'); ?>" alt="<?php esc_attr_e('Picture of Ash Davies', 'ashdavies'); ?>"
                        width="500" height="500">
                </div>
            </div>
            <div class="masthead__copy">
                <h1 class="masthead__heading"><?php the_title(); ?></h1>
                <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <a href="https://www.linkedin.com/in/drizzlyowl/" class="masthead__connect">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/linkedin.svg'); ?>" alt="" width="20" height="20">
                    <span>
                        <?php esc_html_e('Connect on LinkedIn', 'ashdavies'); ?>
                    </span>
                </a>
                <a href="<?php echo esc_url(home_url('/cv')); ?>" class="masthead__connect">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/document.svg'); ?>" alt="" width="20" height="20">
                    <span>
                        <?php esc_html_e('View My Resume', 'ashdavies'); ?>
                    </span>
                </a>
                <a href="https://github.com/DrizzlyOwl" class="masthead__connect">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/github.svg'); ?>" alt="" width="20" height="20">
                    <span>
                        <?php esc_html_e('See My Work', 'ashdavies'); ?>
                    </span>
                </a><br>
                <a href="https://steamcommunity.com/id/drizzlyowl" class="masthead__connect masthead__connect--steam">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/steam.svg'); ?>" alt="" width="20" height="20">
                    <span>
                        <?php esc_html_e("See What I'm Playing", 'ashdavies'); ?>
                    </span>
                </a>
            </div>
        </header>
        <div class="masthead__shim masthead__shim--circle masthead__shim--top-left"></div>
        <div class="masthead__shim masthead__shim--circle masthead__shim--bottom-right"></div>
    </div>
    <div class="skillbox">
        <h2 id="coreskills" class="skillbox__title"><?php esc_html_e('My core skills', 'ashdavies'); ?></h2>
        <div class="skillbox__wrap">
            <div class="skill">
                <figure class="skill__icon skill__icon--azure">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/azure.svg'); ?>" alt="">
                    <figcaption class="screen-reader-text"><?php esc_html_e('Azure', 'ashdavies'); ?></figcaption>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--sys">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/ubuntu.svg'); ?>" alt="">
                    <figcaption class="screen-reader-text"><?php esc_html_e('Ubuntu', 'ashdavies'); ?></figcaption>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--terraform">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/terraform.svg'); ?>" alt="">
                    <figcaption class="screen-reader-text"><?php esc_html_e('Terraform', 'ashdavies'); ?></figcaption>
                </figure>
            </div>
            <div class="skill">
                <figure class="skill__icon skill__icon--docker">
                    <img src="<?php echo esc_url(get_theme_dir() . '/dist/docker.svg'); ?>" alt="">
                    <figcaption class="screen-reader-text"><?php esc_html_e('Docker', 'ashdavies'); ?></figcaption>
                </figure>
            </div>
        </div>
    </div>
    <article <?php post_class('contentarea'); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content">
                    <?php the_content(); ?>
                </div>
                <aside class="content-sidebar-group__sidebar">
                    <div class="avatar avatar--centered">
                        <div class="avatar__wrap">
                            <img class="avatar__image" src="<?php echo esc_url(get_theme_dir() . '/dist/drizzlyowl.jpeg'); ?>" alt="<?php esc_attr_e('An illustration of a wet owl', 'ashdavies'); ?>" width="200" height="200">
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>
</main>
<?php
get_footer();
