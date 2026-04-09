<?php
/**
 * Location — Process Steps (How it Works)
 *
 * @package GoldenGlobe
 * @var array $args  city, steps (array of [step_icon, step_title, step_body])
 */
defined('ABSPATH') || exit;

$city  = $args['city']  ?? '';
$steps = $args['steps'] ?? [];

if (empty($steps)) return;
?>
<section class="loc-process" aria-labelledby="loc-process-heading">
    <div class="container">

        <header class="loc-process__header">
            <span class="loc-process__eyebrow"><?php esc_html_e('Simple Process', 'golden-globe'); ?></span>
            <h2 id="loc-process-heading" class="loc-process__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('How Every %s Job Gets Handled', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
        </header>

        <ol class="loc-process__list">
            <?php foreach ($steps as $i => $step) :
                $icon  = !empty($step['step_icon'])  ? $step['step_icon']  : (string)($i + 1);
                $title = !empty($step['step_title']) ? $step['step_title'] : '';
                $body  = !empty($step['step_body'])  ? $step['step_body']  : '';
                ?>
                <li class="loc-process__step">
                    <div class="loc-process__step-num" aria-hidden="true">
                        <?php echo esc_html($icon); ?>
                    </div>
                    <div class="loc-process__step-body">
                        <?php if ($title) : ?>
                            <h3 class="loc-process__step-title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                        <?php if ($body) : ?>
                            <p class="loc-process__step-text"><?php echo esc_html($body); ?></p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
