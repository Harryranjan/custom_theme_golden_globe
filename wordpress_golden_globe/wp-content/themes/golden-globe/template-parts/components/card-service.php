<?php
defined('ABSPATH') || exit;

$post_id    = $args['post_id']    ?? get_the_ID();
$title      = $args['title']      ?? get_the_title();
$permalink  = $args['permalink']  ?? get_permalink();
$excerpt    = $args['excerpt']    ?? get_the_excerpt();
$thumbnail  = $args['thumbnail']  ?? get_the_post_thumbnail_url($post_id, 'card-thumb');
$types      = $args['types']      ?? [];
$tagline    = $args['tagline']    ?? '';
$icon       = $args['icon']       ?? null; // ACF image array or URL string

// Resolve icon URL from either format
$icon_url = '';
if (is_array($icon) && !empty($icon['url'])) {
    $icon_url = $icon['url'];
} elseif (is_string($icon) && !empty($icon)) {
    $icon_url = $icon;
}

// Fallback excerpt to tagline
$card_desc = $tagline ?: wp_trim_words($excerpt, 18);
?>
<article class="service-card" id="service-<?php echo esc_attr($post_id); ?>">

    <?php if ($thumbnail) : ?>
        <a href="<?php echo esc_url($permalink); ?>" class="service-card__img-link" tabindex="-1" aria-hidden="true">
            <img class="service-card__img"
                 src="<?php echo esc_url($thumbnail); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy">
            <div class="service-card__img-overlay" aria-hidden="true"></div>
        </a>
    <?php endif; ?>

    <div class="service-card__body">

        <!-- Icon + type tags row -->
        <div class="service-card__meta">
            <?php if ($icon_url) : ?>
                <div class="service-card__icon" aria-hidden="true">
                    <img src="<?php echo esc_url($icon_url); ?>"
                         alt=""
                         width="32" height="32">
                </div>
            <?php else : ?>
                <!-- SVG placeholder icon -->
                <div class="service-card__icon service-card__icon--placeholder" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.282c-.09.542-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            <?php endif; ?>

            <?php if (!empty($types) && !is_wp_error($types)) : ?>
                <div class="service-card__types">
                    <?php foreach (array_slice($types, 0, 2) as $type) : ?>
                        <span class="service-card__type-tag"><?php echo esc_html($type->name); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <h3 class="service-card__title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h3>

        <?php if ($card_desc) : ?>
            <p class="service-card__desc"><?php echo esc_html($card_desc); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_url($permalink); ?>" class="service-card__cta">
            <?php esc_html_e('Learn More', 'golden-globe'); ?>
            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M2 8a.5.5 0 01.5-.5h9.793L9.146 4.354a.5.5 0 11.708-.708l4 4a.5.5 0 010 .708l-4 4a.5.5 0 01-.708-.708L12.293 8.5H2.5A.5.5 0 012 8z"
                      clip-rule="evenodd" />
            </svg>
        </a>

    </div>
</article>
