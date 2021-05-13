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
            <p class="masthead__back"><a href="/">&larr; Back to Homepage</a></p>
            <h1 class="masthead__heading">Archive</h1>
        </header>
    </div>
    <section class="wrapper">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <article class="contentarea">
                    <header>
                        <h2><?php the_title(); ?></h2>
                        <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                        <?php the_excerpt(); ?>
                        <?php the_content("Read more&mldr;"); ?>
                    </header>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
