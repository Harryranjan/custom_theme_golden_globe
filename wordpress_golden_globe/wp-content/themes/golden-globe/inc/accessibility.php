<?php
defined('ABSPATH') || exit;

// Add skip-to-content link support
add_action('wp_body_open', function (): void {
    echo '<a class="skip-link screen-reader-text" href="#main-content">' . esc_html__('Skip to content', 'golden-globe') . '</a>';
});

// Ensure all images have alt text fallback
add_filter('wp_get_attachment_image_attributes', function (array $attr, WP_Post $attachment): array {
    if (empty($attr['alt'])) {
        $attr['alt'] = esc_attr(get_the_title($attachment->ID));
    }
    return $attr;
}, 10, 2);

// Add aria-label to nav elements
add_filter('nav_menu_args', function (array $args): array {
    if (!empty($args['theme_location'])) {
        $args['container_aria_label'] = $args['theme_location'] . ' navigation';
    }
    return $args;
});

// Add lang attribute support
add_filter('language_attributes', function (string $output): string {
    return $output;
});
