<?php

namespace Ash;

$pid = get_the_ID();
$anchors = get_heading_anchors($pid);
$reading_time = get_post_meta($pid, '_yoast_wpseo_estimated-reading-time-minutes', true);
?>

<aside class="content-sidebar-group__sidebar">
    <div class="sidebar__item">
        <h2 class="sidebar__title"><?php _e("Info", "ashdavies"); ?></h2>
        <p>
            <strong><?php _e("Approximate reading time:", "ashdavies"); ?></strong><br>
            <?php echo $reading_time; ?> <?php echo _n('minute', 'minutes', $reading_time, 'ashdavies'); ?>
        </p>
        <p>
            <strong><?php _e("Published:", "ashdavies"); ?></strong><br>
            <?php the_date(); ?>
        </p>
        <?php
        $published_time = get_the_time('Ymd');
        $last_updated = get_the_modified_time('Ymd');

        if ($last_updated != $published_time) : ?>
            <p>
                <strong><?php _e("Updated:", "ashdavies"); ?></strong><br>
                <?php the_modified_date(); ?>
            </p>
        <?php endif; ?>
        <?php if (wp_get_post_categories(get_the_ID())) : ?>
            <p>
                <strong><?php _e("Category:", 'ashdavies'); ?></strong><br>
                <?php the_category(','); ?>
            </p>
        <?php endif; ?>
        <?php if (get_comments('status=approve')) : ?>
            <p>
                <strong><?php _e("Comments:", 'ashdavies'); ?></strong><br>
                <?php comments_number(); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php if (!empty($anchors)) : ?>
        <nav class="sidebar__item page-anchors">
            <h2 class="sidebar__title"><?php _e("Jump to", 'ashdavies'); ?></h2>
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
        </nav>
    <?php endif; ?>
</aside>
