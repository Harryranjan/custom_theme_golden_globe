<?php
defined('ABSPATH') || exit;

// Example: [agency_button url="https://example.com" label="Learn More"]
add_shortcode('agency_button', function (array $atts): string {
    $atts = shortcode_atts([
        'url'    => '#',
        'label'  => __('Learn More', 'golden-globe'),
        'target' => '_self',
        'class'  => 'btn btn--primary',
    ], $atts, 'agency_button');

    $target = $atts['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : '';

    return sprintf(
        '<a href="%s" class="%s" %s>%s</a>',
        esc_url($atts['url']),
        esc_attr($atts['class']),
        $target,
        esc_html($atts['label'])
    );
});

// Example: [agency_year] — outputs current year
add_shortcode('agency_year', function (): string {
    return esc_html(date('Y'));
});
