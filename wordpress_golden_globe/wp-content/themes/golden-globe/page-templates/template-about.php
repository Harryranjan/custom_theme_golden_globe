<?php
/**
 * Template Name: About Page
 * Description: A story-driven layout designed to build agency authority through Mission, Vision, Values, and stats.
 */

defined('ABSPATH') || exit;
get_header();

$post_id = get_the_ID();

// 1. Get Hero Data (Universal Hero)
$hero_data = [
    'heading'    => agency_get_field('hero_heading')    ?: get_the_title(),
    'subheading' => agency_get_field('hero_subheading') ?: __('Discover our story, our mission, and the people behind the world\'s most ambitious projects.', 'golden-globe'),
    'image'      => agency_get_field('hero_image'),
    'btn_label'  => agency_get_field('hero_btn_label'),
    'btn_url'    => agency_get_field('hero_btn_url'),
    'layout'     => agency_get_field('hero_layout')     ?: 'centered',
];

?>

<main id="main-content" class="about-page">

    <!-- ── HERO ── -->
    <?php agency_render('sections/hero', $hero_data); ?>

    <!-- ── THE STORY (Manual call for standardized "About" flow) ── -->
    <?php 
    $story_img   = agency_get_field('story_image');
    $story_title = agency_get_field('story_title') ?: __('Crafting Excellence Since Day One', 'golden-globe');
    $story_text  = agency_get_field('story_text')  ?: get_the_content();
    ?>
    <section class="section section-story">
        <div class="container">
            <div class="grid grid--2 align-items-center">
                <div class="story-content reveal-fade">
                    <span class="eyebrow"><?php _e('Our History', 'golden-globe'); ?></span>
                    <h2 class="section-title"><?php echo esc_html($story_title); ?></h2>
                    <div class="story-body">
                        <?php echo wp_kses_post($story_text); ?>
                    </div>
                </div>
                <div class="story-visual reveal-fade" style="transition-delay: 0.2s;">
                    <?php if ($story_img) : ?>
                        <img src="<?php echo esc_url($story_img['url']); ?>" alt="<?php echo esc_attr($story_img['alt']); ?>" class="img-fluid rounded shadow">
                    <?php else : ?>
                        <div class="placeholder-box shadow-xl"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <style>
        .section-story { padding: 100px 0; background: var(--color-white); }
        .story-body { font-size: 1.125rem; line-height: 1.8; color: var(--color-gray-600); }
        .story-body p { margin-bottom: 1.5rem; }
        .placeholder-box { width: 100%; aspect-ratio: 4/5; background: var(--color-gray-100); border-radius: var(--radius-xl); }
    </style>

    <!-- ── CORE VALUES ── -->
    <?php agency_render('sections/section-values-grid'); ?>

    <!-- ── COUNTER STATS ── -->
    <?php agency_render('sections/section-counter-stats', ['bg' => 'primary']); ?>

    <!-- ── TEAM HIGHLIGHTS ── -->
    <?php agency_render('sections/section-team-highlights'); ?>

    <!-- ── DYNAMIC SECTIONS BUILDER (Allow extra content) ── -->
    <?php agency_render_page_sections(); ?>

</main>

<?php get_footer(); ?>
