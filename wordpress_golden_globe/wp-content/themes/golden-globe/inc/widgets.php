<?php
defined('ABSPATH') || exit;

function agency_register_sidebars(): void {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar(array_merge($defaults, [
        'name' => __('Sidebar', 'golden-globe'),
        'id'   => 'sidebar-main',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 1', 'golden-globe'),
        'id'   => 'footer-col-1',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 2', 'golden-globe'),
        'id'   => 'footer-col-2',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 3', 'golden-globe'),
        'id'   => 'footer-col-3',
    ]));
}
add_action('widgets_init', 'agency_register_sidebars');
