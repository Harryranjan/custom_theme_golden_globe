<?php
defined('ABSPATH') || exit;

function agency_theme_setup(): void {
    load_theme_textdomain('golden-globe', AGENCY_THEME_DIR . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_image_size('card-thumb',   600,  400, true);
    add_image_size('hero-large',  1920,  800, true);
    add_image_size('hero-medium', 1280,  600, true);
    add_image_size('team-square',  500,  500, true);
    add_image_size('og-image',    1200,  630, true);

    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);

    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    add_theme_support('custom-background');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    add_theme_support('editor-color-palette', [
        ['name' => __('Primary',   'golden-globe'), 'slug' => 'primary',   'color' => '#2563eb'],
        ['name' => __('Secondary', 'golden-globe'), 'slug' => 'secondary', 'color' => '#64748b'],
        ['name' => __('Accent',    'golden-globe'), 'slug' => 'accent',    'color' => '#f59e0b'],
        ['name' => __('Dark',      'golden-globe'), 'slug' => 'dark',      'color' => '#0f172a'],
        ['name' => __('Light',     'golden-globe'), 'slug' => 'light',     'color' => '#f8fafc'],
    ]);

    add_theme_support('editor-font-sizes', [
        ['name' => __('Small',  'golden-globe'), 'shortName' => __('S',  'golden-globe'), 'size' => 14, 'slug' => 'small'],
        ['name' => __('Normal', 'golden-globe'), 'shortName' => __('M',  'golden-globe'), 'size' => 16, 'slug' => 'normal'],
        ['name' => __('Large',  'golden-globe'), 'shortName' => __('L',  'golden-globe'), 'size' => 20, 'slug' => 'large'],
        ['name' => __('Huge',   'golden-globe'), 'shortName' => __('XL', 'golden-globe'), 'size' => 30, 'slug' => 'huge'],
    ]);

    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1280;
    }
}
add_action('after_setup_theme', 'agency_theme_setup');
