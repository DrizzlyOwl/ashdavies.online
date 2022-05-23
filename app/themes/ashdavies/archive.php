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
            <ul class="unset-list">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <li <?php post_class("contentarea contentarea--list-item"); ?>>
                    <h2><?php the_title(); ?></h2>
                    <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                    <?php the_excerpt(); ?>
                    <?php the_content(__("Read more&mldr;", 'ashdavies')); ?>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
