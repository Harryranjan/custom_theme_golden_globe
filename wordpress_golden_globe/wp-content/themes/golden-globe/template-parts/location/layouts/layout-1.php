<?php
/**
 * Location Layout 1 — Dark Hero / Sidebar Form / Services First
 *
 * Section order (city):        Hero → Breadcrumbs → About → Process → Services → Trust → Pricing → Neighbourhoods → Deep-dive → Testimonials → Coverage → FAQ → CTA
 * Section order (neighbourhood): Hero → Breadcrumbs → About → Services → Neighbourhoods → Trust → FAQ → CTA
 * Section order (street):      Hero → Breadcrumbs → About → Services → FAQ → CTA
 *
 * @package GoldenGlobe
 */
defined('ABSPATH') || exit;

// ── Pull all ACF fields once ──────────────────────────────────────────────────
$post_id       = get_the_ID();
$location_type = agency_get_field('location_type', $post_id) ?: 'city';
$city          = agency_get_field('city_name',     $post_id) ?: get_the_title();
$state         = agency_get_field('state_abbr',    $post_id) ?: '';
$layout        = 'layout-1';

// Shared args passed to every section part
$shared = [
    'city'          => $city,
    'state'         => $state,
    'layout'        => $layout,
    'location_type' => $location_type,
];

// Hero-specific args
$hero_args = array_merge($shared, [
    'style'        => 'dark',
    'tagline'      => agency_get_field('hero_tagline',    $post_id) ?: '',
    'hero_bg'      => agency_get_field('hero_bg_image',   $post_id) ?: [],
    'phone'        => agency_get_field('location_phone',  $post_id) ?: '',
    'review_count' => (int) agency_get_field('review_count',  $post_id),
    'review_score' => agency_get_field('review_score',    $post_id) ?: '4.9',
]);
?>
<div class="location-page location-layout-1" data-location-type="<?php echo esc_attr($location_type); ?>">

    <?php get_template_part('template-parts/location/hero', null, $hero_args); ?>

    <?php get_template_part('template-parts/location/breadcrumbs'); ?>

    <?php get_template_part('template-parts/location/about', null, array_merge($shared, [
        'intro_html' => agency_get_field('local_intro', $post_id),
    ])); ?>

    <?php if (in_array($location_type, ['city', 'neighbourhood'], true)) : ?>
        <?php get_template_part('template-parts/location/process', null, array_merge($shared, [
            'steps' => agency_get_field('process_steps', $post_id) ?: [],
        ])); ?>
    <?php endif; ?>

    <?php
    $related_services = agency_get_field('related_services', $post_id) ?: [];
    if ($related_services) :
        get_template_part('template-parts/location/services-grid', null, array_merge($shared, [
            'services' => $related_services,
        ]));
    endif;
    ?>

    <?php if (in_array($location_type, ['city', 'neighbourhood'], true)) : ?>
        <?php get_template_part('template-parts/location/trust-badges', null, $shared); ?>
    <?php endif; ?>

    <?php if ('city' === $location_type) : ?>
        <?php
        $pricing = agency_get_field('pricing_rows', $post_id) ?: [];
        if ($pricing) :
            get_template_part('template-parts/location/pricing', null, array_merge($shared, [
                'pricing_rows' => $pricing,
            ]));
        endif;
        ?>
    <?php endif; ?>

    <?php
    $neighbourhoods = agency_get_field('neighbourhoods', $post_id) ?: [];
    if ($neighbourhoods && in_array($location_type, ['city', 'neighbourhood'], true)) :
        get_template_part('template-parts/location/neighbourhoods', null, array_merge($shared, [
            'neighbourhoods' => $neighbourhoods,
            'coverage_map'   => agency_get_field('coverage_map_img', $post_id) ?: [],
        ]));
    endif;
    ?>

    <?php if ('city' === $location_type) : ?>
        <?php
        $deep_title   = agency_get_field('deep_dive_title',   $post_id) ?: '';
        $deep_content = agency_get_field('deep_dive_content', $post_id) ?: '';
        if ($deep_title || $deep_content) :
            get_template_part('template-parts/location/deep-dive', null, array_merge($shared, [
                'deep_dive_title' => $deep_title,
                'deep_dive_html'  => $deep_content,
            ]));
        endif;
        ?>

        <?php get_template_part('template-parts/location/testimonials', null, array_merge($shared, [
            'testimonials' => agency_get_field('location_testimonials', $post_id) ?: [],
        ])); ?>

        <?php
        $nearby = agency_get_field('nearby_locations', $post_id) ?: [];
        get_template_part('template-parts/location/coverage-map', null, array_merge($shared, [
            'nearby_locations' => $nearby,
            'map_embed_url'    => agency_get_field('map_embed_url', $post_id) ?: '',
        ]));
        ?>
    <?php endif; ?>

    <?php
    $faq = agency_get_field('faq', $post_id) ?: [];
    if ($faq) :
        get_template_part('template-parts/location/faq', null, array_merge($shared, [
            'faq_items' => $faq,
        ]));
    endif;
    ?>

    <?php get_template_part('template-parts/location/cta-banner', null, array_merge($shared, [
        'phone'        => agency_get_field('location_phone', $post_id) ?: '',
        'tagline'      => agency_get_field('hero_tagline',   $post_id) ?: '',
        'review_count' => (int) agency_get_field('review_count', $post_id),
        'review_score' => agency_get_field('review_score',  $post_id) ?: '4.9',
    ])); ?>

</div><!-- .location-layout-1 -->
