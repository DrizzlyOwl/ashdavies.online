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
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <section class="wrapper">
        <article class="contentarea"><?php the_content(); ?></article>
    </section>
</main>
<?php
get_footer();
