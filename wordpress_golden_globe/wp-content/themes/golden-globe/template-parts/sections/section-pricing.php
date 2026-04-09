<?php
/**
 * Section: Pricing Tables
 */
$title = agency_get_sub_field('title') ?: __('Transparent Pricing', 'golden-globe');
?>

<section class="section section-pricing">
    <div class="container">
        <?php if ($title) : ?>
            <div class="section-header text-center reveal-fade">
                <span class="eyebrow"><?php _e('Investment', 'golden-globe'); ?></span>
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            </div>
        <?php endif; ?>

        <?php if (agency_have_rows('plans')) : ?>
            <div class="grid grid--3">
                <?php while (agency_have_rows('plans')) : agency_the_row(); 
                    $name       = agency_get_sub_field('name');
                    $price      = agency_get_sub_field('price');
                    $period     = agency_get_sub_field('period') ?: '/mo';
                    $is_popular = agency_get_sub_field('is_popular');
                    $button     = agency_get_sub_field('button');
                ?>
                    <div class="pricing-card <?php echo $is_popular ? 'pricing-card--popular' : ''; ?> reveal-fade">
                        <?php if ($is_popular) : ?>
                            <div class="pricing-card__badge"><?php _e('Most Popular', 'golden-globe'); ?></div>
                        <?php endif; ?>

                        <div class="pricing-card__header">
                            <h3 class="pricing-card__name"><?php echo esc_html($name); ?></h3>
                            <div class="pricing-card__price">
                                <span class="amount"><?php echo esc_html($price); ?></span>
                                <span class="period"><?php echo esc_html($period); ?></span>
                            </div>
                        </div>

                        <div class="pricing-card__body">
                            <?php if (agency_have_rows('features')) : ?>
                                <ul class="pricing-features">
                                    <?php while (agency_have_rows('features')) : agency_the_row(); ?>
                                        <li>
                                            <span class="pricing-features__icon"><?php agency_icon('check'); ?></span>
                                            <?php echo esc_html(agency_get_sub_field('text')); ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <?php if ($button) : ?>
                            <div class="pricing-card__footer">
                                <a href="<?php echo esc_url($button['url']); ?>" class="agency-btn <?php echo $is_popular ? 'agency-btn--primary' : 'agency-btn--secondary'; ?> agency-btn--full">
                                    <?php echo esc_html($button['title']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-pricing { background-color: var(--color-gray-50); }
.pricing-card { 
    background: var(--color-white); padding: 3rem 2rem; border-radius: var(--radius-xl); 
    border: 1px solid var(--color-gray-200); position: relative; display: flex; flex-direction: column;
}
.pricing-card--popular { border-color: var(--color-primary); box-shadow: var(--shadow-xl); transform: scale(1.05); z-index: 2; }
.pricing-card__badge { 
    position: absolute; top: 0; left: 50%; transform: translate(-50%, -50%); 
    background: var(--color-accent); color: white; padding: 4px 16px; 
    border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
}
.pricing-card__header { text-align: center; margin-bottom: 2.5rem; }
.pricing-card__name { font-size: 1.25rem; color: var(--color-gray-500); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; }
.pricing-card__price .amount { font-size: 3.5rem; font-weight: 900; color: var(--color-black); }
.pricing-card__price .period { font-size: 1rem; color: var(--color-gray-400); }

.pricing-card__body { flex-grow: 1; margin-bottom: 2.5rem; }
.pricing-features { list-style: none; padding: 0; margin: 0; }
.pricing-features li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1rem; color: var(--color-gray-600); }
.pricing-features__icon { color: var(--color-success); width: 18px; height: 18px; margin-top: 2px; }
.pricing-features__icon svg { width: 100%; height: 100%; }

.agency-btn--full { width: 100%; justify-content: center; }

@media (max-width: 991px) {
    .pricing-card--popular { transform: none; margin: 2rem 0; }
}
</style>
