<?php
/**
 * Section: Portfolio Showcase
 */
$title = agency_get_sub_field('title') ?: __('Our Recent Work', 'golden-globe');
$count = (int) (agency_get_sub_field('count') ?: 4);

$query = new WP_Query([
    'post_type'      => 'portfolio',
    'posts_per_page' => $count,
    'no_found_rows'  => true,
]);
?>

<section class="section section-portfolio-showcase">
    <div class="container">
        <div class="section-header reveal-fade" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:4rem;">
            <div>
                <span class="eyebrow"><?php _e('Portfolio', 'golden-globe'); ?></span>
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            </div>
            <a href="<?php echo get_post_type_archive_link('portfolio'); ?>" class="agency-btn agency-btn--secondary">
                <?php _e('View All Work', 'golden-globe'); ?>
            </a>
        </div>

        <?php if ($query->have_posts()) : ?>
            <div class="portfolio-showcase-grid">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="showcase-item reveal-fade">
                        <a href="<?php the_permalink(); ?>" class="showcase-item__link">
                            <div class="showcase-item__image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                <?php else : ?>
                                    <div class="placeholder"></div>
                                <?php endif; ?>
                            </div>
                            <div class="showcase-item__overlay">
                                <div class="showcase-item__meta">
                                    <?php 
                                    $cats = get_the_terms(get_the_ID(), 'portfolio_cat');
                                    if ($cats) : ?>
                                        <span class="showcase-item__cat"><?php echo esc_html($cats[0]->name); ?></span>
                                    <?php endif; ?>
                                    <h3 class="showcase-item__title"><?php the_title(); ?></h3>
                                </div>
                                <div class="showcase-item__icon">
                                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-portfolio-showcase { background-color: var(--color-white); }
.portfolio-showcase-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; }
@media (max-width: 768px) { .portfolio-showcase-grid { grid-template-columns: 1fr; } }

.showcase-item { position: relative; border-radius: var(--radius-lg); overflow: hidden; height: 450px; }
.showcase-item__link { display: block; height: 100%; width: 100%; }
.showcase-item__image { height: 100%; width: 100%; transition: transform var(--transition-slow); }
.showcase-item__image img { width: 100%; height: 100%; object-fit: cover; }
.showcase-item:hover .showcase-item__image { transform: scale(1.05); }

.showcase-item__overlay { 
    position: absolute; inset: 0; padding: 2.5rem; display: flex; align-items: flex-end; justify-content: space-between;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
    opacity: 0.9; transition: opacity var(--transition-normal);
}
.showcase-item:hover .showcase-item__overlay { opacity: 1; }

.showcase-item__cat { 
    display: inline-block; padding: 4px 12px; background: var(--color-primary); color: white; 
    font-size: 0.75rem; font-weight: 700; text-transform: uppercase; border-radius: var(--radius-full); margin-bottom: 0.75rem;
}
.showcase-item__title { color: white; margin: 0; font-size: 1.75rem; }
.showcase-item__icon { 
    width: 48px; height: 48px; background: white; border-radius: 50%; display: flex; 
    align-items: center; justify-content: center; color: var(--color-primary);
    transform: translateX(10px); opacity: 0; transition: all var(--transition-normal);
}
.showcase-item:hover .showcase-item__icon { transform: translateX(0); opacity: 1; }
</style>
