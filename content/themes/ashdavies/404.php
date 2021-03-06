<?php

namespace Ash;

/**
 * 404 template
 */

get_header();
?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><?php yoast_breadcrumb("&#8647; "); ?></p>
            <h1 class="masthead__heading"><?php _e("You've stumbled upon a broken link", 'ashdavies'); ?></h1>
        </header>
    </div>
    <article <?php post_class("contentarea"); ?>>
        <div class="wrapper">
            <h2><?php _e("Page not found", 'ashdavies'); ?></h2>
            <p><?php _e("The page you're looking for doesn't exist anymore.", 'ashdavies'); ?></p>
            <p><?php _e("I might have moved it or worse, if it wasn't very accurate, I might have decided to bin it.", 'ashdavies'); ?></p>
            <p><?php _e("You might find something similar in the following links:", 'ashdavies'); ?></p>
            <nav>
                <h3><?php _e("Categories", 'ashdavies'); ?></h3>
                <ul><?php wp_list_categories(['title_li' => '']); ?></ul>
                <h3><?php _e("Pages", 'ashdavies'); ?></h3>
                <ul><?php wp_list_pages(['title_li' => '']); ?></ul>
            </nav>
        </div>
    </article>
</main>
<?php
get_footer();
