<?php

namespace Ash;

/**
 * Index template
 */

get_header();
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php esc_html_e('Archive', 'ashdavies'); ?></h1>
        </header>
    </div>
    <section class="wrapper">
        <?php if (have_posts()) : ?>
            <ul class="unset-list">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <li>
                    <div <?php post_class('contentarea'); ?>>
                        <header>
                            <h2><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
                            <?php
                            $post_date = get_post_datetime();
                            if ($post_date) : ?>
                                <p class="date"><?php echo esc_html($post_date->format(get_option('date_format'))); ?></p>
                            <?php endif; ?>
                            <?php the_excerpt(); ?>
                        </header>
                    </div>
                </li>
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
