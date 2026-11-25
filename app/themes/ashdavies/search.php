<?php

namespace Ash;

/**
 * Search results template
 */

get_header();
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading">
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__('Search results for: %s', 'ashdavies'),
                    '<span>' . esc_html(get_search_query()) . '</span>'
                );
                ?>
            </h1>
        </header>
    </div>
    <section class="wrapper">
        <?php if (have_posts()) : ?>
            <ul class="cards">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php get_template_part('template-parts/post-card'); ?>
                <?php endwhile; ?>
            </ul>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <div class="contentarea">
                <p><?php esc_html_e('Sorry, nothing matched your search terms. Please try again with different keywords.', 'ashdavies'); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
