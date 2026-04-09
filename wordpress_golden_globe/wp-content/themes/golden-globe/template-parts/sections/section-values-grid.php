<?php
/**
 * Section: Core Values Grid
 */
$title = agency_get_sub_field('title') ?: __('Our Principles', 'golden-globe');
$intro = agency_get_sub_field('intro') ?: __('Building the future with expertise and empathy.', 'golden-globe');
?>

<section class="section section-values">
    <div class="container">
        <?php if ($title || $intro) : ?>
            <div class="section-header text-center reveal-fade">
                <span class="eyebrow"><?php _e('Our Foundation', 'golden-globe'); ?></span>
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <p class="section-desc"><?php echo esc_html($intro); ?></p>
            </div>
        <?php endif; ?>

        <?php if (agency_have_rows('values')) : ?>
            <div class="grid grid--3">
                <?php while (agency_have_rows('values')) : agency_the_row(); 
                    $icon_name = agency_get_sub_field('icon_name') ?: 'shield';
                    $name      = agency_get_sub_field('name');
                    $desc      = agency_get_sub_field('desc');
                ?>
                    <div class="value-card reveal-fade">
                        <div class="value-card__icon">
                            <?php agency_icon($icon_name); ?>
                        </div>
                        <h3 class="value-card__title"><?php echo esc_html($name); ?></h3>
                        <p class="value-card__text"><?php echo esc_html($desc); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <!-- Default placeholder values -->
            <div class="grid grid--3 reveal-fade" style="opacity:0.3;">
                <div class="value-card"><div class="value-card__icon"><?php agency_icon('target'); ?></div><h3>Our Mission</h3><p>Example description here.</p></div>
                <div class="value-card"><div class="value-card__icon"><?php agency_icon('eye'); ?></div><h3>Our Vision</h3><p>Example description here.</p></div>
                <div class="value-card"><div class="value-card__icon"><?php agency_icon('star'); ?></div><h3>Our Values</h3><p>Example description here.</p></div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-values { background-color: var(--color-white); }
.value-card { 
    text-align: center; padding: 3rem 2rem; border-radius: var(--radius-xl); 
    background: var(--color-gray-50); transition: all var(--transition-normal); 
    border: 1px solid var(--color-gray-100);
}
.value-card:hover { transform: translateY(-8px); border-color: var(--color-primary); background: white; box-shadow: var(--shadow-xl); }
.value-card__icon { 
    width: 64px; height: 64px; margin: 0 auto 2rem; background: var(--color-primary-light); 
    color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.value-card__icon svg { width: 32px; height: 32px; }
.value-card__title { margin-bottom: 1rem; font-size: 1.5rem; }
.value-card__text { color: var(--color-gray-600); line-height: 1.6; }
</style>
