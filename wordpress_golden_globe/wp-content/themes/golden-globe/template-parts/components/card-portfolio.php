<?php
defined('ABSPATH') || exit;

$post_id   = $args['post_id']    ?? get_the_ID();
$title     = $args['title']      ?? get_the_title();
$permalink = $args['permalink']  ?? get_permalink();
$excerpt   = $args['excerpt']    ?? get_the_excerpt();
$thumbnail = $args['thumbnail']  ?? get_the_post_thumbnail_url($post_id, 'card-thumb');
$categories = $args['categories'] ?? [];

// ACF field for categories/industries used as overlay tags
$industries = get_the_terms($post_id, 'industry') ?: [];
?>
<article class="portfolio-card" id="portfolio-<?php echo esc_attr($post_id); ?>">

    <a href="<?php echo esc_url($permalink); ?>" class="portfolio-card__image-link" tabindex="-1" aria-hidden="true">
        <?php if ($thumbnail) : ?>
            <img class="portfolio-card__image"
                 src="<?php echo esc_url($thumbnail); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy">
        <?php else : ?>
            <div class="portfolio-card__image portfolio-card__image--placeholder" aria-hidden="true"></div>
        <?php endif; ?>

        <!-- Hover overlay -->
        <div class="portfolio-card__overlay" aria-hidden="true">
            <span class="portfolio-card__overlay-icon">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
            </span>
        </div>
    </a>

    <div class="portfolio-card__body">

        <!-- Category tags -->
        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
            <div class="portfolio-card__cats">
                <?php foreach (array_slice($categories, 0, 2) as $cat) : ?>
                    <span class="portfolio-card__tag"><?php echo esc_html($cat->name); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3 class="portfolio-card__title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="portfolio-card__excerpt"><?php echo esc_html(wp_trim_words($excerpt, 18)); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_url($permalink); ?>" class="portfolio-card__cta">
            <?php esc_html_e('View Project', 'golden-globe'); ?>
            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M2 8a.5.5 0 01.5-.5h9.793L9.146 4.354a.5.5 0 11.708-.708l4 4a.5.5 0 010 .708l-4 4a.5.5 0 01-.708-.708L12.293 8.5H2.5A.5.5 0 012 8z" clip-rule="evenodd"/></svg>
        </a>

    </div>
</article>
