<?php
/**
 * Location — FAQ Accordion
 *
 * @package GoldenGlobe
 * @var array $args  city, faq_items (repeater rows: faq_question, faq_answer)
 */
defined('ABSPATH') || exit;

$city      = $args['city']      ?? '';
$faq_items = $args['faq_items'] ?? [];

if (empty($faq_items)) return;

$post_id = get_the_ID();
?>
<section class="loc-faq" aria-labelledby="loc-faq-heading">
    <div class="container">

        <header class="loc-faq__header">
            <span class="loc-faq__eyebrow"><?php esc_html_e('FAQ', 'golden-globe'); ?></span>
            <h2 id="loc-faq-heading" class="loc-faq__title">
                <?php
                printf(
                    /* translators: %s: city name */
                    esc_html__('Common Questions — %s', 'golden-globe'),
                    esc_html($city ?: get_the_title())
                );
                ?>
            </h2>
        </header>

        <!-- Schema.org FAQPage markup -->
        <div class="loc-faq__list" itemscope itemtype="https://schema.org/FAQPage">

            <?php foreach ($faq_items as $i => $item) :
                $question = $item['faq_question'] ?? '';
                $answer   = $item['faq_answer']   ?? '';
                if (!$question || !$answer) continue;
                $item_id = "loc-faq-{$post_id}-{$i}";
                ?>
                <div class="loc-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <h3 class="loc-faq__question" itemprop="name">
                        <button class="loc-faq__toggle"
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr($item_id); ?>">
                            <?php echo esc_html($question); ?>
                            <span class="loc-faq__chevron" aria-hidden="true"></span>
                        </button>
                    </h3>
                    <div class="loc-faq__answer"
                         id="<?php echo esc_attr($item_id); ?>"
                         role="region"
                         aria-labelledby="<?php echo esc_attr("{$item_id}-btn"); ?>"
                         hidden
                         itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div class="loc-faq__answer-inner" itemprop="text">
                            <?php echo wp_kses_post(wpautop($answer)); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div><!-- .loc-faq__list -->

    </div><!-- .container -->
</section>

<script>
(function () {
    document.querySelectorAll('.loc-faq__toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            var panel    = document.getElementById(this.getAttribute('aria-controls'));
            this.setAttribute('aria-expanded', String(!expanded));
            if (panel) {
                panel.hidden = expanded;
            }
        });
    });
}());
</script>
