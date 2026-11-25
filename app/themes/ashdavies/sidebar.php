<?php

namespace Ash;

$pid = (int) get_the_ID();
$anchors = get_heading_anchors($pid);
$tags = wp_get_post_tags($pid);
$published_time = get_the_time('Ymd');
$last_updated = get_the_modified_time('Ymd');
$has_updated = $last_updated && $last_updated !== $published_time;
$comment_count = (int) get_comments_number($pid);
$has_tags = !is_wp_error($tags) && !empty($tags);
$has_info = $has_updated || $has_tags || $comment_count > 0;
?>
<aside class="content-sidebar-group__sidebar">
    <?php if ($has_info) : ?>
        <div class="sidebar__item">
            <h2 class="sidebar__title"><?php esc_html_e('Info', 'ashdavies'); ?></h2>
            <?php if ($has_tags) : ?>
                <div class="tag-cloud">
                    <p><strong><?php esc_html_e('Tags:', 'ashdavies'); ?></strong></p>
                    <ul class="tag-cloud__list">
                    <?php foreach ($tags as $tag) :
                        $tag_archive_url = get_tag_link($tag);
                        if (is_wp_error($tag_archive_url)) {
                            continue;
                        }
                    ?>
                        <li class="tag-cloud__list-item">
                            <a href="<?php echo esc_url(add_query_arg('ref', 'sidebar', $tag_archive_url)); ?>" class="tag-cloud-link"><?php echo esc_html($tag->name); ?></a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if ($has_updated) : ?>
                <p>
                    <strong><?php esc_html_e('Updated:', 'ashdavies'); ?></strong><br>
                    <?php echo esc_html(get_the_modified_date()); ?>
                </p>
            <?php endif; ?>
            <?php if ($comment_count > 0) : ?>
                <p>
                    <strong><?php esc_html_e('Comments:', 'ashdavies'); ?></strong><br>
                    <?php comments_number(); ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($anchors)) : ?>
        <nav class="sidebar__item page-anchors" aria-labelledby="sidebar-jump-to">
            <h2 id="sidebar-jump-to" class="sidebar__title"><?php esc_html_e('Jump to', 'ashdavies'); ?></h2>
            <ul class="page-anchors__list">
                <?php foreach ($anchors as $anchor) : ?>
                    <li class="page-anchors__item">
                        <a class="page-anchors__link" href="#<?php echo esc_attr($anchor['id']); ?>">
                            <?php echo esc_html($anchor['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</aside>
