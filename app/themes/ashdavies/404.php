<?php

namespace Ash;

/**
 * 404 template
 */

get_header();
?>
<main id="contentarea" tabindex="-1">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back">
                <?php if (function_exists('yoast_breadcrumb')) : ?>
                    <?php yoast_breadcrumb('&#8647; '); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">&larr; <?php esc_html_e('Back to Homepage', 'ashdavies'); ?></a>
                <?php endif; ?>
            </p>
            <h1 class="masthead__heading"><?php esc_html_e("You've stumbled upon a broken link", 'ashdavies'); ?></h1>
        </header>
    </div>
    <article class="contentarea">
        <div class="wrapper">
            <h2><?php esc_html_e('Page not found', 'ashdavies'); ?></h2>
            <p><?php esc_html_e("The page you're looking for doesn't exist anymore.", 'ashdavies'); ?></p>
            <p><?php esc_html_e("I might have moved it or worse, if it wasn't very accurate, I might have decided to bin it.", 'ashdavies'); ?></p>
            <p><?php esc_html_e('You might find something similar in the following links:', 'ashdavies'); ?></p>
            <nav aria-label="<?php esc_attr_e('Site directory', 'ashdavies'); ?>">
                <h3><?php esc_html_e('Categories', 'ashdavies'); ?></h3>
                <ul><?php wp_list_categories(['title_li' => '']); ?></ul>
                <h3><?php esc_html_e('Pages', 'ashdavies'); ?></h3>
                <ul><?php wp_list_pages(['title_li' => '']); ?></ul>
            </nav>
        </div>
    </article>
</main>
<?php
get_footer();
