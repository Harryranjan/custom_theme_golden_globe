<?php
defined('ABSPATH') || exit;

add_action('acf/init', function (): void {
    if (!function_exists('acf_register_block_type')) return;

    // Hero Block
    acf_register_block_type([
        'name'            => 'hero',
        'title'           => __('Hero', 'golden-globe'),
        'description'     => __('A hero section with heading, text and CTA.', 'golden-globe'),
        'render_template' => 'template-parts/blocks/block-hero.php',
        'category'        => 'layout',
        'icon'            => 'cover-image',
        'keywords'        => ['hero', 'banner', 'header'],
        'supports'        => ['align' => ['full', 'wide'], 'mode' => false],
    ]);

    // Cards Block
    acf_register_block_type([
        'name'            => 'cards',
        'title'           => __('Cards Grid', 'golden-globe'),
        'description'     => __('A repeatable cards grid.', 'golden-globe'),
        'render_template' => 'template-parts/blocks/block-cards.php',
        'category'        => 'layout',
        'icon'            => 'grid-view',
        'keywords'        => ['cards', 'grid', 'features'],
        'supports'        => ['align' => ['wide', 'full'], 'mode' => false],
    ]);

    // CTA Block
    acf_register_block_type([
        'name'            => 'cta',
        'title'           => __('CTA Banner', 'golden-globe'),
        'description'     => __('A call-to-action banner.', 'golden-globe'),
        'render_template' => 'template-parts/blocks/block-cta.php',
        'category'        => 'layout',
        'icon'            => 'megaphone',
        'keywords'        => ['cta', 'call to action', 'banner'],
        'supports'        => ['align' => ['full'], 'mode' => false],
    ]);

    // Testimonials Block
    acf_register_block_type([
        'name'            => 'testimonials',
        'title'           => __('Testimonials', 'golden-globe'),
        'description'     => __('Testimonials slider.', 'golden-globe'),
        'render_template' => 'template-parts/blocks/block-testimonials.php',
        'category'        => 'layout',
        'icon'            => 'format-quote',
        'keywords'        => ['testimonials', 'reviews', 'quotes'],
        'supports'        => ['align' => false, 'mode' => false],
    ]);
});
