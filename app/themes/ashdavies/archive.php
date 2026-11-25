<?php

namespace Ash;

/**
 * Archive template
 */

get_header();
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php the_archive_title(); ?></h1>
            <div class="masthead__blurb"><?php the_archive_description(); ?></div>
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
                <p><?php esc_html_e('No posts found.', 'ashdavies'); ?></p>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
