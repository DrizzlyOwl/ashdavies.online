<?php

/**
 * Index template
 */

get_header();
?>
<div class="wrapper" id="wrapper">
    <aside class="sidebar">
        <img class="sidebar__avatar" src="<?php echo get_home_url(); ?>/content/uploads/2020/11/3853061.jpeg" alt="">
        <ul class="sidebar__list">
            <li class="sidebar__list-item"><a href="https://github.com/DrizzlyOwl">Github</a></li>
            <li class="sidebar__list-item"><a href="https://twitter.com/DrizzlyOwl">Twitter</a></li>
            <li class="sidebar__list-item"><a href="https://keybase.io/DrizzlyOwl">Keybase</a></li>
        </ul>
    </aside>
    <main class="contentarea">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <article>
                    <header>
                        <h1><?php the_title(); ?></h1>
                        <p class="date"><?php echo get_post_datetime()->format(get_option('date_format')); ?></p>
                        <?php the_excerpt(); ?>
                    </header>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
        <footer>&copy; Ash Davies</footer>
    </main>
</div>
<?php
get_footer();
