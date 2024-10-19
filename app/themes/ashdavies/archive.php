<?php

namespace Ash;

/**
 * Archive template
 */

get_header();
?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="/">&larr; <?php _e("Back to Homepage", 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php the_archive_title() ?></h1>
            <div class="masthead__blurb"><?php the_archive_description(); ?></div>
        </header>
    </div>
    <section class="wrapper">
        <?php if (have_posts()) : ?>
            <ul class="cards">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <li <?php post_class("card__item"); ?>>
                    <div class="card__inner">
                        <p class="date card__meta"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                        <h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="card__readmore"><?php the_excerpt(); ?></div>
                    </div>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
