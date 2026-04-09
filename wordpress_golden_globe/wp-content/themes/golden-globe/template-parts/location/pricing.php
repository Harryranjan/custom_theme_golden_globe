<?php
/**
 * Location — Pricing Table
 *
 * @package GoldenGlobe
 * @var array $args  city, pricing_rows (repeater rows: price_icon, price_label, price_range)
 */
defined('ABSPATH') || exit;

$city         = $args['city']         ?? '';
$pricing_rows = $args['pricing_rows'] ?? [];

if (empty($pricing_rows)) return;
?>
<section class="loc-pricing" aria-labelledby="loc-pricing-heading">
    <div class="container">

        <header class="loc-pricing__header">
            <span class="loc-pricing__eyebrow"><?php esc_html_e('Pricing Guide', 'golden-globe'); ?></span>
            <h2 id="loc-pricing-heading" class="loc-pricing__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('What Work Typically Costs Across %s', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
            <p class="loc-pricing__note"><?php esc_html_e('Costs vary by job size and complexity. These are honest starting-point ranges.', 'golden-globe'); ?></p>
        </header>

        <div class="loc-pricing__table-wrap">
            <table class="loc-pricing__table">
                <thead>
                    <tr>
                        <th class="loc-pricing__col-label" scope="col"><?php esc_html_e('Service', 'golden-globe'); ?></th>
                        <th class="loc-pricing__col-range" scope="col"><?php esc_html_e('Typical Range', 'golden-globe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pricing_rows as $row) :
                        $icon  = $row['price_icon']  ?? '';
                        $label = $row['price_label'] ?? '';
                        $range = $row['price_range'] ?? '';
                        if (!$label) continue;
                        ?>
                        <tr class="loc-pricing__row">
                            <td class="loc-pricing__cell-label">
                                <?php if ($icon) : ?>
                                    <span class="loc-pricing__row-icon" aria-hidden="true"><?php echo esc_html($icon); ?></span>
                                <?php endif; ?>
                                <?php echo esc_html($label); ?>
                            </td>
                            <td class="loc-pricing__cell-range"><?php echo esc_html($range); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="loc-pricing__disclaimer">
            <?php esc_html_e('Commercial and bulk projects are quoted separately. Prices may vary for complex or emergency situations.', 'golden-globe'); ?>
        </p>

        <div class="loc-pricing__cta">
            <a href="#contact" class="btn btn--primary">
                <?php esc_html_e('Get a Free On-Site Estimate', 'golden-globe'); ?>
            </a>
        </div>

    </div>
</section>
