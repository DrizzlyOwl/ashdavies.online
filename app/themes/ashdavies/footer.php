<?php

namespace Ash;

$footer_nav = get_footer_nav_data();
$categories = $footer_nav['categories'];
$tags = $footer_nav['tags'];
$recent_posts = $footer_nav['posts'];
$pages = $footer_nav['pages'];
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
                    <?php if (!empty($categories)) : ?>
                        <ul class="footer__list">
                            <?php foreach ($categories as $term) : ?>
                                <li class="footer__list-item">
                                    <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $term['url'])); ?>">
                                        <?php echo esc_html($term['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </nav>
                <nav aria-labelledby="footer-tags-heading">
                    <h2 id="footer-tags-heading" class="footer__heading"><?php esc_html_e('Popular Tags', 'ashdavies'); ?></h2>
                    <?php if (!empty($tags)) : ?>
                        <ul class="footer__list">
                            <?php foreach ($tags as $term) : ?>
                                <li class="footer__list-item">
                                    <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $term['url'])); ?>">
                                        <?php echo esc_html($term['name']); ?>
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
                            <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $recent_post['url'])); ?>">
                                <?php echo esc_html($recent_post['title']); ?>
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
                            <a class="footer__list-link" href="<?php echo esc_url(add_query_arg('ref', '_footer', $page_item['url'])); ?>">
                                <?php echo esc_html($page_item['title']); ?>
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
