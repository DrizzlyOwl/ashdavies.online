<?php

namespace Ash;

$recent_posts = wp_get_recent_posts(['post_status' => 'publish'], OBJECT);
?>
</div>
<div class="divider"></div>
<footer class="footer">
    <div class="wrapper">
        <?php if (!empty($recent_posts)) : ?>
            <nav class="footer__nav">
                <h2 class="footer__heading"><?php _e("My recent blog posts", 'ashdavies'); ?></h2>
                <ul class="footer__list">
                <?php foreach ($recent_posts as $post) : ?>
                    <?php setup_postdata($post); ?>
                    <li class="footer__list-item">
                        <?php echo get_post_datetime()->format(get_option('date_format')); ?> -
                        <a class="footer__list-link" href="<?php echo add_query_arg('ref', '_footer', get_permalink()); ?>">
                            <?php the_title(); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <p class="footer__sign">
            &copy; <?php _e("Ash Davies", 'ashdavies'); ?>
            - <a href="/privacy/">Privacy</a>
        </p>
    </div>
</footer>
<!-- #app -->
<?php

wp_footer();
?>
</body>
</html>
