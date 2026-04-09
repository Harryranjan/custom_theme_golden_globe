<?php
/**
 * Section: Testimonials
 */
$title = agency_get_sub_field('title');
$intro = agency_get_sub_field('intro');
$layout = agency_get_sub_field('layout') ?: 'grid';
$count  = (int) (agency_get_sub_field('count') ?: 3);

$query = new WP_Query([
    'post_type'      => 'testimonial',
    'posts_per_page' => $count,
    'no_found_rows'  => true,
]);
?>

<section class="section section-testimonials section-testimonials--<?php echo esc_attr($layout); ?>">
    <div class="container">
        <?php if ($title || $intro) : ?>
            <div class="section-header text-center reveal-fade">
                <?php if ($title) : ?><h2 class="section-title"><?php echo esc_html($title); ?></h2><?php endif; ?>
                <?php if ($intro) : ?><p class="section-desc"><?php echo esc_html($intro); ?></p><?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($query->have_posts()) : ?>
            <div class="testimonials-grid testimonials-grid--<?php echo esc_attr($layout); ?>">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $quote  = get_post_meta(get_the_ID(), 'testimonial_quote',  true) ?: get_the_content();
                    $author = get_post_meta(get_the_ID(), 'testimonial_author', true) ?: get_the_title();
                    $role   = get_post_meta(get_the_ID(), 'testimonial_role',   true);
                ?>
                    <div class="testimonial-item reveal-fade">
                        <div class="testimonial-item__inner">
                            <div class="testimonial-item__quote-icon"><?php agency_icon('quote'); ?></div>
                            <div class="testimonial-item__content">
                                <p><?php echo wp_kses_post($quote); ?></p>
                            </div>
                            <div class="testimonial-item__footer">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="testimonial-item__avatar">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="testimonial-item__meta">
                                    <strong class="testimonial-item__author"><?php echo esc_html($author); ?></strong>
                                    <?php if ($role) : ?>
                                        <span class="testimonial-item__role"><?php echo esc_html($role); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-testimonials { background-color: var(--color-white); }
.testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
@media (max-width: 991px) { .testimonials-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .testimonials-grid { grid-template-columns: 1fr; } }

.testimonial-item__inner { 
    height: 100%; display: flex; flex-direction: column; padding: 2.5rem; 
    background: var(--color-gray-50); border-radius: var(--radius-lg); border: 1px solid var(--color-gray-200);
}
.testimonial-item__quote-icon { color: var(--color-primary); margin-bottom: 1.5rem; opacity: 0.2; }
.testimonial-item__quote-icon svg { width: 40px; height: 40px; }
.testimonial-item__content { flex-grow: 1; margin-bottom: 2rem; font-size: 1.125rem; font-style: italic; line-height: 1.6; color: var(--color-gray-700); }
.testimonial-item__footer { display: flex; align-items: center; gap: 1rem; }
.testimonial-item__avatar { width: 48px; height: 48px; border-radius: 50%; overflow: hidden; }
.testimonial-item__avatar img { width: 100%; height: 100%; object-fit: cover; }
.testimonial-item__author { display: block; font-size: 1rem; color: var(--color-black); }
.testimonial-item__role { font-size: 0.875rem; color: var(--color-gray-500); }
</style>
