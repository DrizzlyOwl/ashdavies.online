<?php

namespace Ash;

/**
 * Index template
 */

get_header();
?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="/">&larr; <?php _e("Back to Homepage", 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php _e("Archive", 'ashdavies'); ?></h1>
        </header>
    </div>
    <section class="wrapper">
        <?php if (have_posts()) : ?>
            <ul class="unset-list">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <li>
                    <div <?php post_class("contentarea"); ?>>
                        <header>
                            <h2><?php the_title(); ?></h2>
                            <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                            <?php the_excerpt(); ?>
                            <?php the_content(__("Read more&mldr;", 'ashdavies')); ?>
                        </header>
                    </div>
                </li>
            <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
