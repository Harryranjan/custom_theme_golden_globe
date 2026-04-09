<?php
defined('ABSPATH') || exit;

$title    = $args['title']    ?? '';
$text     = $args['text']     ?? '';
$button   = $args['button']   ?? null;
$bg_color = $args['bg_color'] ?? '#2563eb';
?>
<section class="section section--cta" style="background-color:<?php echo esc_attr($bg_color); ?>">
    <div class="container container--narrow" style="text-align:center;">
        <?php if ($title) : ?>
            <h2 style="color:#fff; margin-bottom:1rem;"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($text) : ?>
            <p style="color:rgba(255,255,255,0.85); margin-bottom:2rem; font-size:1.125rem;"><?php echo esc_html($text); ?></p>
        <?php endif; ?>

        <?php if (!empty($button['url'])) : ?>
            <a href="<?php echo esc_url($button['url']); ?>"
               class="btn btn--white btn--lg"
               <?php echo !empty($button['target']) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                <?php echo esc_html($button['title']); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
