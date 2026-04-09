<?php
/**
 * Template Name: Home Page
 * Template Post Type: page
 */

defined('ABSPATH') || exit;
get_header();

$post_id = get_the_ID();

// 1. Get Hero Data
$hero_data = [
    'heading'    => agency_get_field('hero_heading'),
    'subheading' => agency_get_field('hero_subheading'),
    'image'      => agency_get_field('hero_image'),
    'btn_label'  => agency_get_field('hero_btn_label'),
    'btn_url'    => agency_get_field('hero_btn_url'),
    'layout'     => agency_get_field('hero_layout') ?: 'centered',
];

?>

<main id="main-content" class="home-page">

    <!-- ── HERO ── -->
    <?php agency_render('sections/hero', $hero_data); ?>

    <!-- ── DYNAMIC SECTIONS BUILDER ── -->
    <?php agency_render_page_sections(); ?>

</main>

<?php get_footer(); ?>
