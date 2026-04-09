<?php
defined('ABSPATH') || exit;

$title = agency_get_field('cards_title');
$items = agency_get_field('cards_items');
?>
<section class="section">
    <div class="container">
        <?php if ($title) : ?>
            <h2 class="text-center" style="margin-bottom:2rem;"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($items)) : ?>
            <div class="grid grid--3">
                <?php foreach ($items as $item) : ?>
                    <div class="card">
                        <?php if (!empty($item['image'])) : ?>
                            <img class="card__image"
                                 src="<?php echo esc_url($item['image']['url']); ?>"
                                 alt="<?php echo esc_attr($item['image']['alt']); ?>"
                                 loading="lazy">
                        <?php endif; ?>
                        <div class="card__body">
                            <h3 class="card__title"><?php echo esc_html($item['title']); ?></h3>
                            <p class="card__excerpt"><?php echo esc_html($item['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
