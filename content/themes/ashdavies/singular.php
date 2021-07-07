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
            <div class="masthead__meta">
                <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                <p class="comment-count"><?php comments_number(); ?></p>
            </div>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <section class="wrapper">
        <article <?php post_class("contentarea"); ?>>
            <?php the_content(); ?>
            <footer>
                <?php comment_form(); ?>
                <?php wp_list_comments(); ?>
            </footer>
        </article>
    </section>
    <div class="crumbs">
        <div class="crumbs__inner">
            <p class="screen-reader-text">Breadcrumbs</p>
            <?php yoast_breadcrumb("&#8647; "); ?>
            <div class="post-navigation">
                <p><?php previous_post_link("&larr; Previous post<br>%link", "%title", true); ?></p>
                <p><?php next_post_link("Next post &rarr;<br>%link", "%title", true); ?></p>
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
