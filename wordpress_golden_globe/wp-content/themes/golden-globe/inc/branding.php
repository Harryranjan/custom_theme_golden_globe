<?php
/**
 * Golden Globe — Branding Engine
 * Injects dynamic CSS variables based on Theme Options.
 */

defined('ABSPATH') || exit;

/**
 * Injects dynamic CSS variables into the head.
 */
function agency_inject_branding_css(): void {
    $options = get_option('agency_theme_options');
    
    // Default values (fallback to main.css defaults)
    $primary    = $options['brand_primary']   ?? '#2563eb';
    $secondary  = $options['brand_secondary'] ?? '#64748b';
    $accent     = $options['brand_accent']    ?? '#f59e0b';
    $font_sans  = $options['font_sans']       ?? 'Inter';
    $font_serif = $options['font_serif']      ?? 'Playfair Display';
    $radius     = $options['border_radius']   ?? '8px';

    // Calculate variations
    $primary_dark  = agency_adjust_brightness($primary, -20);
    $primary_light = agency_adjust_brightness($primary, 40);

    ?>
    <style id="agency-branding-dynamic">
        :root {
            /* Dynamic Colors */
            --color-primary: <?php echo esc_attr($primary); ?>;
            --color-primary-dark: <?php echo esc_attr($primary_dark); ?>;
            --color-primary-light: <?php echo esc_attr($primary_light); ?>;
            --color-secondary: <?php echo esc_attr($secondary); ?>;
            --color-accent: <?php echo esc_attr($accent); ?>;

            /* Dynamic Typography */
            <?php if ($font_sans) : ?>
            --font-sans: "<?php echo esc_attr($font_sans); ?>", system-ui, -apple-system, sans-serif;
            <?php endif; ?>
            
            <?php if ($font_serif) : ?>
            --font-serif: "<?php echo esc_attr($font_serif); ?>", Georgia, serif;
            <?php endif; ?>

            /* Dynamic Spacing & Shapes */
            --radius-md: <?php echo esc_attr($radius); ?>;
            --radius-lg: <?php echo esc_attr( (int)$radius * 1.5 ); ?>px;
            --radius-xl: <?php echo esc_attr( (int)$radius * 2 ); ?>px;
        }
    </style>
    <?php
}
add_action('wp_head', 'agency_inject_branding_css', 100);

/**
 * Helper to adjust hex color brightness.
 * $steps: -255 to 255
 */
function agency_adjust_brightness(string $hex, int $steps): string {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);

    if (3 === strlen($hex)) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) . str_pad(dechex($g), 2, '0', STR_PAD_LEFT) . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}
