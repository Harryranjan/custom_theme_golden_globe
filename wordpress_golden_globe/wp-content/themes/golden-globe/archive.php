<?php
defined('ABSPATH') || exit;
get_header();

// ── Archive meta ──────────────────────────────────────────────────────────────
$archive_title       = '';
$archive_description = '';
$archive_type        = '';   // 'category' | 'tag' | 'author' | 'date' | 'post_type'
$term_image          = null; // optional term image (ACF)

if (is_category()) {
    $archive_type        = 'category';
    $term                = get_queried_object();
    $archive_title       = single_term_title('', false);
    $archive_description = term_description();
    if (function_exists('get_field')) {
        $term_image = agency_get_field('term_image', $term);
    }

} elseif (is_tag()) {
    $archive_type        = 'tag';
    $archive_title       = '#' . single_term_title('', false);
    $archive_description = term_description();

} elseif (is_author()) {
    $archive_type        = 'author';
    $author              = get_queried_object();
    $archive_title       = $author->display_name;
    $archive_description = get_the_author_meta('description', $author->ID);

} elseif (is_date()) {
    $archive_type  = 'date';
    if (is_day())   $archive_title = get_the_date();
    elseif (is_month()) $archive_title = get_the_date('F Y');
    else            $archive_title = get_the_date('Y');

} elseif (is_post_type_archive()) {
    $archive_type        = 'post_type';
    $archive_title       = post_type_archive_title('', false);
    $archive_description = get_the_archive_description();

} else {
    $archive_title       = get_the_archive_title();
    $archive_description = get_the_archive_description();
}
?>

<main id="main-content" class="site-main">

    <!-- ── ARCHIVE BANNER ── -->
    <header class="archive-banner<?php echo $archive_type === 'author' ? ' archive-banner--author' : ''; ?>">
        <div class="container">

            <?php if ($archive_type === 'author') : ?>
                <div class="archive-banner__author">
                    <?php echo get_avatar($author->ID, 80, '', esc_attr($author->display_name), ['class' => 'archive-banner__avatar']); ?>
                    <div class="archive-banner__author-info">
                        <p class="archive-banner__label"><?php esc_html_e('Posts by', 'golden-globe'); ?></p>
                        <h1 class="archive-banner__title"><?php echo esc_html($archive_title); ?></h1>
                        <?php if ($archive_description) : ?>
                            <p class="archive-banner__desc"><?php echo esc_html(wp_strip_all_tags($archive_description)); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else : ?>

                <?php if ($archive_type === 'category') : ?>
                    <p class="archive-banner__label"><?php esc_html_e('Category', 'golden-globe'); ?></p>
                <?php elseif ($archive_type === 'tag') : ?>
                    <p class="archive-banner__label"><?php esc_html_e('Tag', 'golden-globe'); ?></p>
                <?php elseif ($archive_type === 'date') : ?>
                    <p class="archive-banner__label"><?php esc_html_e('Archive', 'golden-globe'); ?></p>
                <?php endif; ?>

                <h1 class="archive-banner__title"><?php echo esc_html($archive_title); ?></h1>

                <?php if ($archive_description) : ?>
                    <div class="archive-banner__desc">
                        <?php echo wp_kses_post($archive_description); ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <!-- Post count -->
            <?php global $wp_query; ?>
            <p class="archive-banner__count">
                <?php
                $count = (int) $wp_query->found_posts;
                printf(
                    esc_html(_n('%s post', '%s posts', $count, 'golden-globe')),
                    '<strong>' . esc_html(number_format_i18n($count)) . '</strong>'
                );
                ?>
            </p>

        </div>
    </header>

    <!-- ── POST GRID ── -->
    <div class="container archive-container">

        <?php if (have_posts()) : ?>

            <div class="grid grid--3">
                <?php while (have_posts()) : the_post();
                    $cats = get_the_category(get_the_ID());
                    get_template_part('template-parts/components/card', null, [
                        'post_id'    => get_the_ID(),
                        'title'      => get_the_title(),
                        'permalink'  => get_permalink(),
                        'excerpt'    => get_the_excerpt(),
                        'thumbnail'  => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                        'categories' => $cats ?: [],
                    ]);
                endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="archive-pagination" aria-label="<?php esc_attr_e('Archive pages', 'golden-globe'); ?>">
                <?php the_posts_pagination([
                    'mid_size'           => 2,
                    'prev_text'          => '<span aria-hidden="true">&larr;</span> ' . __('Newer', 'golden-globe'),
                    'next_text'          => __('Older', 'golden-globe') . ' <span aria-hidden="true">&rarr;</span>',
                    'screen_reader_text' => __('Posts navigation', 'golden-globe'),
                ]); ?>
            </nav>

        <?php else : ?>

            <div class="archive-empty">
                <p class="archive-empty__message">
                    <?php esc_html_e('No posts found in this archive.', 'golden-globe'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                    <?php esc_html_e('Back to Homepage', 'golden-globe'); ?>
                </a>
            </div>

        <?php endif; ?>

    </div><!-- /.archive-container -->

</main>

<?php get_footer(); ?>

