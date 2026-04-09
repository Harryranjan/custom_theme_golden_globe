<?php
/**
 * Archive Location — "Service Areas" hub page
 *
 * Lists all city-level locations in a card grid with region grouping.
 *
 * @package GoldenGlobe
 */
defined('ABSPATH') || exit;

get_header();

// Group locations by location_region taxonomy
$regions     = get_terms(['taxonomy' => 'location_region', 'hide_empty' => true]);
$has_regions = !is_wp_error($regions) && !empty($regions);

// If no regions, fall back to a flat loop
$flat_query = null;
if (!$has_regions) {
    $flat_query = new WP_Query([
        'post_type'      => 'location',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => 'location_type',
                'value'   => 'city',
                'compare' => '=',
            ],
        ],
    ]);
}
?>
<main id="main" class="site-main" role="main">

    <!-- ══ BANNER ═══════════════════════════════════════════════════════════ -->
    <section class="loc-archive-banner" aria-labelledby="loc-archive-heading">
        <div class="loc-archive-banner__overlay" aria-hidden="true"></div>
        <div class="container">
            <h1 id="loc-archive-heading" class="loc-archive-banner__title">
                <?php esc_html_e('Service Areas', 'golden-globe'); ?>
            </h1>
            <p class="loc-archive-banner__sub">
                <?php esc_html_e('Browse all locations we serve. Click your city or neighbourhood to see local service details, pricing, and how we work in your area.', 'golden-globe'); ?>
            </p>
        </div>
        <div class="loc-archive-banner__wave" aria-hidden="true">
            <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,32 C360,56 1080,0 1440,32 L1440,56 L0,56 Z" fill="currentColor"/>
            </svg>
        </div>
    </section>

    <!-- ══ LOCATION GRID ════════════════════════════════════════════════════ -->
    <section class="loc-archive-section">
        <div class="container">

            <?php if ($has_regions) :
                foreach ($regions as $region) :
                    $region_locs = new WP_Query([
                        'post_type'      => 'location',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                        'tax_query'      => [
                            [
                                'taxonomy' => 'location_region',
                                'field'    => 'term_id',
                                'terms'    => $region->term_id,
                            ],
                        ],
                        'meta_query' => [
                            [
                                'key'     => 'location_type',
                                'value'   => 'city',
                                'compare' => '=',
                            ],
                        ],
                    ]);

                    if (!$region_locs->have_posts()) continue;
                    ?>
                    <div class="loc-archive-region">
                        <h2 class="loc-archive-region__title"><?php echo esc_html($region->name); ?></h2>
                        <div class="loc-archive-grid">
                            <?php while ($region_locs->have_posts()) : $region_locs->the_post();
                                $loc_id    = get_the_ID();
                                $loc_city  = function_exists('get_field') ? (agency_get_field('city_name',     $loc_id) ?: get_the_title()) : get_the_title();
                                $loc_state = function_exists('get_field') ? (agency_get_field('state_abbr',    $loc_id) ?: '') : '';
                                $loc_phone = function_exists('get_field') ? (agency_get_field('location_phone',$loc_id) ?: '') : '';
                                $loc_score = function_exists('get_field') ? (agency_get_field('review_score',  $loc_id) ?: '') : '';
                                $loc_count = function_exists('get_field') ? (int) agency_get_field('review_count', $loc_id) : 0;
                                $loc_thumb = get_the_post_thumbnail_url($loc_id, 'medium');
                                $loc_url   = get_permalink($loc_id);

                                // Child locations (neighbourhoods)
                                $children = get_posts([
                                    'post_type'      => 'location',
                                    'post_parent'    => $loc_id,
                                    'posts_per_page' => 6,
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                ]);
                                ?>
                                <article class="loc-card">
                                    <?php if ($loc_thumb) : ?>
                                        <a href="<?php echo esc_url($loc_url); ?>" class="loc-card__img-link" tabindex="-1" aria-hidden="true">
                                            <img src="<?php echo esc_url($loc_thumb); ?>"
                                                 alt="<?php printf(esc_attr__('Services in %s', 'golden-globe'), esc_attr("{$loc_city}, {$loc_state}")); ?>"
                                                 class="loc-card__img"
                                                 loading="lazy" decoding="async">
                                        </a>
                                    <?php endif; ?>

                                    <div class="loc-card__body">
                                        <h3 class="loc-card__title">
                                            <a href="<?php echo esc_url($loc_url); ?>">
                                                <?php echo esc_html($loc_city); ?>
                                                <?php if ($loc_state) : ?><span class="loc-card__state">, <?php echo esc_html($loc_state); ?></span><?php endif; ?>
                                            </a>
                                        </h3>

                                        <?php if ($loc_score && $loc_count > 0) : ?>
                                            <p class="loc-card__rating">
                                                <span aria-hidden="true">★</span>
                                                <span><?php echo esc_html($loc_score); ?></span>
                                                <span class="loc-card__rating-count">(<?php echo esc_html(number_format_i18n($loc_count)); ?>)</span>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($children)) : ?>
                                            <ul class="loc-card__children" aria-label="<?php esc_attr_e('Neighbourhoods', 'golden-globe'); ?>">
                                                <?php foreach ($children as $child) : ?>
                                                    <li><a href="<?php echo esc_url(get_permalink($child->ID)); ?>"><?php echo esc_html(get_the_title($child->ID)); ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>

                                        <a href="<?php echo esc_url($loc_url); ?>" class="loc-card__cta">
                                            <?php printf(esc_html__('Services in %s', 'golden-globe'), esc_html($loc_city)); ?>
                                            <span aria-hidden="true"> →</span>
                                        </a>
                                    </div>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div><!-- .loc-archive-grid -->
                    </div><!-- .loc-archive-region -->
                <?php endforeach;

            else : // ── Flat loop, no regions ────────────────────────────────
                if ($flat_query && $flat_query->have_posts()) : ?>
                    <div class="loc-archive-grid">
                        <?php while ($flat_query->have_posts()) : $flat_query->the_post();
                            $loc_id    = get_the_ID();
                            $loc_city  = function_exists('get_field') ? (agency_get_field('city_name',     $loc_id) ?: get_the_title()) : get_the_title();
                            $loc_state = function_exists('get_field') ? (agency_get_field('state_abbr',    $loc_id) ?: '') : '';
                            $loc_score = function_exists('get_field') ? (agency_get_field('review_score',  $loc_id) ?: '') : '';
                            $loc_count = function_exists('get_field') ? (int) agency_get_field('review_count', $loc_id) : 0;
                            $loc_thumb = get_the_post_thumbnail_url($loc_id, 'medium');
                            $loc_url   = get_permalink($loc_id);
                            $children  = get_posts([
                                'post_type'      => 'location',
                                'post_parent'    => $loc_id,
                                'posts_per_page' => 6,
                                'orderby'        => 'title',
                                'order'          => 'ASC',
                            ]);
                            ?>
                            <article class="loc-card">
                                <?php if ($loc_thumb) : ?>
                                    <a href="<?php echo esc_url($loc_url); ?>" class="loc-card__img-link" tabindex="-1" aria-hidden="true">
                                        <img src="<?php echo esc_url($loc_thumb); ?>"
                                             alt="<?php printf(esc_attr__('Services in %s', 'golden-globe'), esc_attr("{$loc_city}, {$loc_state}")); ?>"
                                             class="loc-card__img"
                                             loading="lazy" decoding="async">
                                    </a>
                                <?php endif; ?>

                                <div class="loc-card__body">
                                    <h3 class="loc-card__title">
                                        <a href="<?php echo esc_url($loc_url); ?>">
                                            <?php echo esc_html($loc_city); ?>
                                            <?php if ($loc_state) : ?><span class="loc-card__state">, <?php echo esc_html($loc_state); ?></span><?php endif; ?>
                                        </a>
                                    </h3>

                                    <?php if ($loc_score && $loc_count > 0) : ?>
                                        <p class="loc-card__rating">
                                            <span aria-hidden="true">★</span>
                                            <span><?php echo esc_html($loc_score); ?></span>
                                            <span class="loc-card__rating-count">(<?php echo esc_html(number_format_i18n($loc_count)); ?>)</span>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($children)) : ?>
                                        <ul class="loc-card__children" aria-label="<?php esc_attr_e('Neighbourhoods', 'golden-globe'); ?>">
                                            <?php foreach ($children as $child) : ?>
                                                <li><a href="<?php echo esc_url(get_permalink($child->ID)); ?>"><?php echo esc_html(get_the_title($child->ID)); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_url($loc_url); ?>" class="loc-card__cta">
                                        <?php printf(esc_html__('Services in %s', 'golden-globe'), esc_html($loc_city)); ?>
                                        <span aria-hidden="true"> →</span>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div><!-- .loc-archive-grid -->
                <?php else : ?>
                    <p class="loc-archive-empty"><?php esc_html_e('No service areas found.', 'golden-globe'); ?></p>
                <?php endif;
            endif; ?>

        </div><!-- .container -->
    </section>

</main>

<?php get_footer();
