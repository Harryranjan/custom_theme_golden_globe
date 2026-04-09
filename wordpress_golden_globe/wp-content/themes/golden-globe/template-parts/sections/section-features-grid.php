<?php
/**
 * Section: Features Grid
 */
$title = agency_get_sub_field('title');
?>

<section class="section section-features">
    <div class="container">
        <?php if ($title) : ?>
            <div class="section-header text-center reveal-fade">
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            </div>
        <?php endif; ?>

        <div class="grid grid--3">
            <?php if (agency_have_rows('items')) : ?>
                <?php while (agency_have_rows('items')) : agency_the_row(); 
                    $icon_name = agency_get_sub_field('icon_name') ?: 'star';
                    $name      = agency_get_sub_field('name');
                    $desc      = agency_get_sub_field('desc');
                ?>
                    <div class="feature-card reveal-fade">
                        <div class="feature-card__icon">
                            <?php agency_icon($icon_name); ?>
                        </div>
                        <h3 class="feature-card__title"><?php echo esc_html($name); ?></h3>
                        <p class="feature-card__text"><?php echo esc_html($desc); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.section-features { background-color: var(--color-gray-50); }
.feature-card { 
    background: var(--color-white); padding: 3rem 2rem; border-radius: var(--radius-lg); 
    text-align: center; box-shadow: var(--shadow-sm); transition: transform var(--transition-normal), box-shadow var(--transition-normal);
}
.feature-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
.feature-card__icon { 
    width: 64px; height: 64px; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center;
    background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md);
}
.feature-card__icon svg { width: 32px; height: 32px; }
.feature-card__title { margin-bottom: 1rem; font-size: 1.25rem; }
.feature-card__text { color: var(--color-gray-600); line-height: 1.6; }
</style>
