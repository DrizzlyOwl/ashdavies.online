<?php

namespace Ash;

/**
 * Index template
 */

get_header();
?>
<div class="wrapper" id="wrapper">
    <aside class="sidebar">
        <img class="sidebar__avatar" src="<?php theme_dir(); ?>/dist/profile.jpg" alt="Picture of Ash Davies">
    </aside>
    <main class="contentarea" id="contentarea">
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
    </main>
</div>
<?php
get_footer();
