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
    <div class="masthead">
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
                    </a>
                    <a href="https://steamcommunity.com/id/drizzlyowl" class="masthead__connect masthead__connect--steam">
                        <img src="<?php echo esc_url(get_theme_dir() . '/dist/steam.svg'); ?>" alt="" width="20" height="20">
                        <span>
                            <?php esc_html_e("See What I'm Playing", 'ashdavies'); ?>
                        </span>
                    </a>
                </div>
            </div>
        </header>
        <div class="masthead__shim masthead__shim--circle masthead__shim--top-left"></div>
        <div class="masthead__shim masthead__shim--circle masthead__shim--bottom-right"></div>
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
