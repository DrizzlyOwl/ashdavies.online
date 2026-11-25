<?php

/**
 * Frontpage template
 */

namespace Ash;

use WP_Query;

get_header();

if (have_posts()) {
    the_post();
}

$blog_page_id = (int) get_option('page_for_posts');
$blog_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog/');
$latest_posts = new WP_Query([
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 3,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
]);
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--hero">
        <header class="masthead__inner">
            <div class="avatar">
                <div class="avatar__wrap">
                    <picture>
                        <source
                            type="image/webp"
                            srcset="<?php echo esc_url(get_theme_dir() . '/dist/profile-300.webp'); ?> 1x, <?php echo esc_url(get_theme_dir() . '/dist/profile-600.webp'); ?> 2x"
                        >
                        <img
                            class="avatar__image"
                            src="<?php echo esc_url(get_theme_dir() . '/dist/profile-300.jpg'); ?>"
                            srcset="<?php echo esc_url(get_theme_dir() . '/dist/profile-300.jpg'); ?> 1x, <?php echo esc_url(get_theme_dir() . '/dist/profile-600.jpg'); ?> 2x"
                            alt="<?php esc_attr_e('Picture of Ash Davies', 'ashdavies'); ?>"
                            width="300"
                            height="300"
                            fetchpriority="high"
                            decoding="async"
                        >
                    </picture>
                </div>
            </div>
            <div class="masthead__copy">
                <h1 class="masthead__heading"><?php the_title(); ?></h1>
                <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <div class="masthead__actions">
                    <a href="https://www.linkedin.com/in/drizzlyowl/" class="masthead__connect">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 448 512" fill="currentColor"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
                        <span>
                            <?php esc_html_e('Connect on LinkedIn', 'ashdavies'); ?>
                        </span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/cv')); ?>" class="masthead__connect">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 384 512" fill="currentColor"><path d="M64 464c-8.8 0-16-7.2-16-16L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24l144 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-144 0zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24l144 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-144 0z"/></svg>
                        <span>
                            <?php esc_html_e('View My Resume', 'ashdavies'); ?>
                        </span>
                    </a>
                    <a href="https://github.com/DrizzlyOwl" class="masthead__connect">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 98 96" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M48.854 0C21.839 0 0 22 0 49.217c0 21.756 13.993 40.172 33.405 46.69 2.427.49 3.316-1.059 3.316-2.362 0-1.141-.08-5.052-.08-9.127-13.59 2.934-16.42-5.867-16.42-5.867-2.184-5.704-5.42-7.17-5.42-7.17-4.448-3.015.324-3.015.324-3.015 4.934.326 7.523 5.052 7.523 5.052 4.367 7.496 11.404 5.378 14.235 4.074.404-3.178 1.699-5.378 3.074-6.6-10.839-1.141-22.243-5.378-22.243-24.283 0-5.378 1.94-9.778 5.014-13.2-.485-1.222-2.184-6.275.486-13.038 0 0 4.125-1.304 13.426 5.052a46.97 46.97 0 0 1 12.214-1.63c4.125 0 8.33.571 12.213 1.63 9.302-6.356 13.427-5.052 13.427-5.052 2.67 6.763.97 11.816.485 13.038 3.155 3.422 5.015 7.822 5.015 13.2 0 18.905-11.404 23.06-22.324 24.283 1.78 1.548 3.316 4.481 3.316 9.126 0 6.6-.08 11.897-.08 13.526 0 1.304.89 2.853 3.316 2.364 19.412-6.52 33.405-24.935 33.405-46.691C97.707 22 75.788 0 48.854 0z"/></svg>
                        <span>
                            <?php esc_html_e('See My Work', 'ashdavies'); ?>
                        </span>
                    </a>
                    <a href="https://steamcommunity.com/id/drizzlyowl" class="masthead__connect masthead__connect--steam">
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 496 512" fill="currentColor"><path d="M496 256c0 137-111.2 248-248.4 248-113.8 0-209.6-76.3-239-180.4l95.2 39.3c6.4 32.1 34.9 56.4 68.9 56.4 39.2 0 71.9-32.4 70.2-73.5l84.5-60.2c52.1 1.3 95.8-40.9 95.8-93.5 0-51.6-42-93.5-93.7-93.5s-93.7 42-93.7 93.5v1.2L176.6 279c-15.5-.9-30.7 3.4-43.5 12.1L0 236.1C10.2 108.4 117.1 8 247.6 8 384.8 8 496 119 496 256zM155.7 384.3l-30.5-12.6a52.8 52.8 0 0 0 27.2 25.8c26.9 11.2 57.8-1.6 69-28.4 5.4-13 5.5-27.3 .1-40.3-5.4-13-15.5-23.2-28.5-28.6-12.9-5.4-26.7-5.2-38.9-.6l31.5 13c19.8 8.2 29.2 30.9 20.9 50.7-8.3 19.9-31 29.2-50.8 21zm173.8-129.9c-34.4 0-62.4-28-62.4-62.3s28-62.3 62.4-62.3 62.4 28 62.4 62.3-27.9 62.3-62.4 62.3zm.1-15.6c25.9 0 46.9-21 46.9-46.8 0-25.9-21-46.8-46.9-46.8s-46.9 21-46.9 46.8c.1 25.8 21.1 46.8 46.9 46.8z"/></svg>
                        <span>
                            <?php esc_html_e("See What I'm Playing", 'ashdavies'); ?>
                        </span>
                    </a>
                </div>
            </div>
        </header>
        <div class="masthead__shim masthead__shim--circle masthead__shim--top-left" aria-hidden="true"></div>
        <div class="masthead__shim masthead__shim--circle masthead__shim--bottom-right" aria-hidden="true"></div>
    </div>
    <section class="skillbox" aria-labelledby="coreskills">
        <div class="wrapper">
            <h2 id="coreskills" class="skillbox__title"><?php esc_html_e('My core skills', 'ashdavies'); ?></h2>
            <div class="skillbox__wrap">
                <div class="skill">
                    <div class="skill__icon skill__icon--azure">
                        <img src="<?php echo esc_url(get_theme_dir() . '/dist/azure.svg'); ?>" alt="" width="85" height="85" decoding="async">
                    </div>
                    <h3 class="skill__heading"><?php esc_html_e('Azure', 'ashdavies'); ?></h3>
                    <p class="skill__text"><?php esc_html_e('Cloud Architecture', 'ashdavies'); ?></p>
                </div>
                <div class="skill">
                    <div class="skill__icon skill__icon--sys">
                        <img src="<?php echo esc_url(get_theme_dir() . '/dist/ubuntu.svg'); ?>" alt="" width="200" height="99" decoding="async">
                    </div>
                    <h3 class="skill__heading"><?php esc_html_e('Ubuntu', 'ashdavies'); ?></h3>
                    <p class="skill__text"><?php esc_html_e('Linux & Automation', 'ashdavies'); ?></p>
                </div>
                <div class="skill">
                    <div class="skill__icon skill__icon--terraform">
                        <img src="<?php echo esc_url(get_theme_dir() . '/dist/terraform.svg'); ?>" alt="" width="200" height="56" decoding="async">
                    </div>
                    <h3 class="skill__heading"><?php esc_html_e('Terraform', 'ashdavies'); ?></h3>
                    <p class="skill__text"><?php esc_html_e('Infrastructure as Code', 'ashdavies'); ?></p>
                </div>
                <div class="skill">
                    <div class="skill__icon skill__icon--docker">
                        <img src="<?php echo esc_url(get_theme_dir() . '/dist/docker.svg'); ?>" alt="" width="100" height="100" decoding="async">
                    </div>
                    <h3 class="skill__heading"><?php esc_html_e('Docker', 'ashdavies'); ?></h3>
                    <p class="skill__text"><?php esc_html_e('Containers & CI/CD', 'ashdavies'); ?></p>
                </div>
            </div>
        </div>
    </section>
    <article <?php post_class('contentarea'); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content">
                    <?php the_content(); ?>
                </div>
                <aside class="content-sidebar-group__sidebar">
                    <div class="sidebar__item avatar avatar--centered avatar-card">
                        <div class="avatar__wrap">
                            <picture>
                                <source type="image/webp" srcset="<?php echo esc_url(get_theme_dir() . '/dist/drizzlyowl.webp'); ?>">
                                <img class="avatar__image" src="<?php echo esc_url(get_theme_dir() . '/dist/drizzlyowl.jpeg'); ?>" alt="<?php esc_attr_e('An illustration of a wet owl', 'ashdavies'); ?>" width="200" height="200" loading="lazy" decoding="async">
                            </picture>
                        </div>
                        <p class="avatar-card__handle"><strong><?php esc_html_e('DrizzlyOwl', 'ashdavies'); ?></strong></p>
                        <p class="avatar-card__caption"><?php esc_html_e('My online handle — illustrated here as a very wet owl.', 'ashdavies'); ?></p>
                    </div>
                </aside>
            </div>
        </div>
    </article>
    <?php if ($latest_posts->have_posts()) : ?>
        <section class="latest-posts" aria-labelledby="latest-posts-heading">
            <div class="wrapper">
                <div class="latest-posts__header">
                    <h2 id="latest-posts-heading" class="latest-posts__title"><?php esc_html_e('Latest Posts', 'ashdavies'); ?></h2>
                    <?php if ($blog_url) : ?>
                        <a class="latest-posts__all" href="<?php echo esc_url($blog_url); ?>">
                            <?php esc_html_e('View all posts', 'ashdavies'); ?> &rarr;
                        </a>
                    <?php endif; ?>
                </div>
                <ul class="cards">
                    <?php while ($latest_posts->have_posts()) : ?>
                        <?php $latest_posts->the_post(); ?>
                        <?php get_template_part('template-parts/post-card'); ?>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</main>
<?php
get_footer();
