<?php

namespace Ash;

/**
 * Post card partial used across blog, archive, search, and front page grids
 */

$pid = (int) get_the_ID();
$post_date = get_post_datetime();
$reading_time = get_reading_time($pid);
$categories = get_the_category($pid);
$primary_category = !empty($categories) ? $categories[0] : null;
?>
<li <?php post_class('card__item'); ?>>
    <article class="card__inner">
        <a class="card__media-link" href="<?php echo esc_url(get_permalink()); ?>" tabindex="-1" aria-hidden="true">
            <figure class="card__media">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large', [
                        'class'    => 'card__image',
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                    ]); ?>
                <?php else : ?>
                    <img
                        class="card__image"
                        src="<?php echo esc_url(get_theme_dir() . '/dist/cover-default.svg'); ?>"
                        alt=""
                        width="640"
                        height="360"
                        loading="lazy"
                        decoding="async"
                    >
                <?php endif; ?>
            </figure>
        </a>
        <div class="card__body">
            <div class="card__meta">
                <?php if ($post_date) : ?>
                    <time datetime="<?php echo esc_attr($post_date->format('c')); ?>">
                        <?php echo esc_html($post_date->format(get_option('date_format'))); ?>
                    </time>
                <?php endif; ?>
                <?php if ($reading_time > 0) : ?>
                    <span class="card__meta-sep" aria-hidden="true">&middot;</span>
                    <span>
                        <?php
                        printf(
                            /* translators: %d: reading time in minutes */
                            esc_html(_n('%d min read', '%d min read', $reading_time, 'ashdavies')),
                            $reading_time
                        );
                        ?>
                    </span>
                <?php endif; ?>
                <?php if ($primary_category) : ?>
                    <span class="card__badge"><?php echo esc_html($primary_category->name); ?></span>
                <?php endif; ?>
            </div>
            <h2 class="card__title">
                <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="card__readmore"><?php the_excerpt(); ?></div>
        </div>
    </article>
</li>
