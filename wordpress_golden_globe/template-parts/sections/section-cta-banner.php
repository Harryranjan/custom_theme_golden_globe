<?php
/**
 * Section: CTA Banner
 */
$title    = agency_get_sub_field('title');
$text     = agency_get_sub_field('text');
$button   = agency_get_sub_field('button');
$bg_color = agency_get_sub_field('bg_color') ?: 'var(--color-primary)';
?>

<section class="section section-cta-banner reveal-fade" style="background-color: <?php echo esc_attr($bg_color); ?>;">
    <div class="container text-center">
        <h2 class="cta-banner__title"><?php echo esc_html($title); ?></h2>
        <?php if ($text) : ?>
            <p class="cta-banner__text"><?php echo esc_html($text); ?></p>
        <?php endif; ?>
        <?php if ($button) : ?>
            <a href="<?php echo esc_url($button['url']); ?>" 
               class="agency-btn agency-btn--white agency-btn--lg"
               <?php echo $button['target'] === '_blank' ? 'target="_blank" rel="noopener"' : ''; ?>>
                <?php echo esc_html($button['title']); ?>
            </a>
        <?php endif; ?>
    </div>
</section>

<style>
.section-cta-banner { padding: 80px 0; border-radius: var(--radius-xl); margin: 60px auto; max-width: var(--container-max); }
.cta-banner__title { color: white; font-size: 3rem; margin-bottom: 1.5rem; }
.cta-banner__text { color: rgba(255,255,255,0.9); font-size: 1.25rem; margin-bottom: 2.5rem; max-width: 700px; margin-inline: auto; }
.agency-btn--white { background: white; color: var(--color-primary); }
.agency-btn--white:hover { background: var(--color-gray-100); transform: translateY(-3px); }

@media (max-width: 768px) {
    .section-cta-banner { border-radius: 0; margin: 40px 0; }
    .cta-banner__title { font-size: 2.25rem; }
}
</style>
