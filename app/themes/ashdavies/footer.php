<?php

namespace Ash;

$recent_posts = wp_get_recent_posts(['post_status' => 'publish', 'posts_per_page' => 15], OBJECT);
$categories = get_terms(['taxonomy' => 'category']);
$tags = get_terms(['taxonomy' => 'post_tag', 'number' => 10]);
$pages = get_pages(['posts_per_page' => 10]);
?>
</div>
<!-- #app -->
<div class="divider"></div>
<footer class="footer">
    <div class="wrapper">
        <div class="footer__content">
            <nav class="footer__nav footer__nav--cats">
                <h2 class="footer__heading"><?php _e("Categories", 'ashdavies'); ?></h2>
                <?php if (!empty($categories)) : ?>
                    <ul class="footer__list">
                        <?php foreach ($categories as $term) : ?>
                            <li class="footer__list-item">
                                <a class="footer__list-link" href="<?php echo add_query_arg('ref', '_footer', get_category_link($term)); ?>">
                                    <?php echo $term->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <h2 class="footer__heading"><?php _e("Popular Tags", 'ashdavies'); ?></h2>
                <?php if (!empty($tags)) : ?>
                    <ul class="footer__list">
                        <?php foreach ($tags as $term) : ?>
                            <li class="footer__list-item">
                                <a class="footer__list-link" href="<?php echo add_query_arg('ref', '_footer', get_category_link($term)); ?>">
                                    <?php echo $term->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>
            <nav class="footer__nav footer__nav--posts">
                <h2 class="footer__heading"><?php _e("Posts", 'ashdavies'); ?></h2>
                <?php if (!empty($recent_posts)) : ?>
                    <ul class="footer__list">
                    <?php foreach ($recent_posts as $post) : ?>
                        <?php setup_postdata($post); ?>
                        <li class="footer__list-item">
                            <a class="footer__list-link" href="<?php echo add_query_arg('ref', '_footer', get_permalink()); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>
            <nav class="footer__nav footer__nav--pages">
                <h2 class="footer__heading"><?php _e("Pages", 'ashdavies'); ?></h2>
                <?php if (!empty($pages)) : ?>
                    <ul class="footer__list">
                    <?php foreach ($pages as $post) : ?>
                        <?php setup_postdata($post); ?>
                        <li class="footer__list-item">
                            <a class="footer__list-link" href="<?php echo add_query_arg('ref', '_footer', get_permalink()); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>
        </div>
        <p class="footer__sign">
            &copy; <?php _e("Ash Davies", 'ashdavies'); ?>
            • <a href="/privacy/"><?php _e("Privacy", 'ashdavies'); ?></a>
        </p>
    </div>
</footer>
<?php

wp_footer();
?>
</body>
</html>
