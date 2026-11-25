<?php

namespace Ash;

$recent_posts = wp_get_recent_posts([
    'post_status' => 'publish',
    'numberposts' => 15,
], OBJECT);
$categories = get_terms(['taxonomy' => 'category']);
$tags = get_terms(['taxonomy' => 'post_tag', 'number' => 10]);
$pages = get_pages(['number' => 10]);
$privacy_url = get_privacy_policy_url() ?: home_url('/privacy/');
?>
</div>
<!-- #app -->
<div class="divider"></div>
<footer class="footer">
    <div class="wrapper">
        <div class="footer__content">
            <div class="footer__nav footer__nav--cats">
                <nav aria-labelledby="footer-categories-heading">
                    <h2 id="footer-categories-heading" class="footer__heading"><?php esc_html_e('Categories', 'ashdavies'); ?></h2>
                    <?php if (!is_wp_error($categories) && !empty($categories)) : ?>
                        <ul class="footer__list">
                            <?php foreach ($categories as $term) :
                                $term_link = get_term_link($term);
                                if (is_wp_error($term_link)) {
                                    continue;
                                }
                            ?>
                                <li class="footer__list-item">
                                    <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $term_link)); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </nav>
                <nav aria-labelledby="footer-tags-heading">
                    <h2 id="footer-tags-heading" class="footer__heading"><?php esc_html_e('Popular Tags', 'ashdavies'); ?></h2>
                    <?php if (!is_wp_error($tags) && !empty($tags)) : ?>
                        <ul class="footer__list">
                            <?php foreach ($tags as $term) :
                                $term_link = get_term_link($term);
                                if (is_wp_error($term_link)) {
                                    continue;
                                }
                            ?>
                                <li class="footer__list-item">
                                    <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $term_link)); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </nav>
            </div>
            <nav class="footer__nav footer__nav--posts" aria-labelledby="footer-posts-heading">
                <h2 id="footer-posts-heading" class="footer__heading"><?php esc_html_e('Posts', 'ashdavies'); ?></h2>
                <?php if (!empty($recent_posts)) : ?>
                    <ul class="footer__list">
                    <?php foreach ($recent_posts as $recent_post) : ?>
                        <li class="footer__list-item">
                            <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', get_permalink($recent_post))); ?>">
                                <?php echo esc_html(get_the_title($recent_post)); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>
            <nav class="footer__nav footer__nav--pages" aria-labelledby="footer-pages-heading">
                <h2 id="footer-pages-heading" class="footer__heading"><?php esc_html_e('Pages', 'ashdavies'); ?></h2>
                <?php if (!empty($pages)) : ?>
                    <ul class="footer__list">
                    <?php foreach ($pages as $page_item) : ?>
                        <li class="footer__list-item">
                            <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', get_permalink($page_item))); ?>">
                                <?php echo esc_html(get_the_title($page_item)); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </nav>
        </div>
        <p class="footer__sign">
            &copy; <?php esc_html_e('Ash Davies', 'ashdavies'); ?>
            &bull; <a href="<?php echo esc_url($privacy_url); ?>"><?php esc_html_e('Privacy', 'ashdavies'); ?></a>
        </p>
    </div>
</footer>
<?php

wp_footer();
?>
</body>
</html>
