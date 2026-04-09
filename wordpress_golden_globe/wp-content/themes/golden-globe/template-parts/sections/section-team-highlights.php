<?php
/**
 * Section: Team Highlights
 */
$title = agency_get_sub_field('title') ?: __('Meet the Experts', 'golden-globe');
$count = (int) (agency_get_sub_field('count') ?: 4);

$query = new WP_Query([
    'post_type'      => 'team_member',
    'posts_per_page' => $count,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);
?>

<section class="section section-team-highlights">
    <div class="container">
        <div class="section-header text-center reveal-fade">
            <span class="eyebrow"><?php _e('Our People', 'golden-globe'); ?></span>
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
        </div>

        <?php if ($query->have_posts()) : ?>
            <div class="grid grid--4">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $role = agency_get_field('member_role', get_the_ID());
                ?>
                    <div class="team-card reveal-fade">
                        <div class="team-card__image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('member-thumb'); ?>
                            <?php else : ?>
                                <div class="placeholder"></div>
                            <?php endif; ?>
                            <div class="team-card__overlay">
                                <a href="<?php the_permalink(); ?>" class="agency-btn agency-btn--white agency-btn--sm"><?php _e('View Profile', 'golden-globe'); ?></a>
                            </div>
                        </div>
                        <div class="team-card__info">
                            <h3 class="team-card__name"><?php the_title(); ?></h3>
                            <?php if ($role) : ?>
                                <span class="team-card__role"><?php echo esc_html($role); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-team-highlights { background-color: var(--color-white); }
.team-card { text-align: center; }
.team-card__image { 
    position: relative; border-radius: var(--radius-lg); overflow: hidden; 
    margin-bottom: 1.5rem; aspect-ratio: 4/5; 
}
.team-card__image img { width: 100%; height: 100%; object-fit: cover; transition: transform var(--transition-normal); }
.team-card__overlay { 
    position: absolute; inset: 0; background: rgba(0,0,0,0.4); 
    display: flex; align-items: center; justify-content: center; 
    opacity: 0; transition: opacity var(--transition-normal);
}
.team-card:hover .team-card__image img { transform: scale(1.05); }
.team-card:hover .team-card__overlay { opacity: 1; }

.team-card__name { font-size: 1.25rem; margin-bottom: 0.25rem; }
.team-card__role { color: var(--color-primary); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; }
</style>
