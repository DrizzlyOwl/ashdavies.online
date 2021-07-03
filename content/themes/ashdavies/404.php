<?php

namespace Ash;

/**
 * Index template
 */

get_header();
?>
<main id="contentarea">
    <div class="masthead masthead--slim">
        <header class="masthead__inner">
            <p class="masthead__back"><a href="/">&larr; Back to Homepage</a></p>
            <h1 class="masthead__heading">You've stumbled upon a broken link</h1>
        </header>
    </div>
    <section class="wrapper">
        <article class="contentarea">
            <h2>Page not found</h2>
            <p>The page you're looking for doesn't exist anymore.</p>
            <p>I might have moved it or worse, if it wasn't very accurate, I might have decided to bin it.</p>
            <p>You might find something similar in the following links:</p>
            <nav>
                <h3>Categories</h3>
                <ul><?php wp_list_categories(['title_li' => '']); ?></ul>
                <h3>Pages</h3>
                <ul><?php wp_list_pages(['title_li' => '']); ?></ul>
            </nav>
        </article>
    </section>
</main>
<?php
get_footer();
