<?php

namespace Ash;

/**
 * Single template
 */

get_header();
?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="/">&larr; <?php _e("Back to Homepage", 'ashdavies'); ?></a></p>
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <section class="wrapper">
        <article <?php post_class("contentarea"); ?>>
            <?php the_content(); ?>
            <footer>
                <?php comment_form(); ?>
            </footer>
        </article>
    </section>
</main>
<?php
get_footer();
