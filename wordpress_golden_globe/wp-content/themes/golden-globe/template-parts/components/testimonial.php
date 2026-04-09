<?php
defined('ABSPATH') || exit;

$author  = $args['author']  ?? get_the_title();
$content = $args['content'] ?? get_the_content();
$role    = $args['role']    ?? '';
$avatar  = $args['avatar']  ?? get_the_post_thumbnail_url(get_the_ID(), 'team-square');
?>
<div class="testimonial">
    <blockquote class="testimonial__quote">
        <?php echo wp_kses_post(wpautop($content)); ?>
    </blockquote>
    <footer class="testimonial__author">
        <?php if ($avatar) : ?>
            <img class="testimonial__avatar"
                 src="<?php echo esc_url($avatar); ?>"
                 alt="<?php echo esc_attr($author); ?>"
                 width="48" height="48" loading="lazy">
        <?php endif; ?>
        <div>
            <strong class="testimonial__name"><?php echo esc_html($author); ?></strong>
            <?php if ($role) : ?>
                <span class="testimonial__role"><?php echo esc_html($role); ?></span>
            <?php endif; ?>
        </div>
    </footer>
</div>
