<?php

namespace Ash;

/**
 * Single template
 */

get_header();

$is_post_old = is_post_older_than("1 year");

?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><?php yoast_breadcrumb("&#8647; "); ?></p>
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <article <?php post_class("contentarea"); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content">
                    <?php if ($is_post_old) : ?>
                        <p class="notice notice--warning">
                            <strong><?php _e("Yikes! This post is over a year old!", "ashdavies"); ?></strong>
                            <br>
                            <?php _e("If you think it deserves a rewrite please get in touch or leave a comment.", "ashdavies"); ?>
                            <br>
                            <?php _e("— Thanks, Ash.", "ashdavies"); ?>
                        </p>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div>
    </article>
    <footer class="wrapper">
        <?php if (comments_open() || get_comments_number()) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>
    </footer>
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
