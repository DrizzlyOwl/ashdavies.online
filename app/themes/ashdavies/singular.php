<?php

namespace Ash;

/**
 * Single template
 */

get_header();

$is_post_old = is_singular('post') && is_post_older_than('1 year');

?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back">
                <?php if (function_exists('yoast_breadcrumb')) : ?>
                    <?php yoast_breadcrumb('&#8647; '); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a>
                <?php endif; ?>
            </p>
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <article <?php post_class('contentarea'); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content">
                    <?php if ($is_post_old) : ?>
                        <p class="notice notice--warning">
                            <strong><?php esc_html_e('Yikes! This post is over a year old!', 'ashdavies'); ?></strong>
                            <br>
                            <?php esc_html_e('If you think it deserves a rewrite please get in touch or leave a comment.', 'ashdavies'); ?>
                            <br>
                            <?php esc_html_e('— Thanks, Ash.', 'ashdavies'); ?>
                        </p>
                    <?php endif; ?>
                    <?php the_content(); ?>
                    <?php wp_link_pages([
                        'before' => '<nav class="page-links" aria-label="' . esc_attr__('Post pages', 'ashdavies') . '">',
                        'after'  => '</nav>',
                    ]); ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </article>
    <footer class="wrapper">
        <?php if (comments_open() || get_comments_number()) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>
    </footer>
    <div class="crumbs">
        <div class="crumbs__inner">
            <?php if (function_exists('yoast_breadcrumb')) : ?>
                <p class="screen-reader-text"><?php esc_html_e('Breadcrumbs', 'ashdavies'); ?></p>
                <?php yoast_breadcrumb('&#8647; '); ?>
            <?php endif; ?>
            <?php if (is_singular('post')) : ?>
                <div class="post-navigation">
                    <p><?php previous_post_link('&larr; ' . esc_html__('Previous post', 'ashdavies') . '<br>%link', '%title', true); ?></p>
                    <p><?php next_post_link(esc_html__('Next post', 'ashdavies') . ' &rarr;<br>%link', '%title', true); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
get_footer();
