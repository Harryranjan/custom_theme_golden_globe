<?php
/**
 * Location — Services Grid
 * Shows related service CPT posts with city-aware descriptions
 *
 * @package GoldenGlobe
 * @var array $args  city, state, services (array of WP_Post objects)
 */
defined('ABSPATH') || exit;

$city     = $args['city']     ?? '';
$state    = $args['state']    ?? '';
$services = $args['services'] ?? [];

if (empty($services)) return;
$location_label = ($city && $state) ? "{$city}, {$state}" : get_the_title();
?>
<section class="loc-services" aria-labelledby="loc-services-heading">
    <div class="container">

        <header class="loc-services__header">
            <span class="loc-services__eyebrow"><?php esc_html_e('What We Offer', 'golden-globe'); ?></span>
            <h2 id="loc-services-heading" class="loc-services__title">
                <?php
                printf(
                    /* translators: %s: "City, State" */
                    esc_html__('Every Service We Handle Across %s', 'golden-globe'),
                    esc_html($location_label)
                );
                ?>
            </h2>
        </header>

        <div class="loc-services__grid">
            <?php foreach ($services as $service) :
                $service_id    = $service->ID;
                $service_title = get_the_title($service_id);
                $service_url   = get_permalink($service_id);
                $service_icon  = agency_get_field('service_icon', $service_id);
                $service_desc  = get_the_excerpt($service_id) ?: wp_trim_words(get_the_content(null, false, $service_id), 25);

                // Image alt uses city name for local SEO
                $thumb_url = get_the_post_thumbnail_url($service_id, 'medium');
                $thumb_alt = esc_attr("{$service_title} in {$location_label}");
                ?>
                <article class="loc-service-card">

                    <?php if ($thumb_url) : ?>
                        <div class="loc-service-card__img-wrap">
                            <img src="<?php echo esc_url($thumb_url); ?>"
                                 alt="<?php echo $thumb_alt; ?>"
                                 class="loc-service-card__img"
                                 loading="lazy" decoding="async">
                        </div>
                    <?php endif; ?>

                    <div class="loc-service-card__body">

                        <?php if ($service_icon) :
                            $icon_url = is_array($service_icon) ? ($service_icon['url'] ?? '') : $service_icon;
                            if ($icon_url) : ?>
                                <img src="<?php echo esc_url($icon_url); ?>"
                                     alt=""
                                     class="loc-service-card__icon"
                                     width="40" height="40"
                                     aria-hidden="true" loading="lazy">
                            <?php endif;
                        endif; ?>

                        <h3 class="loc-service-card__title">
                            <a href="<?php echo esc_url($service_url); ?>">
                                <?php echo esc_html($service_title); ?>
                            </a>
                        </h3>

                        <?php if ($city) : ?>
                            <p class="loc-service-card__location-tag">
                                <?php printf(esc_html__('in %s', 'golden-globe'), esc_html($location_label)); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($service_desc) : ?>
                            <p class="loc-service-card__desc"><?php echo esc_html($service_desc); ?></p>
                        <?php endif; ?>

                        <a href="<?php echo esc_url($service_url); ?>"
                           class="loc-service-card__cta"
                           aria-label="<?php printf(esc_attr__('%1$s in %2$s', 'golden-globe'), esc_attr($service_title), esc_attr($location_label)); ?>">
                            <?php esc_html_e('Learn More', 'golden-globe'); ?>
                            <span aria-hidden="true"> →</span>
                        </a>

                    </div><!-- .loc-service-card__body -->

                </article>
            <?php endforeach; ?>
        </div><!-- .loc-services__grid -->

    </div><!-- .container -->
</section>
