<?php
/**
 * Template Name: Static Page
 * Template Post Type: page
 *
 * Renders with no header, no footer and no sidebars.
 * Use for: coming-soon, maintenance, standalone microsites.
 *
 * @package GoldenGlobe
 */

defined('ABSPATH') || exit;

// Minimal <head> without nav chrome.
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('template-static'); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/global/skip-link'); ?>

<main id="main-content" class="static-page">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('static-page__article'); ?>>

            <?php if (agency_get_field('show_title')) : ?>
                <header class="static-page__header">
                    <?php the_title('<h1 class="static-page__title">', '</h1>'); ?>
                </header>
            <?php endif; ?>

            <div class="static-page__content entry-content">
                <?php the_content(); ?>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
