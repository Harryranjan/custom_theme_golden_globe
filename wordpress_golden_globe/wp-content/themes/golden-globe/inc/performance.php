<?php
defined('ABSPATH') || exit;

// ─── HEAD CLEANUP ─────────────────────────────────────────────────────────────

// Remove emoji scripts & styles (~30 KB saved)
remove_action('wp_head',             'print_emoji_detection_script', 7);
remove_action('wp_print_styles',     'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles',  'print_emoji_styles');
add_filter('emoji_svg_url',           '__return_false');

// Remove WP generator meta tag (hides WP version from bots)
remove_action('wp_head', 'wp_generator');

// Remove RSD, wlwmanifest, shortlink, oEmbed discovery links
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// Remove REST API link from <head> and HTTP Link header
remove_action('wp_head',          'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11);

// Remove X-Pingback header
add_filter('x_pingback', '__return_empty_string');
add_filter('pings_open',  '__return_false', 20, 2);

// ─── XML-RPC ──────────────────────────────────────────────────────────────────
// Disable XML-RPC entirely — it's an attack surface we don't use.
// Note: this does NOT break the REST API.
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function (): array { return []; });

// ─── SCRIPTS & STYLES ────────────────────────────────────────────────────────

// Remove jQuery Migrate (not needed by modern code)
add_action('wp_default_scripts', function ($scripts): void {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
});

// Add defer attribute to non-critical front-end theme scripts
// Guard: never run in admin — WP 6.9 already manages defer via 'strategy' param
// and double-deferring admin scripts breaks Gutenberg's wp.apiFetch initialisation.
add_filter('script_loader_tag', function (string $tag, string $handle): string {
    if (is_admin()) return $tag;
    // Scripts that must remain synchronous (parser-blocking is intentional)
    $sync = ['jquery-core', 'jquery'];
    if (in_array($handle, $sync, true)) return $tag;
    // Only defer scripts enqueued by the theme
    if (strpos($tag, '/themes/golden-globe/') === false) return $tag;
    // Don't add defer if it already has async or defer (any form)
    if (preg_match('/\b(defer|async)\b/i', $tag)) return $tag;
    return str_replace(' src=', ' defer src=', $tag);
}, 10, 2);

// Dequeue block library CSS on pages that render no Gutenberg blocks
add_action('wp_enqueue_scripts', function (): void {
    if (!is_singular()) return;
    $post = get_queried_object();
    if (!$post instanceof WP_Post) return;
    if (!has_blocks($post->post_content)) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
    }
}, 100);

// ─── RESOURCE HINTS ──────────────────────────────────────────────────────────

// Preconnect for Google Fonts + CDN origins used by this theme
add_action('wp_head', function (): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    // DNS-prefetch fallback for older browsers
    echo '<link rel="dns-prefetch" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://fonts.gstatic.com">' . "\n";
}, 1);
