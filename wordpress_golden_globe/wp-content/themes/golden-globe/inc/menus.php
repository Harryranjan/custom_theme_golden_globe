<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    register_nav_menus([
        'primary' => __('Primary Navigation', 'golden-globe'),
        'footer'  => __('Footer Navigation',  'golden-globe'),
        'mobile'  => __('Mobile Navigation',  'golden-globe'),
        'social'  => __('Social Links',        'golden-globe'),
    ]);
});
