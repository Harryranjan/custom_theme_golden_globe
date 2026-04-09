<?php
/**
 * The main template file
 */
defined('ABSPATH') || exit;

get_header();
?>
<main id="main-content" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="grid grid--3">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/components/card', null, [
                        'post_id'   => get_the_ID(),
                        'title'     => get_the_title(),
                        'permalink' => get_permalink(),
                        'excerpt'   => get_the_excerpt(),
                        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                    ]); ?>
                <?php endwhile; ?>
            </div>
            <?php get_template_part('template-parts/components/pagination'); ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'golden-globe'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
