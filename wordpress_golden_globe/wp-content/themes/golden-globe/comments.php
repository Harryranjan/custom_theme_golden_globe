<?php
/**
 * Comments template
 *
 * @package GoldenGlobe
 */
defined('ABSPATH') || exit;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password, return early.
 */
if (post_password_required()) {
    echo '<p class="comments-password-notice">' . esc_html__('This content is password-protected. Please enter the password to see comments.', 'golden-globe') . '</p>';
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>

        <!-- ── Comment count heading ── -->
        <h2 class="comments-area__title">
            <?php
            $count = get_comments_number();
            if (1 === (int) $count) {
                printf(
                    /* translators: %s: post title */
                    esc_html__('One thought on &ldquo;%s&rdquo;', 'golden-globe'),
                    '<span>' . esc_html(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count, 2: post title */
                    esc_html(_n('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', (int) $count, 'golden-globe')),
                    esc_html(number_format_i18n((int) $count)),
                    '<span>' . esc_html(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <!-- ── Comment list ── -->
        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'golden_globe_comment_template',
            ]);
            ?>
        </ol>

        <!-- ── Comment pagination ── -->
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-pagination" aria-label="<?php esc_attr_e('Comment navigation', 'golden-globe'); ?>">
                <span class="comment-pagination__prev">
                    <?php previous_comments_link(esc_html__('&larr; Older Comments', 'golden-globe')); ?>
                </span>
                <span class="comment-pagination__next">
                    <?php next_comments_link(esc_html__('Newer Comments &rarr;', 'golden-globe')); ?>
                </span>
            </nav>
        <?php endif; ?>

    <?php endif; // have_comments() ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="comments-none"><?php esc_html_e('Comments are closed.', 'golden-globe'); ?></p>
    <?php endif; ?>

    <!-- ── Comment form ── -->
    <?php
    $commenter = wp_get_current_commenter();
    $req      = get_option('require_name_email');
    $html_req = $req ? ' required aria-required="true"' : '';

    comment_form([
        'title_reply'          => esc_html__('Leave a Comment', 'golden-globe'),
        'title_reply_to'       => esc_html__('Reply to %s', 'golden-globe'),
        'title_reply_before'   => '<h2 id="reply-title" class="comment-form__title">',
        'title_reply_after'    => '</h2>',
        'cancel_reply_before'  => ' <span class="comment-form__cancel">',
        'cancel_reply_after'   => '</span>',
        'cancel_reply_link'    => esc_html__('Cancel reply', 'golden-globe'),
        'label_submit'         => esc_html__('Post Comment', 'golden-globe'),
        'submit_button'        => '<button type="submit" id="%1$s" name="%2$s" class="btn btn--primary %3$s">%4$s</button>',
        'submit_field'         => '<p class="comment-form__submit">%1$s %2$s</p>',
        'class_form'           => 'comment-form',
        'class_container'      => 'comment-respond',
        'comment_field'        => '<p class="comment-form-field comment-form-comment">
            <label for="comment">' . esc_html__('Comment', 'golden-globe') . ($req ? ' <span class="required" aria-hidden="true">*</span>' : '') . '</label>
            <textarea id="comment" name="comment" cols="45" rows="7"
                      class="comment-form__textarea"
                      placeholder="' . esc_attr__('Share your thoughts…', 'golden-globe') . '"
                      required aria-required="true"></textarea>
        </p>',
        'fields' => [
            'author' => '<p class="comment-form-field comment-form-author">
                <label for="author">' . esc_html__('Name', 'golden-globe') . ($req ? ' <span class="required" aria-hidden="true">*</span>' : '') . '</label>
                <input id="author" name="author" type="text"
                       class="comment-form__input"
                       value="' . esc_attr($commenter['comment_author']) . '"
                       placeholder="' . esc_attr__('Your name', 'golden-globe') . '"
                       autocomplete="name"' . $html_req . '>
            </p>',
            'email' => '<p class="comment-form-field comment-form-email">
                <label for="email">' . esc_html__('Email', 'golden-globe') . ($req ? ' <span class="required" aria-hidden="true">*</span>' : '') . '</label>
                <input id="email" name="email" type="email"
                       class="comment-form__input"
                       value="' . esc_attr($commenter['comment_author_email']) . '"
                       placeholder="' . esc_attr__('your@email.com', 'golden-globe') . '"
                       autocomplete="email"' . $html_req . '>
            </p>',
            'url' => '<p class="comment-form-field comment-form-url">
                <label for="url">' . esc_html__('Website', 'golden-globe') . '</label>
                <input id="url" name="url" type="url"
                       class="comment-form__input"
                       value="' . esc_attr($commenter['comment_author_url']) . '"
                       placeholder="' . esc_attr__('https://yoursite.com (optional)', 'golden-globe') . '"
                       autocomplete="url">
            </p>',
            'cookies' => '<p class="comment-form-field comment-form-cookies-consent">
                <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent"
                       type="checkbox" value="yes"' . (empty($commenter['comment_author_email']) ? '' : ' checked') . '>
                <label for="wp-comment-cookies-consent">' .
                    esc_html__('Save my name, email, and website for the next time I comment.', 'golden-globe') .
                '</label>
            </p>',
        ],
        'comment_notes_before' => '<p class="comment-form__notes">' .
            esc_html__('Your email address will not be published.', 'golden-globe') .
            ($req ? ' <span class="required-note">' . esc_html__('Required fields are marked', 'golden-globe') . ' <span class="required" aria-hidden="true">*</span></span>' : '') .
        '</p>',
        'comment_notes_after' => '',
    ]);
    ?>

</div><!-- #comments -->

<?php
/**
 * Custom comment template callback.
 *
 * @param WP_Comment $comment
 * @param array      $args
 * @param int        $depth
 */
function golden_globe_comment_template(WP_Comment $comment, array $args, int $depth): void {
    $tag    = 'div' === $args['style'] ? 'div' : 'li';
    $is_by_author = ($comment->user_id > 0 && get_post_field('post_author', $comment->comment_post_ID) == $comment->user_id);
    ?>
    <<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>"
        <?php comment_class('comment-item' . ($is_by_author ? ' comment-item--author' : ''), $comment); ?>>

        <article class="comment-body">

            <!-- Avatar + meta -->
            <header class="comment-header">
                <div class="comment-header__avatar">
                    <?php echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'comment-avatar']); ?>
                </div>
                <div class="comment-header__meta">
                    <span class="comment-author">
                        <?php comment_author_link($comment); ?>
                        <?php if ($is_by_author) : ?>
                            <span class="comment-author-badge"><?php esc_html_e('Author', 'golden-globe'); ?></span>
                        <?php endif; ?>
                    </span>
                    <time class="comment-date" datetime="<?php echo esc_attr(get_comment_date('c', $comment)); ?>">
                        <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                            <?php
                            printf(
                                /* translators: 1: date, 2: time */
                                esc_html__('%1$s at %2$s', 'golden-globe'),
                                esc_html(get_comment_date('', $comment)),
                                esc_html(get_comment_time('', false, true, $comment))
                            );
                            ?>
                        </a>
                    </time>
                </div>
                <?php if ('0' === $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'golden-globe'); ?></p>
                <?php endif; ?>
            </header>

            <!-- Comment text -->
            <div class="comment-content">
                <?php comment_text($comment); ?>
            </div>

            <!-- Reply link -->
            <footer class="comment-footer">
                <?php
                comment_reply_link(array_merge($args, [
                    'add_below' => 'comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="comment-reply">',
                    'after'     => '</span>',
                    'reply_text' => esc_html__('Reply', 'golden-globe'),
                ]));
                ?>
                <?php edit_comment_link(esc_html__('Edit', 'golden-globe'), '<span class="comment-edit">', '</span>'); ?>
            </footer>

        </article>

    <?php
    // Note: Walker_Comment::end_el() automatically outputs the closing </li> or </div>,
    // so we intentionally do NOT close the tag here.
}

