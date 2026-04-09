<?php
/**
 * Section: Hero
 */
$heading    = $args['heading']    ?? '';
$subheading = $args['subheading'] ?? '';
$image      = $args['image']      ?? null;
$btn_label  = $args['btn_label']  ?? '';
$btn_url    = $args['btn_url']    ?? '';
$layout     = $args['layout']     ?? 'centered';

$bg_style = '';
if ($image && $layout !== 'split') {
    $bg_style = 'style="background-image: url(' . esc_url($image['url']) . ');"';
}
?>

<section class="hero hero--<?php echo esc_attr($layout); ?>" <?php echo $bg_style; ?>>
    <?php if ($image && $layout !== 'split') : ?>
        <div class="hero__overlay"></div>
    <?php endif; ?>

    <div class="container hero__container">
        <div class="hero__content">
            <?php if ($heading) : ?>
                <h1 class="hero__title reveal-fade"><?php echo esc_html($heading); ?></h1>
            <?php endif; ?>

            <?php if ($subheading) : ?>
                <p class="hero__subtitle reveal-fade" style="transition-delay: 0.1s;"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>

            <?php if ($btn_label && $btn_url) : ?>
                <div class="hero__actions reveal-fade" style="transition-delay: 0.2s;">
                    <a href="<?php echo esc_url($btn_url); ?>" class="agency-btn agency-btn--primary agency-btn--lg">
                        <?php echo esc_html($btn_label); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($layout === 'split' && $image) : ?>
            <div class="hero__image reveal-fade" style="transition-delay: 0.3s;">
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="800" height="600">
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hero { 
    position: relative; padding: 120px 0; min-height: 80vh; display: flex; align-items: center; 
    background-size: cover; background-position: center; overflow: hidden;
}
.hero--centered { text-align: center; }
.hero__overlay { 
    position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.6), rgba(0,0,0,0.4)); 
    z-index: 1;
}
.hero__container { position: relative; z-index: 2; }
.hero--centered .hero__container { display: flex; justify-content: center; }
.hero--centered .hero__content { max-width: 800px; color: var(--color-white); }
.hero--centered .hero__title { color: var(--color-white); font-size: 4rem; margin-bottom: 1.5rem; }
.hero--centered .hero__subtitle { font-size: 1.25rem; opacity: 0.9; margin-bottom: 2.5rem; }

.hero--split .hero__container { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
.hero--split .hero__title { font-size: 3.5rem; margin-bottom: 1.5rem; }
.hero--split .hero__image img { border-radius: var(--radius-lg); box-shadow: var(--shadow-2xl); }

@media (max-width: 991px) {
    .hero--split .hero__container { grid-template-columns: 1fr; text-align: center; }
    .hero--split .hero__image { order: -1; }
    .hero--centered .hero__title { font-size: 3rem; }
}

/* Base Reveal Animation */
.reveal-fade { opacity: 0; transform: translateY(20px); transition: all 0.8s ease-out; }
.reveal-fade.is-visible { opacity: 1; transform: translateY(0); }
</style>
