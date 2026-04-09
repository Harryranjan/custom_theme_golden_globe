<?php
/**
 * Location — Local Intro / About section
 *
 * @package GoldenGlobe
 * @var array $args  city, state, intro_html (already wp_kses'd from ACF wysiwyg)
 */
defined('ABSPATH') || exit;

$city       = $args['city']       ?? '';
$state      = $args['state']      ?? '';
$intro_html = $args['intro_html'] ?? '';

if (!$intro_html) return;
?>
<section class="loc-about" aria-labelledby="loc-about-heading">
    <div class="container">
        <div class="loc-about__inner">

            <header class="loc-about__header">
                <span class="loc-about__eyebrow"><?php esc_html_e('About Our Service', 'golden-globe'); ?></span>
                <h2 id="loc-about-heading" class="loc-about__title">
                    <?php
                    printf(
                        /* translators: %s: "City, State" */
                        esc_html__('Professional Services in %s', 'golden-globe'),
                        esc_html(($city && $state) ? "{$city}, {$state}" : get_the_title())
                    );
                    ?>
                </h2>
            </header>

            <div class="loc-about__body wysiwyg-content">
                <?php
                // Already sanitized via ACF — output as-is (ACF escapes on save)
                echo wp_kses_post($intro_html);
                ?>
            </div>

        </div>
    </div>
</section>
