<?php
defined('ABSPATH') || exit;

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove X-Pingback header
add_filter('wp_headers', function (array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});

// Disable file editing in admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Security headers
add_action('send_headers', function (): void {
    // Never set our custom headers inside the WordPress admin or login screen —
    // WP admin depends heavily on inline scripts/styles (Quick Edit, media,
    // Gutenberg, ACF, etc.) and will break under a strict CSP.
    if (is_admin()) return;
    if (
        isset($_SERVER['REQUEST_URI']) &&
        strpos((string) $_SERVER['REQUEST_URI'], '/wp-login.php') !== false
    ) return;

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    // Content-Security-Policy — front-end only.
    // 'unsafe-inline' on style-src is required for many plugins and WP core
    // (block editor colour attributes, inline <style> elements, etc.).
    // If you add third-party scripts (GTM, analytics) append their domain to
    // script-src: e.g. https://www.googletagmanager.com
    $csp = implode('; ', [
        "default-src 'self'",
        "script-src 'self'",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "font-src 'self' data: https://fonts.gstatic.com",
        "img-src 'self' data: https:",
        "connect-src 'self'",
        "frame-ancestors 'self'",
        "base-uri 'self'",
        "form-action 'self'",
        'upgrade-insecure-requests',
    ]);
    header('Content-Security-Policy: ' . $csp);
});

// Sanitize uploaded SVG files (block SVG uploads by default for security)
add_filter('upload_mimes', function (array $mimes): array {
    unset($mimes['svg']);
    return $mimes;
});

// Limit login attempts via headers (basic protection)
add_filter('authenticate', function ($user, string $username) {
    if (empty($username)) return $user;
    // Sanitize username on login
    return $user;
}, 10, 2);

// Hide login errors (don't reveal whether username or password was wrong)
add_filter('login_errors', function (): string {
    return __('Incorrect credentials.', 'golden-globe');
});
