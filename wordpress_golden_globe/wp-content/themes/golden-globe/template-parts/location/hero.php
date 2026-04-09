<?php
/**
 * Location Hero section
 *
 * @package GoldenGlobe
 * @var array $args  style (dark|light|gradient), city, state, tagline, phone,
 *                   review_count, review_score, hero_bg
 */
defined('ABSPATH') || exit;

$style        = $args['style']        ?? 'dark';
$city         = $args['city']         ?? '';
$state        = $args['state']        ?? '';
$tagline      = $args['tagline']      ?? '';
$phone        = $args['phone']        ?? '';
$review_count = (int) ($args['review_count'] ?? 0);
$review_score = $args['review_score'] ?? '4.9';
$hero_bg      = $args['hero_bg']      ?? [];

$full_location = $city && $state ? esc_html("{$city}, {$state}") : esc_html(get_the_title());
$bg_url        = is_array($hero_bg) ? esc_url($hero_bg['url'] ?? '') : esc_url($hero_bg);
$bg_alt        = is_array($hero_bg) ? esc_attr($hero_bg['alt'] ?? "Tree service in {$city}") : esc_attr("Tree service in {$city}");
$phone_display = $phone ? esc_html($phone) : '';
$phone_href    = $phone ? esc_url('tel:' . preg_replace('/[^0-9+]/', '', $phone)) : '#';
?>
<section class="loc-hero loc-hero--<?php echo esc_attr($style); ?>"
         aria-labelledby="loc-hero-heading"
    <?php if ($bg_url) : ?>
         style="--loc-hero-bg: url('<?php echo $bg_url; ?>')"
    <?php endif; ?>>

    <?php if ($bg_url) : ?>
        <div class="loc-hero__overlay" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="container">
        <div class="loc-hero__inner">

            <!-- Text column -->
            <div class="loc-hero__text">

                <?php if ($review_count > 0) : ?>
                    <p class="loc-hero__rating" aria-label="<?php printf(esc_attr__('%1$s out of 5 stars, %2$s reviews', 'golden-globe'), esc_attr($review_score), esc_attr($review_count)); ?>">
                        <span class="loc-hero__stars" aria-hidden="true">★★★★★</span>
                        <span class="loc-hero__rating-score"><?php echo esc_html($review_score); ?></span>
                        <span class="loc-hero__rating-count"><?php printf(esc_html__('%s Five-Star Reviews', 'golden-globe'), esc_html(number_format_i18n($review_count))); ?></span>
                    </p>
                <?php endif; ?>

                <h1 id="loc-hero-heading" class="loc-hero__heading">
                    <?php
                    printf(
                        /* translators: %s: "City, State" */
                        esc_html__('Professional Services in %s', 'golden-globe'),
                        '<span class="loc-hero__city">' . $full_location . '</span>'
                    );
                    ?>
                </h1>

                <?php if ($tagline) : ?>
                    <p class="loc-hero__tagline"><?php echo esc_html($tagline); ?></p>
                <?php endif; ?>

                <ul class="loc-hero__badges" aria-label="<?php esc_attr_e('Key trust signals', 'golden-globe'); ?>">
                    <li><span aria-hidden="true">✓</span> <?php esc_html_e('Licensed &amp; Insured', 'golden-globe'); ?></li>
                    <li><span aria-hidden="true">✓</span> <?php esc_html_e('Free Estimates', 'golden-globe'); ?></li>
                    <li><span aria-hidden="true">⚡</span> <?php esc_html_e('Fast Local Response', 'golden-globe'); ?></li>
                    <li><span aria-hidden="true">🏅</span> <?php esc_html_e('Certified Specialists', 'golden-globe'); ?></li>
                </ul>

                <div class="loc-hero__ctas">
                    <?php if ($phone_display) : ?>
                        <a href="<?php echo $phone_href; ?>" class="btn btn--primary loc-hero__cta-call">
                            <span aria-hidden="true">📞</span>
                            <?php printf(esc_html__('Call %s', 'golden-globe'), $phone_display); ?>
                        </a>
                    <?php endif; ?>
                    <a href="#contact" class="btn btn--outline loc-hero__cta-quote">
                        <span aria-hidden="true">📋</span>
                        <?php esc_html_e('Free Estimate', 'golden-globe'); ?>
                    </a>
                </div>

            </div><!-- .loc-hero__text -->

            <!-- Estimate form column -->
            <div id="contact" class="loc-hero__form-wrap">
                <div class="loc-hero__form">
                    <h2 class="loc-hero__form-title">
                        <?php
                        printf(
                            /* translators: %s: city name */
                            esc_html__('Get Your Free Estimate in %s', 'golden-globe'),
                            esc_html($city ?: get_the_title())
                        );
                        ?>
                    </h2>
                    <p class="loc-hero__form-sub"><?php esc_html_e('No obligation. Free site review. Talk with a specialist today.', 'golden-globe'); ?></p>
                    <?php
                    // Output contact form — uses CF7 if available, otherwise a basic HTML form
                    if (function_exists('wpcf7_enqueue_scripts') && ($cf7_id = get_option('golden_globe_cf7_estimate_id'))) {
                        echo do_shortcode('[contact-form-7 id="' . absint($cf7_id) . '"]');
                    } else {
                        // Fallback basic form — posts to wp-admin/admin-post.php
                        ?>
                        <form class="loc-estimate-form"
                              method="post"
                              action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('loc_estimate_request', 'loc_estimate_nonce'); ?>
                            <input type="hidden" name="action"        value="loc_estimate_request">
                            <input type="hidden" name="location_id"   value="<?php echo esc_attr(get_the_ID()); ?>">
                            <input type="hidden" name="location_city" value="<?php echo esc_attr($city); ?>">

                            <label for="est-name-<?php echo esc_attr(get_the_ID()); ?>" class="sr-only"><?php esc_html_e('Your Name', 'golden-globe'); ?></label>
                            <input id="est-name-<?php echo esc_attr(get_the_ID()); ?>"
                                   type="text" name="est_name"
                                   placeholder="<?php esc_attr_e('Your Name', 'golden-globe'); ?>"
                                   class="loc-estimate-form__input" required>

                            <label for="est-phone-<?php echo esc_attr(get_the_ID()); ?>" class="sr-only"><?php esc_html_e('Phone Number', 'golden-globe'); ?></label>
                            <input id="est-phone-<?php echo esc_attr(get_the_ID()); ?>"
                                   type="tel" name="est_phone"
                                   placeholder="<?php esc_attr_e('Phone Number', 'golden-globe'); ?>"
                                   class="loc-estimate-form__input" required>

                            <label for="est-email-<?php echo esc_attr(get_the_ID()); ?>" class="sr-only"><?php esc_html_e('Email Address', 'golden-globe'); ?></label>
                            <input id="est-email-<?php echo esc_attr(get_the_ID()); ?>"
                                   type="email" name="est_email"
                                   placeholder="<?php esc_attr_e('Email Address', 'golden-globe'); ?>"
                                   class="loc-estimate-form__input" required>

                            <label for="est-message-<?php echo esc_attr(get_the_ID()); ?>" class="sr-only"><?php esc_html_e('Describe the job', 'golden-globe'); ?></label>
                            <textarea id="est-message-<?php echo esc_attr(get_the_ID()); ?>"
                                      name="est_message" rows="3"
                                      placeholder="<?php esc_attr_e('Briefly describe the job…', 'golden-globe'); ?>"
                                      class="loc-estimate-form__textarea"></textarea>

                            <button type="submit" class="btn btn--primary loc-estimate-form__submit">
                                <?php esc_html_e('Request Free Estimate', 'golden-globe'); ?>
                            </button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div><!-- .loc-hero__form-wrap -->

        </div><!-- .loc-hero__inner -->
    </div><!-- .container -->

    <!-- Decorative bottom wave -->
    <div class="loc-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,32 C360,56 1080,0 1440,32 L1440,56 L0,56 Z" fill="currentColor"/>
        </svg>
    </div>

</section>
