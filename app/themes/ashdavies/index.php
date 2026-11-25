<?php

namespace Ash;

/**
 * Index / Blog posts template
 */

get_header();

$blog_page_id = (int) get_option('page_for_posts');
$heading = $blog_page_id ? get_the_title($blog_page_id) : __('Blog', 'ashdavies');
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php echo esc_html($heading); ?></h1>
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
