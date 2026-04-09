<?php
defined('ABSPATH') || exit;

$post_id   = $args['post_id']   ?? get_the_ID();
$title     = $args['title']     ?? get_the_title();
$permalink = $args['permalink'] ?? get_permalink();
$excerpt   = $args['excerpt']   ?? get_the_excerpt();
$thumbnail = $args['thumbnail'] ?? get_the_post_thumbnail_url($post_id, 'card-thumb');
$categories= $args['categories'] ?? [];
?>
<article class="card" id="post-<?php echo esc_attr($post_id); ?>">
    <?php if ($thumbnail) : ?>
        <a href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
            <img class="card__image"
                 src="<?php echo esc_url($thumbnail); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy">
        </a>
    <?php endif; ?>

    <div class="card__body">
        <?php if (!empty($categories)) : ?>
            <div class="card__cats">
                <?php foreach ($categories as $cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="card__cat-tag">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3 class="card__title">
            <a href="<?php echo esc_url($permalink); ?>" class="card__link">
                <?php echo esc_html($title); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="card__excerpt"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_url($permalink); ?>" class="btn btn--outline btn--sm" aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'golden-globe'), $title)); ?>">
            <?php esc_html_e('Read More', 'golden-globe'); ?>
        </a>
    </div>
</article>
