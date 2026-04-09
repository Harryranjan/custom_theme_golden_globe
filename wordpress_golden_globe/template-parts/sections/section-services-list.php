<?php
/**
 * Section: Services List
 */
$title = agency_get_sub_field('title') ?: __('Our Core Services', 'golden-globe');
$count = (int) (agency_get_sub_field('count') ?: 6);

$query = new WP_Query([
    'post_type'      => 'service',
    'posts_per_page' => $count,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);
?>

<section class="section section-services-list">
    <div class="container">
        <div class="section-header text-center reveal-fade">
            <span class="eyebrow"><?php _e('Expertise', 'golden-globe'); ?></span>
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
        </div>

        <?php if ($query->have_posts()) : ?>
            <div class="grid grid--3">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $sid      = get_the_ID();
                    $tagline  = agency_get_field('service_tagline', $sid);
                    $icon_name = agency_get_field('service_icon', $sid) ?: 'settings';
                ?>
                    <div class="service-card-item reveal-fade">
                        <div class="service-card-item__icon">
                            <?php agency_icon($icon_name); ?>
                        </div>
                        <h3 class="service-card-item__title"><?php the_title(); ?></h3>
                        <?php if ($tagline) : ?>
                            <p class="service-card-item__tagline"><?php echo esc_html($tagline); ?></p>
                        <?php endif; ?>
                        <p class="service-card-item__excerpt"><?php echo get_the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="service-card-item__link">
                            <?php _e('Learn More', 'golden-globe'); ?> <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-services-list { background-color: var(--color-gray-50); }
.service-card-item { 
    background: var(--color-white); padding: 2.5rem; border-radius: var(--radius-lg); 
    border: 1px solid var(--color-gray-200); transition: all var(--transition-normal);
}
.service-card-item:hover { border-color: var(--color-primary); box-shadow: var(--shadow-lg); transform: translateY(-5px); }
.service-card-item__icon { 
    width: 48px; height: 48px; color: var(--color-primary); margin-bottom: 1.5rem;
}
.service-card-item__icon svg { width: 100%; height: 100%; }
.service-card-item__title { margin-bottom: 0.5rem; font-size: 1.5rem; }
.service-card-item__tagline { font-weight: 600; font-size: 0.875rem; color: var(--color-primary); margin-bottom: 1rem; text-transform: uppercase; }
.service-card-item__excerpt { color: var(--color-gray-600); margin-bottom: 1.5rem; line-height: 1.6; }
.service-card-item__link { font-weight: 700; color: var(--color-black); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.875rem; }
.service-card-item__link:hover { color: var(--color-primary); }
</style>
