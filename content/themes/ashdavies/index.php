<?php

/**
 * Index template
 */

get_header();
?>
<main class="wrapper">
    <div class="sidebar">
        <img src="https://ashdavies.local/content/uploads/2020/11/3853061.jpeg" alt="">
        <ul style="list-style-type: none; margin: 2rem 0 2rem 0; padding: 0; font-size: 19px">
            <li style="text-align: center"><a href="https://github.com/DrizzlyOwl">Github</a></li>
            <li style="text-align: center"><a href="https://twitter.com/DrizzlyOwl">Twitter</a></li>
            <li style="text-align: center"><a href="https://keybase.io/DrizzlyOwl">Keybase</a></li>
        </ul>
    </div>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <article>
                <header>
                    <h1><?php the_title(); ?></h1>
                    <?php the_excerpt(); ?>
                </header>
                <?php the_content(); ?>
                <footer>&copy; Ash Davies</footer>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
<?php
get_footer();
