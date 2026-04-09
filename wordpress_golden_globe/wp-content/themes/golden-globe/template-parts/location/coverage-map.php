<?php
/**
 * Location — Coverage Map / Nearby Locations
 *
 * @package GoldenGlobe
 * @var array $args  city, state, nearby_locations (array of WP_Post), map_embed_url
 */
defined('ABSPATH') || exit;

$city             = $args['city']             ?? '';
$state            = $args['state']            ?? '';
$nearby_locations = $args['nearby_locations'] ?? [];
$map_embed_url    = $args['map_embed_url']    ?? '';

if (empty($nearby_locations) && !$map_embed_url) return;
?>
<section class="loc-coverage" aria-labelledby="loc-coverage-heading">
    <div class="container">

        <header class="loc-coverage__header">
            <span class="loc-coverage__eyebrow"><?php esc_html_e('Coverage', 'golden-globe'); ?></span>
            <h2 id="loc-coverage-heading" class="loc-coverage__title">
                <?php
                $base = ($city && $state) ? "{$city}, {$state}" : get_the_title();
                printf(
                    /* translators: %s: "City, State" */
                    esc_html__('Serving %s &amp; Surrounding Areas', 'golden-globe'),
                    esc_html($base)
                );
                ?>
            </h2>
        </header>

        <div class="loc-coverage__layout">

            <?php if (!empty($nearby_locations)) : ?>
                <nav class="loc-coverage__links" aria-label="<?php esc_attr_e('Nearby service areas', 'golden-globe'); ?>">
                    <p class="loc-coverage__current-pin">
                        <span aria-hidden="true">📍</span>
                        <?php echo esc_html($city ?: get_the_title()); ?>
                    </p>
                    <ul class="loc-coverage__list">
                        <?php foreach ($nearby_locations as $loc) :
                            $loc_city  = agency_get_field('city_name', $loc->ID) ?: get_the_title($loc->ID);
                            $loc_state = agency_get_field('state_abbr', $loc->ID) ?: '';
                            $loc_label = $loc_state ? "{$loc_city}, {$loc_state}" : $loc_city;
                            ?>
                            <li class="loc-coverage__item">
                                <a href="<?php echo esc_url(get_permalink($loc->ID)); ?>"
                                   class="loc-coverage__chip">
                                    <?php echo esc_html($loc_label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <?php if ($map_embed_url) : ?>
                <div class="loc-coverage__map">
                    <iframe title="<?php printf(esc_attr__('Map of %s service area', 'golden-globe'), esc_attr($city)); ?>"
                            src="<?php echo esc_url($map_embed_url); ?>"
                            width="600" height="400"
                            style="border:0;" allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            <?php endif; ?>

        </div><!-- .loc-coverage__layout -->

    </div><!-- .container -->
</section>
