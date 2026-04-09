<?php
/**
 * Template Name: Sections Builder
 * Description: A flexible, full-width canvas for building custom pages with modular sections.
 */

defined('ABSPATH') || exit;
get_header();

// 1. Get Hero Data (Universal Hero)
$hero_data = [
    'heading'    => agency_get_field('hero_heading'),
    'subheading' => agency_get_field('hero_subheading'),
    'image'      => agency_get_field('hero_image'),
    'btn_label'  => agency_get_field('hero_btn_label'),
    'btn_url'    => agency_get_field('hero_btn_url'),
    'layout'     => agency_get_field('hero_layout') ?: 'centered',
];
?>

<main id="main-content" class="sections-builder-page">

    <!-- ── HERO ── -->
    <?php if ($hero_data['heading']) : ?>
        <?php agency_render('sections/hero', $hero_data); ?>
    <?php else : ?>
        <!-- Default Interior Header for sub-pages without a full hero -->
        <header class="section-builder-header">
            <div class="container">
                <h1 class="reveal-fade"><?php the_title(); ?></h1>
            </div>
        </header>
        <style>
            .section-builder-header { padding: 80px 0; background: var(--color-gray-50); border-bottom: 1px solid var(--color-gray-200); }
            .section-builder-header h1 { margin: 0; font-size: 3rem; }
        </style>
    <?php endif; ?>

    <!-- ── DYNAMIC SECTIONS ── -->
    <?php agency_render_page_sections(); ?>

</main>

<?php get_footer(); ?>
