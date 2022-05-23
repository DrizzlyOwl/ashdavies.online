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
            <p class="masthead__back"><?php yoast_breadcrumb("&#8647; "); ?></p>
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <div class="masthead__meta">
                <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                <?php if (get_comments('status=approve')) : ?>
                    <p class="comment-count"><?php comments_number(); ?></p>
                <?php endif; ?>
            </div>
            <?php if (wp_get_post_categories(get_the_ID())) : ?>
                <p class="masthead__tags">Category: <?php the_category(','); ?></p>
            <?php endif; ?>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
        </header>
    </div>
    <article <?php post_class("contentarea"); ?>>
        <div class="wrapper">
            <div class="content-sidebar-group">
                <div class="content-sidebar-group__content"><?php the_content(); ?></div>
                <?php $anchors = get_heading_anchors(get_the_ID()); ?>
                <?php if (!empty($anchors)) : ?>
                <aside class="content-sidebar-group__sidebar">
                    <div class="page-anchors">
                        <p class="page-anchors__title">Jump to</p>
                        <ul class="page-anchors__list">
                            <?php foreach ($anchors as $anchor) : ?>
                                <li class="page-anchors__item">
                                    <?php
                                    echo wp_sprintf(
                                        "<a class='page-anchors__link' href='#%s'>%s</a>",
                                        $anchor["id"],
                                        $anchor["title"]
                                    );
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <footer>
        <?php comment_form(); ?>
        <?php wp_list_comments(); ?>
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
