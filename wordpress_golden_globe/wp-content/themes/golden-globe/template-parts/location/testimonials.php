<?php
/**
 * Location — Testimonials
 *
 * @package GoldenGlobe
 * @var array $args  city, testimonials (array of WP_Post objects)
 */
defined('ABSPATH') || exit;

$city         = $args['city']         ?? '';
$testimonials = $args['testimonials'] ?? [];

// If no array was passed, query the testimonial CPT automatically.
// Prefer testimonials that have a matching location_city meta, then fall
// back to the six most-recent published testimonials.
if (empty($testimonials)) {
    // First try: city-matched
    if ($city) {
        $testimonials = get_posts([
            'post_type'      => 'testimonial',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'no_found_rows'  => true,
            'meta_query'     => [[
                'key'     => 'testimonial_location',
                'value'   => $city,
                'compare' => 'LIKE',
            ]],
        ]);
    }
    // Second try: any published testimonials
    if (empty($testimonials)) {
        $testimonials = get_posts([
            'post_type'      => 'testimonial',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ]);
    }
}

if (empty($testimonials)) return;
?>
<section class="loc-testimonials" aria-labelledby="loc-testimonials-heading">
    <div class="container">

        <header class="loc-testimonials__header">
            <span class="loc-testimonials__eyebrow"><?php esc_html_e('Real Reviews', 'golden-globe'); ?></span>
            <h2 id="loc-testimonials-heading" class="loc-testimonials__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('What %s Clients Say', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
        </header>

        <div class="loc-testimonials__grid">
            <?php foreach ($testimonials as $testimonial) :
                $id         = $testimonial->ID;
                $quote      = agency_get_field('testimonial_quote', $id)  ?: wp_trim_words(get_the_content(null, false, $id), 40);
                $author     = agency_get_field('testimonial_author', $id) ?: get_the_title($id);
                $location   = agency_get_field('testimonial_location', $id) ?? $city;
                $rating     = (int)(agency_get_field('testimonial_rating', $id) ?? 5);
                $rating     = min(5, max(1, $rating));
                ?>
                <blockquote class="loc-testimonial-card">
                    <div class="loc-testimonial-card__stars" aria-label="<?php printf(esc_attr__('%d out of 5 stars', 'golden-globe'), $rating); ?>">
                        <?php echo esc_html(str_repeat('★', $rating) . str_repeat('☆', 5 - $rating)); ?>
                    </div>
                    <p class="loc-testimonial-card__quote">
                        &ldquo;<?php echo esc_html($quote); ?>&rdquo;
                    </p>
                    <footer class="loc-testimonial-card__footer">
                        <cite class="loc-testimonial-card__author"><?php echo esc_html($author); ?></cite>
                        <?php if ($location) : ?>
                            <span class="loc-testimonial-card__location">
                                <span aria-hidden="true">📍</span> <?php echo esc_html($location); ?>
                            </span>
                        <?php endif; ?>
                    </footer>
                </blockquote>
            <?php endforeach; ?>
        </div><!-- .loc-testimonials__grid -->

    </div><!-- .container -->
</section>
