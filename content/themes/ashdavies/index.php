<?php

/**
 * Index template
 */

get_header();
?>
<main class="wrapper">
    <div class="sidebar">
        <img src="<?php echo get_home_url(); ?>/content/uploads/2020/11/3853061.jpeg" alt="">
        <ul style="list-style-type: none; margin: 2rem 0 2rem 0; padding: 0; font-size: 19px">
            <li style="text-align: center"><a href="https://github.com/DrizzlyOwl">Github</a></li>
            <li style="text-align: center"><a href="https://twitter.com/DrizzlyOwl">Twitter</a></li>
            <li style="text-align: center"><a href="https://keybase.io/DrizzlyOwl">Keybase</a></li>
        </ul>
    </div>
    <div class="contentarea">
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
    </div>
</main>
<?php
get_footer();
