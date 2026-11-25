<?php

namespace Ash;

/**
 * Page template
 */

get_header();
?>
<main id="contentarea" tabindex="-1">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
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
                <?php if (has_excerpt()) : ?>
                    <div class="masthead__blurb"><?php the_excerpt(); ?></div>
                <?php endif; ?>
            </header>
        </div>
        <article <?php post_class('contentarea'); ?>>
            <div class="wrapper">
                <?php the_content(); ?>
                <?php wp_link_pages([
                    'before' => '<nav class="page-links" aria-label="' . esc_attr__('Page sections', 'ashdavies') . '">',
                    'after'  => '</nav>',
                ]); ?>
            </div>
        </article>
        <?php if (comments_open() || get_comments_number()) : ?>
            <footer class="wrapper">
                <?php comments_template(); ?>
            </footer>
        <?php endif; ?>
    <?php endwhile; ?>
</main>
<?php
get_footer();
