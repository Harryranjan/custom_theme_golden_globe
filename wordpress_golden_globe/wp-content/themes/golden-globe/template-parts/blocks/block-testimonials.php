<?php
defined('ABSPATH') || exit;

$title        = agency_get_field('testimonials_title');
$testimonials = agency_get_field('testimonials_items');
?>
<section class="section section--testimonials bg-light">
    <div class="container">
        <?php if ($title) : ?>
            <h2 class="text-center" style="margin-bottom:2rem;"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($testimonials)) : ?>
            <div class="grid grid--3">
                <?php foreach ($testimonials as $item) : ?>
                    <?php agency_render('components/testimonial', [
                        'author'  => $item['author']  ?? '',
                        'content' => $item['content'] ?? '',
                        'role'    => $item['role']    ?? '',
                        'avatar'  => $item['avatar']['url'] ?? '',
                    ]); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
