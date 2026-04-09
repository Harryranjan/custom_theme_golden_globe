<?php
/**
 * Single Location Template
 *
 * Reads the `layout_style` ACF field and loads the matching layout file.
 * Includes thin-content admin notice and Schema.org LocalBusiness markup.
 *
 * @package GoldenGlobe
 */
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    $post_id       = get_the_ID();
    $layout_style  = function_exists('get_field') ? (agency_get_field('layout_style', $post_id) ?: 'layout-1') : 'layout-1';
    $location_type = function_exists('get_field') ? (agency_get_field('location_type', $post_id) ?: 'city') : 'city';
    $city          = function_exists('get_field') ? (agency_get_field('city_name',  $post_id) ?: get_the_title()) : get_the_title();
    $state         = function_exists('get_field') ? (agency_get_field('state_abbr', $post_id) ?: '') : '';
    $local_intro   = function_exists('get_field') ? (agency_get_field('local_intro', $post_id) ?: '') : get_the_content();
    $phone         = function_exists('get_field') ? (agency_get_field('location_phone', $post_id) ?: '') : '';

    // ── Thin content admin notice ─────────────────────────────────────────────
    if (is_user_logged_in() && current_user_can('edit_posts')) {
        $word_count = str_word_count(wp_strip_all_tags($local_intro));
        if ($word_count < 300) {
            printf(
                '<div class="loc-admin-notice" role="alert" style="background:#fff3cd;border-left:4px solid #f59e0b;padding:1rem 1.25rem;margin:0;font-size:.875rem;">
                    <strong>%s</strong> %s
                    <a href="%s" style="margin-left:.5rem;">%s →</a>
                </div>',
                esc_html__('⚠ Thin Content Warning:', 'golden-globe'),
                sprintf(
                    /* translators: 1: actual word count, 2: minimum required */
                    esc_html__('Local Intro has %1$d words — minimum is %2$d for street/neighbourhood pages.', 'golden-globe'),
                    $word_count,
                    300
                ),
                esc_url(get_edit_post_link($post_id)),
                esc_html__('Edit this location', 'golden-globe')
            );
        }

        // ── Copy-paste placeholder check ─────────────────────────────────────
        $other_cities = ['Orlando', 'Miami', 'Houston', 'Chicago', 'Phoenix', 'Dallas', 'Atlanta'];
        foreach ($other_cities as $check_city) {
            if ($check_city !== $city && strpos($local_intro . get_the_title(), $check_city) !== false) {
                printf(
                    '<div class="loc-admin-notice loc-admin-notice--error" role="alert" style="background:#fee2e2;border-left:4px solid #dc2626;padding:1rem 1.25rem;margin:0;font-size:.875rem;">
                        <strong>%s</strong> %s
                        <a href="%s" style="margin-left:.5rem;">%s →</a>
                    </div>',
                    esc_html__('🚨 Placeholder City Detected:', 'golden-globe'),
                    sprintf(
                        /* translators: %s: city name found */
                        esc_html__('"%s" appears in this page content — this may be a copy-paste error from another location. Fix before publishing.', 'golden-globe'),
                        esc_html($check_city)
                    ),
                    esc_url(get_edit_post_link($post_id)),
                    esc_html__('Edit this location', 'golden-globe')
                );
            }
        }
    }

    // ── Schema.org LocalBusiness JSON-LD ────────────────────────────────────
    $schema_phone   = $phone ?: get_option('golden_globe_phone', '');
    $schema_address = [
        '@type'           => 'PostalAddress',
        'addressLocality' => $city,
        'addressRegion'   => $state,
        'addressCountry'  => 'US',
    ];
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        'name'        => get_bloginfo('name'),
        'url'         => get_permalink($post_id),
        'description' => wp_strip_all_tags(wp_trim_words($local_intro, 30)),
        'address'     => $schema_address,
        'areaServed'  => [
            '@type' => 'City',
            'name'  => $city . ($state ? ", {$state}" : ''),
        ],
    ];
    if ($schema_phone) {
        $schema['telephone'] = esc_html($schema_phone);
    }
    $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumb_url) {
        $schema['image'] = esc_url($thumb_url);
    }
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    ?>

    <main id="main" class="site-main" role="main">
        <?php
        // ── Load the chosen layout ────────────────────────────────────────────
        $allowed_layouts = ['layout-1', 'layout-2', 'layout-3'];
        $safe_layout     = in_array($layout_style, $allowed_layouts, true) ? $layout_style : 'layout-1';

        get_template_part('template-parts/location/layouts/' . $safe_layout);
        ?>
    </main>

<?php endwhile;

get_footer();
