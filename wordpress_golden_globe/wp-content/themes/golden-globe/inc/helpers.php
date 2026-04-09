<?php
defined('ABSPATH') || exit;

function agency_render(string $slug, array $args = []): void {
    get_template_part('template-parts/' . $slug, null, $args);
}

function agency_get_adjacent_posts(): array {
    return [
        'prev' => get_previous_post(),
        'next' => get_next_post(),
    ];
}

/**
 * Compatibility wrapper for agency_get_field().
 * Falls back to native post meta if ACF is missing.
 */
function agency_get_field(string $selector, $post_id = false, bool $format_value = true) {
    if (function_exists('get_field')) {
        return get_field($selector, $post_id, $format_value);
    }

    if ('option' === $post_id || 'options' === $post_id) {
        return agency_get_option($selector);
    }

    $post_id = $post_id ?: get_the_ID();
    if (!$post_id) return null;

    $value = get_post_meta($post_id, $selector, true);
    
    // Auto-unserialize if it happens to be serialized (though get_post_meta does this)
    // and handle JSON strings which we use for repeaters in the native system.
    if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
    }

    return $value;
}

/**
 * Echoes a field value.
 */
function agency_the_field(string $selector, $post_id = false): void {
    echo agency_get_field($selector, $post_id);
}

/**
 * Compatibility wrapper for have_rows().
 */
$agency_rows_stack = [];
function agency_have_rows(string $selector, $post_id = false): bool {
    global $agency_rows_stack;
    
    if (function_exists('have_rows')) {
        return have_rows($selector, $post_id);
    }

    $rows = agency_get_field($selector, $post_id);
    if (!is_array($rows) || empty($rows)) {
        return false;
    }

    // Initialize stack if needed
    if (!isset($agency_rows_stack[$selector])) {
        $agency_rows_stack[$selector] = [
            'data'  => $rows,
            'index' => -1,
            'total' => count($rows)
        ];
    }

    return ($agency_rows_stack[$selector]['index'] + 1) < $agency_rows_stack[$selector]['total'];
}

/**
 * Compatibility wrapper for the_row().
 */
function agency_the_row(string $selector = ''): void {
    global $agency_rows_stack;
    
    if (function_exists('the_row')) {
        the_row();
        return;
    }

    // If no selector provided, we might be in a nested loop (ACF usually handles this automatically)
    // For our native version, we assume the last started loop.
    if (empty($selector)) {
        $selector = array_key_last($agency_rows_stack);
    }

    if (isset($agency_rows_stack[$selector])) {
        $agency_rows_stack[$selector]['index']++;
    }
}

/**
 * Compatibility wrapper for get_sub_field().
 */
function agency_get_sub_field(string $selector) {
    global $agency_rows_stack;
    
    if (function_exists('get_sub_field')) {
        return get_sub_field($selector);
    }

    $current_loop = array_key_last($agency_rows_stack);
    if ($current_loop && isset($agency_rows_stack[$current_loop])) {
        $row = $agency_rows_stack[$current_loop]['data'][$agency_rows_stack[$current_loop]['index']];
        return $row[$selector] ?? null;
    }

    return null;
}

function agency_acf_date(string $field, int $post_id = 0, string $format = 'F j, Y'): string {
    $raw = agency_get_field($field, $post_id ?: null);
    if (!$raw) return '';
    return date_i18n($format, strtotime($raw));
}

function agency_excerpt(string $text, int $words = 20): string {
    return wp_trim_words(wp_strip_all_tags($text), $words, '&hellip;');
}

function agency_get_first_term(int $post_id, string $taxonomy): ?WP_Term {
    $terms = get_the_terms($post_id, $taxonomy);
    return (!empty($terms) && !is_wp_error($terms)) ? reset($terms) : null;
}

function agency_hex_to_rgba(string $hex, float $alpha = 1): string {
    $hex = ltrim($hex, '#');
    [$r, $g, $b] = str_split(strlen($hex) === 3
        ? implode('', array_map(fn($c) => $c . $c, str_split($hex)))
        : $hex, 2);
    return "rgba({$r},{$g},{$b},{$alpha})";
}

function agency_is_template(string $template): bool {
    return is_page_template("page-templates/{$template}.php");
}

function agency_icon(string $name, string $class = ''): void {
    // Restrict name to safe characters — prevents path traversal
    $name = preg_replace('/[^a-z0-9\-_]/', '', $name);
    $path = AGENCY_THEME_DIR . "/assets/images/icons/{$name}.svg";

    if (!file_exists($path)) {
        return;
    }

    $svg = file_get_contents($path); // phpcs:ignore WordPress.WP.AlternativeFunctions

    if (false === $svg) {
        return;
    }

    if ($class) {
        $svg = str_replace('<svg', '<svg class="' . esc_attr($class) . '"', $svg);
    }

    // Strip <script>, event handlers (onclick, onload, etc.) and javascript: hrefs
    $allowed = [
        'svg'      => ['xmlns' => true, 'viewbox' => true, 'width' => true, 'height' => true, 'aria-hidden' => true, 'focusable' => true, 'role' => true, 'class' => true, 'fill' => true, 'stroke' => true],
        'path'     => ['d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'fill-rule' => true, 'clip-rule' => true],
        'circle'   => ['cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true],
        'rect'     => ['x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true, 'stroke' => true],
        'line'     => ['x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'stroke' => true, 'stroke-width' => true],
        'polyline' => ['points' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true],
        'polygon'  => ['points' => true, 'fill' => true, 'stroke' => true],
        'g'        => ['fill' => true, 'stroke' => true, 'transform' => true],
        'title'    => [],
        'desc'     => [],
        'defs'     => [],
        'use'      => ['href' => true, 'xlink:href' => true],
        'symbol'   => ['id' => true, 'viewbox' => true],
        'clippath' => ['id' => true],
    ];

    echo wp_kses($svg, $allowed);
}

/**
 * Get a theme option value.
 * Falls back to ACF's agency_get_field('field', 'option') if available.
 */
function agency_get_option(string $selector, $default = null) {
    if (function_exists('get_field')) {
        $value = agency_get_field($selector, 'option');
        if ($value !== null && $value !== false) return $value;
    }

    $options = get_option('agency_theme_options');
    return $options[$selector] ?? $default;
}
