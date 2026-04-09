<?php
/**
 * Location — Trust Badges / Why Choose Us
 *
 * @package GoldenGlobe
 * @var array $args  city
 */
defined('ABSPATH') || exit;

$city = $args['city'] ?? '';

$badges = [
    ['icon' => '🏅', 'title' => __('Certified Specialists',      'golden-globe'), 'body' => __('Our specialists are certified and conduct thorough assessments before recommending any scope of work.',    'golden-globe')],
    ['icon' => '📋', 'title' => __('Full Documentation',          'golden-globe'), 'body' => __('Every job comes with written scope, before-and-after photos, and sign-off documentation on request.',     'golden-globe')],
    ['icon' => '📍', 'title' => __('Locally Rooted Team',         'golden-globe'), 'body' => __('We know the local permit process and which issues are causing the most problems on residential lots right now.', 'golden-globe')],
    ['icon' => '🔒', 'title' => __('Fully Licensed &amp; Insured','golden-globe'), 'body' => __('We carry full liability and workers\' compensation. We provide proof of coverage immediately on request.',  'golden-globe')],
    ['icon' => '🚜', 'title' => __('Right Equipment for the Job', 'golden-globe'), 'body' => __('Compact equipment for tight lots; larger machinery where needed. The right tool is chosen during assessment.', 'golden-globe')],
    ['icon' => '💰', 'title' => __('Transparent Pricing',         'golden-globe'), 'body' => __('What is estimated is what gets invoiced. No unexpected charges appear on your final bill.',                 'golden-globe')],
    ['icon' => '📜', 'title' => __('Permit &amp; Ordinance Knowledge', 'golden-globe'), 'body' => __('We address permit needs before scheduling the crew, ensuring all paperwork is in order on arrival.',   'golden-globe')],
    ['icon' => '⚡', 'title' => __('Fast Emergency Response',     'golden-globe'), 'body' => __('Our emergency crews respond promptly regardless of the hour. Same-day service available in most cases.',     'golden-globe')],
];
?>
<section class="loc-trust" aria-labelledby="loc-trust-heading">
    <div class="container">

        <header class="loc-trust__header">
            <span class="loc-trust__eyebrow"><?php esc_html_e('Why Choose Us', 'golden-globe'); ?></span>
            <h2 id="loc-trust-heading" class="loc-trust__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('Why %s Clients Trust Us', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
            <p class="loc-trust__subtitle"><?php esc_html_e('From our first call to your final walkthrough, we do things differently.', 'golden-globe'); ?></p>
        </header>

        <ul class="loc-trust__grid">
            <?php foreach ($badges as $badge) : ?>
                <li class="loc-trust-card">
                    <span class="loc-trust-card__icon" aria-hidden="true"><?php echo esc_html($badge['icon']); ?></span>
                    <h3 class="loc-trust-card__title"><?php echo wp_kses($badge['title'], ['amp' => []]); ?></h3>
                    <p class="loc-trust-card__body"><?php echo esc_html($badge['body']); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
