<?php
defined('ABSPATH') || exit;

/**
 * Preconnect to Google Fonts domains early — reduces DNS + TLS handshake time.
 */
add_filter('wp_resource_hints', function ( array $urls, string $relation_type ): array {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = 'https://fonts.gstatic.com';
    }
    return $urls;
}, 10, 2 );

function agency_enqueue_scripts(): void {
    $ver     = AGENCY_THEME_VERSION;
    $uri     = AGENCY_THEME_URI;
    $options = get_option('agency_theme_options');

    // Branding Fonts
    $font_sans  = $options['font_sans']  ?? 'Inter';
    $font_serif = $options['font_serif'] ?? 'Playfair Display';
    
    // Construct Google Fonts URL
    $font_families = [];
    if ($font_sans)  $font_families[] = urlencode($font_sans) . ':wght@300;400;500;600;700;900';
    if ($font_serif) $font_families[] = urlencode($font_serif) . ':ital,wght@0,400;0,700;1,400';
    
    $fonts_url = '';
    if (!empty($font_families)) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_families) . '&display=swap';
    }

    // Google Fonts
    if ($fonts_url) {
        wp_enqueue_style('golden-globe-fonts', $fonts_url, [], null);
    }

    // Main stylesheet — depends on fonts so loads after
    if (file_exists(AGENCY_THEME_DIR . '/assets/css/main.css')) {
        wp_enqueue_style('agency-main', $uri . '/assets/css/main.css', ['golden-globe-fonts'], $ver);
    } else {
        wp_enqueue_style('agency-main', $uri . '/style.css', ['golden-globe-fonts'], $ver);
    }

    // Main JS
    if (file_exists(AGENCY_THEME_DIR . '/assets/js/main.js')) {
        wp_enqueue_script('agency-main', $uri . '/assets/js/main.js', [], $ver, ['strategy' => 'defer', 'in_footer' => true]);
    }

    // Pass PHP data to JS
    wp_localize_script('agency-main', 'AgencyTheme', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'restUrl'   => esc_url_raw(rest_url('golden-globe/v1/')),
        'nonce'     => wp_create_nonce('agency_nonce'),
        'restNonce' => wp_create_nonce('wp_rest'),
        'isLoggedIn'=> is_user_logged_in(),
        'i18n'      => [
            'loading' => __('Loading...', 'golden-globe'),
            'noMore'  => __('No more posts', 'golden-globe'),
            'error'   => __('Something went wrong', 'golden-globe'),
        ],
    ]);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'agency_enqueue_scripts');

function agency_admin_scripts(): void {
    if (file_exists(AGENCY_THEME_DIR . '/assets/css/admin.css')) {
        wp_enqueue_style('agency-admin', AGENCY_THEME_URI . '/assets/css/admin.css', [], AGENCY_THEME_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'agency_admin_scripts');
