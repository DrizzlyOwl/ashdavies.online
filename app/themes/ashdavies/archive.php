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
                <li <?php post_class('card__item'); ?>>
                    <div class="card__inner">
                        <?php
                        $post_date = get_post_datetime();
                        if ($post_date) : ?>
                            <p class="date card__meta"><?php echo esc_html($post_date->format(get_option('date_format'))); ?></p>
                        <?php endif; ?>
                        <h2 class="card__title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
                        <div class="card__readmore"><?php the_excerpt(); ?></div>
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
