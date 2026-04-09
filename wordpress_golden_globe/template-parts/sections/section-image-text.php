<?php
/**
 * Section: Image/Text (Split)
 */
$it_pos = agency_get_sub_field('image_position') ?: 'right';
$it_bg  = agency_get_sub_field('bg') ?: 'white';
$it_img = agency_get_sub_field('image');
$it_btn = agency_get_sub_field('button');
$it_title = agency_get_sub_field('title');
$eyebrow = agency_get_sub_field('eyebrow');
?>

<section class="section section-split section-split--<?php echo esc_attr($it_pos); ?> section-split--bg-<?php echo esc_attr($it_bg); ?>">
    <div class="container">
        <div class="split-inner">
            <div class="split-image reveal-fade">
                <?php if ($it_img) : ?>
                    <img src="<?php echo esc_url($it_img['url']); ?>" alt="<?php echo esc_attr($it_img['alt']); ?>" loading="lazy">
                <?php else : ?>
                    <div class="placeholder"></div>
                <?php endif; ?>
            </div>
            <div class="split-content reveal-fade" style="transition-delay: 0.1s;">
                <?php if ($eyebrow) : ?>
                    <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <?php if ($it_title) : ?>
                    <h2 class="split-title"><?php echo esc_html($it_title); ?></h2>
                <?php endif; ?>
                <div class="split-body">
                    <?php echo wp_kses_post(agency_get_sub_field('text')); ?>
                </div>
                <?php if ($it_btn) : ?>
                    <div class="split-actions">
                        <a href="<?php echo esc_url($it_btn['url']); ?>" class="agency-btn agency-btn--primary">
                            <?php echo esc_html($it_btn['title']); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.section-split--bg-gray { background-color: var(--color-gray-50); }
.split-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
.section-split--right .split-image { order: 2; }
.section-split--right .split-content { order: 1; }

.split-image img { border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); width: 100%; height: auto; display: block; }
.split-title { font-size: 2.5rem; margin-bottom: 1.5rem; }
.split-body { font-size: 1.125rem; color: var(--color-gray-600); line-height: 1.8; margin-bottom: 2.5rem; }
.split-body p { margin-bottom: 1.25rem; }

@media (max-width: 991px) {
    .split-inner { grid-template-columns: 1fr; gap: 3rem; text-align: center; }
    .section-split--right .split-image, .section-split--right .split-content { order: unset; }
    .split-title { font-size: 2rem; }
}
</style>
