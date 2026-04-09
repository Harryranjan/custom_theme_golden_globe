<?php
/**
 * Location — Bottom CTA Banner
 *
 * @package GoldenGlobe
 * @var array $args  city, state, phone, review_count, review_score
 */
defined('ABSPATH') || exit;

$city         = $args['city']         ?? '';
$state        = $args['state']        ?? '';
$phone        = $args['phone']        ?? '';
$review_count = (int) ($args['review_count'] ?? 0);
$review_score = $args['review_score'] ?? '4.9';

$phone_display = $phone ? esc_html($phone) : '';
$phone_href    = $phone ? esc_url('tel:' . preg_replace('/[^0-9+]/', '', $phone)) : '#';
$location_name = ($city && $state) ? "{$city}, {$state}" : get_the_title();
?>
<section class="loc-cta-banner" aria-labelledby="loc-cta-heading">
    <div class="container">

        <div class="loc-cta-banner__inner">

            <div class="loc-cta-banner__text">
                <h2 id="loc-cta-heading" class="loc-cta-banner__title">
                    <?php
                    printf(
                        /* translators: %s: "City, State" */
                        esc_html__('Ready to Get Started in %s?', 'golden-globe'),
                        esc_html($location_name)
                    );
                    ?>
                </h2>
                <p class="loc-cta-banner__sub">
                    <?php esc_html_e('Contact us for a free, no-obligation on-site estimate. Fast local response guaranteed.', 'golden-globe'); ?>
                </p>

                <?php if ($review_count > 0) : ?>
                    <p class="loc-cta-banner__reviews" aria-label="<?php printf(esc_attr__('%1$s out of 5 stars based on %2$s reviews', 'golden-globe'), esc_attr($review_score), esc_attr($review_count)); ?>">
                        <span aria-hidden="true">★</span>
                        <?php printf(
                            esc_html__('%1$s stars · %2$s Google Reviews · Verified', 'golden-globe'),
                            esc_html($review_score),
                            esc_html(number_format_i18n($review_count))
                        ); ?>
                    </p>
                <?php endif; ?>

                <ul class="loc-cta-banner__badges" aria-label="<?php esc_attr_e('Key trust signals', 'golden-globe'); ?>">
                    <li><span aria-hidden="true">✓</span> <?php esc_html_e('Licensed &amp; Insured', 'golden-globe'); ?></li>
                    <li><span aria-hidden="true">✓</span> <?php esc_html_e('Free Estimates',         'golden-globe'); ?></li>
                    <li><span aria-hidden="true">⚡</span> <?php esc_html_e('Same-Day Available',    'golden-globe'); ?></li>
                </ul>
            </div><!-- .loc-cta-banner__text -->

            <div class="loc-cta-banner__actions">
                <?php if ($phone_display) : ?>
                    <a href="<?php echo $phone_href; ?>" class="btn btn--primary loc-cta-banner__btn-call">
                        <span aria-hidden="true">📞</span>
                        <?php printf(esc_html__('Call %s', 'golden-globe'), $phone_display); ?>
                    </a>
                <?php endif; ?>
                <a href="#contact" class="btn btn--outline loc-cta-banner__btn-quote">
                    <span aria-hidden="true">📋</span>