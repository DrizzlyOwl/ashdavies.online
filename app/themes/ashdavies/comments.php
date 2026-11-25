<?php

namespace Ash;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}

$cc = get_comments_number();
?>

<div id="comments" class="comments-area default-max-width <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php esc_html_e('Comments', 'ashdavies'); ?>
        </h2><!-- .comments-title -->

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'avatar_size' => 36,
                    'style'       => 'ol',
                    'short_ping'  => true,
                )
            );
            ?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_pagination(
            array(
                'before_page_number' => esc_html__('Page', 'ashdavies') . ' ',
                'mid_size'           => 0,
                'prev_text'          => sprintf(
                    '%s <span class="nav-prev-text">%s</span>',
                   '&larr;',
                    esc_html__('Older comments', 'ashdavies')
                ),
                'next_text'          => sprintf(
                    '<span class="nav-next-text">%s</span> %s',
                    esc_html__('Newer comments', 'ashdavies'),
                    '&rarr;'
                ),
            )
        );
        ?>
    <?php endif; ?>

    <?php
    comment_form(
        array(
            'title_reply'        => esc_html__('Leave a Reply', 'ashdavies'),
            'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
            'title_reply_after'  => '</h2>',
        )
    );
    ?>
</div><!-- #comments -->
