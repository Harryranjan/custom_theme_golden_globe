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
    <?php 
    if (agency_have_rows('page_sections')) : 
        while (agency_have_rows('page_sections')) : agency_the_row(); 
            $layout = get_row_layout();
            // Map row layouts to section files
            $section_slug = str_replace('_', '-', $layout);
            agency_render('sections/section-' . $section_slug);
        endwhile; 
    endif; 
    ?>

    <!-- ── DEFAULT SECTIONS (If not in builder) ── -->
    <?php
    // We can conditionally show these if the builder is empty or as a fallback
    // For now, we assume the user builds the page via the builder.
    ?>

</main>

<?php get_header(); ?>
<?php get_footer(); ?>
