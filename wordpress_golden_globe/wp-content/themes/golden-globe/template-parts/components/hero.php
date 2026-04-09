<?php
defined('ABSPATH') || exit;

$heading    = $args['hero_heading']    ?? '';
$subheading = $args['hero_subheading'] ?? '';
$image      = $args['hero_image']      ?? null;
$btn_label  = $args['hero_btn_label']  ?? '';
$btn_url    = $args['hero_btn_url']    ?? '';
$layout     = $args['hero_layout']     ?? 'centered';
?>
<section class="hero hero--<?php echo esc_attr($layout); ?>">
    <?php if (!empty($image['url'])) : ?>
        <img class="hero__bg"
             src="<?php echo esc_url($image['url']); ?>"
             alt="<?php echo esc_attr($image['alt'] ?? ''); ?>"
             loading="eager"
             fetchpriority="high">
    <?php endif; ?>

    <div class="container">
        <div class="hero__content">
            <?php if ($heading) : ?>
                <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
            <?php endif; ?>

            <?php if ($subheading) : ?>
                <p class="hero__subheading"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>

            <?php if ($btn_label && $btn_url) : ?>
                <a href="<?php echo esc_url($btn_url); ?>" class="btn btn--primary btn--lg">
                    <?php echo esc_html($btn_label); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
