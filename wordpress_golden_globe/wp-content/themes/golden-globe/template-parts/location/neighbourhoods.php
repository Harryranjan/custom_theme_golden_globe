<?php
/**
 * Location — Neighbourhoods / Sub-Areas Served
 *
 * @package GoldenGlobe
 * @var array $args  city, neighbourhoods (repeater rows), coverage_map (image array)
 */
defined('ABSPATH') || exit;

$city          = $args['city']          ?? '';
$neighbourhoods = $args['neighbourhoods'] ?? [];
$coverage_map  = $args['coverage_map']  ?? [];

if (empty($neighbourhoods) && empty($coverage_map)) return;

$map_url = is_array($coverage_map) ? ($coverage_map['url'] ?? '') : $coverage_map;
$map_alt = esc_attr("Coverage map for {$city}");
?>
<section class="loc-neighbourhoods" aria-labelledby="loc-nb-heading">
    <div class="container">

        <header class="loc-neighbourhoods__header">
            <span class="loc-neighbourhoods__eyebrow"><?php esc_html_e('Where We Work', 'golden-globe'); ?></span>
            <h2 id="loc-nb-heading" class="loc-neighbourhoods__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('Areas We Serve Across %s', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
        </header>

        <div class="loc-neighbourhoods__layout">

            <?php if (!empty($neighbourhoods)) : ?>
                <ul class="loc-neighbourhoods__grid" aria-label="<?php printf(esc_attr__('Neighbourhoods served in %s', 'golden-globe'), esc_attr($city)); ?>">
                    <?php foreach ($neighbourhoods as $nb) :
                        $name      = $nb['nb_name'] ?? '';
                        $link      = $nb['nb_link'] ?? '';
                        $icon_data = $nb['nb_icon'] ?? [];
                        $icon_url  = is_array($icon_data) ? ($icon_data['url'] ?? '') : $icon_data;
                        $icon_alt  = esc_attr("{$name}, {$city}");
                        if (!$name) continue;
                        ?>
                        <li class="loc-nb-chip">
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>" class="loc-nb-chip__link">
                            <?php endif; ?>

                            <?php if ($icon_url) : ?>
                                <img src="<?php echo esc_url($icon_url); ?>"
                                     alt="<?php echo $icon_alt; ?>"
                                     class="loc-nb-chip__icon"
                                     width="32" height="32"
                                     loading="lazy" decoding="async">
                            <?php else : ?>
                                <span class="loc-nb-chip__pin" aria-hidden="true">📍</span>
                            <?php endif; ?>

                            <span class="loc-nb-chip__name"><?php echo esc_html($name); ?></span>

                            <?php if ($link) : ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($map_url) : ?>
                <div class="loc-neighbourhoods__map">
                    <img src="<?php echo esc_url($map_url); ?>"
                         alt="<?php echo $map_alt; ?>"
                         class="loc-neighbourhoods__map-img"
                         loading="lazy" decoding="async">
                </div>
            <?php endif; ?>

        </div><!-- .loc-neighbourhoods__layout -->

    </div><!-- .container -->
</section>
