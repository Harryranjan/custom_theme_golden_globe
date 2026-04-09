<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 */

defined('ABSPATH') || exit;

get_header();

$hero_heading    = agency_get_field('hero_heading');
$hero_subheading = agency_get_field('hero_subheading');
$hero_image      = agency_get_field('hero_image');
$hero_btn_label  = agency_get_field('hero_btn_label');
$hero_btn_url    = agency_get_field('hero_btn_url');
$hero_layout     = agency_get_field('hero_layout') ?: 'centered';
?>

<main id="main-content" class="landing-page">

    <!-- HERO -->
    <?php agency_render('components/hero', compact(
        'hero_heading', 'hero_subheading',
        'hero_image', 'hero_btn_label',
        'hero_btn_url', 'hero_layout'
    )); ?>

    <!-- FLEXIBLE CONTENT SECTIONS -->
    <?php if (agency_have_rows('page_sections')) : ?>
        <?php while (agency_have_rows('page_sections')) : agency_the_row(); ?>

            <?php if (get_row_layout() === 'features_grid') : ?>
                <section class="section">
                    <div class="container">
                        <h2 class="text-center" style="margin-bottom:2rem;">
                            <?php echo esc_html(agency_get_sub_field('title')); ?>
                        </h2>
                        <div class="grid grid--3">
                            <?php if (agency_have_rows('items')) : ?>
                                <?php while (agency_have_rows('items')) : agency_the_row(); ?>
                                    <div class="card">
                                        <?php $icon = agency_get_sub_field('icon'); ?>
                                        <?php if ($icon) : ?>
                                            <img src="<?php echo esc_url($icon['url']); ?>"
                                                 alt="<?php echo esc_attr($icon['alt']); ?>"
                                                 width="48" height="48" loading="lazy"
                                                 style="padding:1.5rem 1.5rem 0;">
                                        <?php endif; ?>
                                        <div class="card__body">
                                            <h3 class="card__title"><?php echo esc_html(agency_get_sub_field('name')); ?></h3>
                                            <p class="card__excerpt"><?php echo esc_html(agency_get_sub_field('desc')); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'cta_banner') : ?>
                <?php agency_render('components/cta', [
                    'title'    => agency_get_sub_field('title'),
                    'text'     => agency_get_sub_field('text'),
                    'button'   => agency_get_sub_field('button'),
                    'bg_color' => agency_get_sub_field('bg_color') ?: '#2563eb',
                ]); ?>

            <?php elseif (get_row_layout() === 'image_text') :
                $it_pos = agency_get_sub_field('image_position') ?: 'right';
                $it_bg  = agency_get_sub_field('bg') ?: 'white';
                $it_img = agency_get_sub_field('image');
                $it_btn = agency_get_sub_field('button');
            ?>
                <section class="section split-section split-section--<?php echo esc_attr($it_pos); ?> split-section--bg-<?php echo esc_attr($it_bg); ?>">
                    <div class="container">
                        <div class="split-section__inner">

                            <div class="split-section__image">
                                <?php if ($it_img) : ?>
                                    <img src="<?php echo esc_url($it_img['url']); ?>"
                                         alt="<?php echo esc_attr($it_img['alt']); ?>"
                                         width="<?php echo esc_attr($it_img['width']); ?>"
                                         height="<?php echo esc_attr($it_img['height']); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </div>

                            <div class="split-section__text">
                                <?php $eyebrow = agency_get_sub_field('eyebrow'); ?>
                                <?php if ($eyebrow) : ?>
                                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
                                <?php endif; ?>
                                <?php $it_title = agency_get_sub_field('title'); ?>
                                <?php if ($it_title) : ?>
                                    <h2><?php echo esc_html($it_title); ?></h2>
                                <?php endif; ?>
                                <div class="split-section__body">
                                    <?php echo wp_kses_post(agency_get_sub_field('text')); ?>
                                </div>
                                <?php if ($it_btn) : ?>
                                    <a href="<?php echo esc_url($it_btn['url']); ?>"
                                       class="btn btn--primary"
                                       <?php echo $it_btn['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                        <?php echo esc_html($it_btn['title']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'stats_bar') :
                $sb_bg = agency_get_sub_field('bg') ?: 'primary';
            ?>
                <section class="section stats-bar stats-bar--<?php echo esc_attr($sb_bg); ?>">
                    <div class="container">
                        <?php $sb_title = agency_get_sub_field('title'); ?>
                        <?php if ($sb_title) : ?>
                            <h2 class="stats-bar__title"><?php echo esc_html($sb_title); ?></h2>
                        <?php endif; ?>
                        <?php if (agency_have_rows('items')) : ?>
                            <div class="stats-bar__grid">
                                <?php while (agency_have_rows('items')) : agency_the_row(); ?>
                                    <div class="stat-item">
                                        <div class="stat-item__number">
                                            <?php if ($p = agency_get_sub_field('prefix')) echo '<span class="stat-item__prefix">' . esc_html($p) . '</span>'; ?>
                                            <?php echo esc_html(agency_get_sub_field('number')); ?>
                                            <?php if ($s = agency_get_sub_field('suffix')) echo '<span class="stat-item__suffix">' . esc_html($s) . '</span>'; ?>
                                        </div>
                                        <div class="stat-item__label"><?php echo esc_html(agency_get_sub_field('label')); ?></div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'faq') : ?>
                <section class="section faq-section">
                    <div class="container container--narrow">
                        <?php $faq_title = agency_get_sub_field('title'); ?>
                        <?php if ($faq_title) : ?>
                            <h2 class="text-center"><?php echo esc_html($faq_title); ?></h2>
                        <?php endif; ?>
                        <?php $faq_intro = agency_get_sub_field('intro'); ?>
                        <?php if ($faq_intro) : ?>
                            <p class="faq-section__intro text-center"><?php echo esc_html($faq_intro); ?></p>
                        <?php endif; ?>
                        <?php if (agency_have_rows('items')) : ?>
                            <div class="faq-list" role="list">
                                <?php $faq_index = 0; while (agency_have_rows('items')) : agency_the_row(); $faq_index++;
                                    $faq_id = 'faq-' . get_the_ID() . '-' . $faq_index; ?>
                                    <div class="faq-item" role="listitem">
                                        <button class="faq-item__question"
                                                aria-expanded="false"
                                                aria-controls="<?php echo esc_attr($faq_id); ?>">
                                            <?php echo esc_html(agency_get_sub_field('question')); ?>
                                            <span class="faq-item__icon" aria-hidden="true"></span>
                                        </button>
                                        <div class="faq-item__answer" id="<?php echo esc_attr($faq_id); ?>" hidden>
                                            <?php echo wp_kses_post(agency_get_sub_field('answer')); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'testimonials') :
                $testi_count  = (int) (agency_get_sub_field('count') ?: 3);
                $testi_layout = agency_get_sub_field('layout') ?: 'grid';
                $testi_bg     = agency_get_sub_field('bg') ?: 'light';
                $testi_query  = new WP_Query([
                    'post_type'           => 'testimonial',
                    'posts_per_page'      => $testi_count,
                    'no_found_rows'       => true,
                    'ignore_sticky_posts' => true,
                ]);
            ?>
                <section class="section testimonials testimonials--<?php echo esc_attr($testi_layout); ?> testimonials--<?php echo esc_attr($testi_bg); ?>">
                    <div class="container">
                        <?php if ($testi_title = agency_get_sub_field('title')) : ?>
                            <h2 class="section-title text-center"><?php echo esc_html($testi_title); ?></h2>
                        <?php endif; ?>
                        <?php if ($testi_intro = agency_get_sub_field('intro')) : ?>
                            <p class="section-intro text-center"><?php echo esc_html($testi_intro); ?></p>
                        <?php endif; ?>
                        <?php if ($testi_query->have_posts()) : ?>
                            <div class="testimonials__grid">
                                <?php while ($testi_query->have_posts()) : $testi_query->the_post();
                                    $quote  = get_post_meta(get_the_ID(), 'testimonial_quote',  true) ?: get_the_content();
                                    $author = get_post_meta(get_the_ID(), 'testimonial_author', true) ?: get_the_title();
                                    $role   = get_post_meta(get_the_ID(), 'testimonial_role',   true);
                                    $rating = (int) get_post_meta(get_the_ID(), 'testimonial_rating', true);
                                ?>
                                    <blockquote class="testimonial-card">
                                        <?php if ($rating > 0) : ?>
                                            <div class="testimonial-card__stars" aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'golden-globe'), $rating)); ?>">
                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                    <span class="star<?php echo $i <= $rating ? ' star--filled' : ''; ?>" aria-hidden="true">&#9733;</span>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endif; ?>
                                        <p class="testimonial-card__quote"><?php echo wp_kses_post($quote); ?></p>
                                        <footer class="testimonial-card__footer">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="testimonial-card__avatar">
                                                    <?php the_post_thumbnail('thumbnail', [
                                                        'loading' => 'lazy',
                                                        'alt'     => esc_attr($author),
                                                    ]); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="testimonial-card__author-info">
                                                <cite class="testimonial-card__author"><?php echo esc_html($author); ?></cite>
                                                <?php if ($role) : ?>
                                                    <span class="testimonial-card__role"><?php echo esc_html($role); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </footer>
                                    </blockquote>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

            <?php endif; ?>

        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
