<?php
/**
 * Location — Educational Deep-Dive Section (long-form local SEO content)
 *
 * @package GoldenGlobe
 * @var array $args  city, deep_dive_title, deep_dive_html
 */
defined('ABSPATH') || exit;

$city            = $args['city']            ?? '';
$deep_dive_title = $args['deep_dive_title'] ?? '';
$deep_dive_html  = $args['deep_dive_html']  ?? '';

if (!$deep_dive_html) return;
?>
<section class="loc-deep-dive" aria-labelledby="loc-dd-heading">
    <div class="container">

        <?php if ($deep_dive_title) : ?>
            <header class="loc-deep-dive__header">
                <h2 id="loc-dd-heading" class="loc-deep-dive__title">
                    <?php echo esc_html($deep_dive_title); ?>
                </h2>
                <?php if ($city) : ?>
                    <p class="loc-deep-dive__location-tag">
                        <?php printf(esc_html__('Serving %s', 'golden-globe'), esc_html($city)); ?>
                    </p>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <div class="loc-deep-dive__content wysiwyg-content">
            <?php echo wp_kses_post($deep_dive_html); ?>
        </div>

    </div>
</section>
