<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    $has_thumb   = has_post_thumbnail();
    $thumb_url   = $has_thumb ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
    $parent_id   = wp_get_post_parent_id(get_the_ID());
    $parent_page = $parent_id ? get_post($parent_id) : null;

    // Gather sibling pages for prev/next navigation
    $siblings = [];
    if ($parent_id) {
        $siblings = get_pages([
            'parent'      => $parent_id,
            'sort_column' => 'menu_order',
            'sort_order'  => 'ASC',
        ]);
    }

    // Find position of current page within siblings
    $current_index = -1;
    foreach ($siblings as $i => $s) {
        if ((int) $s->ID === get_the_ID()) {
            $current_index = $i;
            break;
        }
    }
    $prev_sibling = ($current_index > 0) ? $siblings[$current_index - 1] : null;
    $next_sibling = ($current_index >= 0 && isset($siblings[$current_index + 1])) ? $siblings[$current_index + 1] : null;
?>

<main id="main-content" class="site-main">

    <!-- ── PAGE HERO (featured image banner) ── -->
    <?php if ($has_thumb) : ?>
        <div class="page-hero" style="background-image:url('<?php echo esc_url($thumb_url); ?>')">
            <div class="page-hero__overlay"></div>
            <div class="container page-hero__inner">

                <!-- Breadcrumb -->
                <nav class="page-hero__breadcrumb breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                    <ol class="breadcrumbs__list">
                        <li class="breadcrumbs__item">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a>
                        </li>
                        <?php if ($parent_page) : ?>
                            <li class="breadcrumbs__item">
                                <a href="<?php echo esc_url(get_permalink($parent_page->ID)); ?>">
                                    <?php echo esc_html($parent_page->post_title); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="breadcrumbs__item" aria-current="page">
                            <?php the_title(); ?>
                        </li>
                    </ol>
                </nav>

                <h1 class="page-hero__title"><?php the_title(); ?></h1>

            </div>
        </div>

    <?php else : ?>

        <!-- ── PAGE BANNER (no featured image) ── -->
        <header class="page-banner">
            <div class="container">

                <!-- Breadcrumb -->
                <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                    <ol class="breadcrumbs__list">
                        <li class="breadcrumbs__item">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a>
                        </li>
                        <?php if ($parent_page) : ?>
                            <li class="breadcrumbs__item">
                                <a href="<?php echo esc_url(get_permalink($parent_page->ID)); ?>">
                                    <?php echo esc_html($parent_page->post_title); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="breadcrumbs__item" aria-current="page">
                            <?php the_title(); ?>
                        </li>
                    </ol>
                </nav>

                <h1 class="page-banner__title"><?php the_title(); ?></h1>

            </div>
        </header>

    <?php endif; ?>

    <!-- ── PAGE CONTENT ── -->
    <div class="container page-layout">

        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <!-- Link pagination (<!--nextpage--> tags) -->
            <?php
            wp_link_pages([
                'before'      => '<nav class="page-links" aria-label="' . esc_attr__('Page sections', 'golden-globe') . '"><span class="page-links__label">' . esc_html__('Pages:', 'golden-globe') . '</span>',
                'after'       => '</nav>',
                'link_before' => '<span class="page-links__item">',
                'link_after'  => '</span>',
            ]);
            ?>
        </article>

        <!-- Sibling page navigation -->
        <?php if ($prev_sibling || $next_sibling) : ?>
            <nav class="page-siblings" aria-label="<?php esc_attr_e('More pages', 'golden-globe'); ?>">
                <?php if ($prev_sibling) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev_sibling->ID)); ?>" class="page-siblings__link page-siblings__link--prev">
                        <span class="page-siblings__arrow" aria-hidden="true">&larr;</span>
                        <span class="page-siblings__text">
                            <span class="page-siblings__label"><?php esc_html_e('Previous', 'golden-globe'); ?></span>
                            <span class="page-siblings__title"><?php echo esc_html($prev_sibling->post_title); ?></span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if ($next_sibling) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_sibling->ID)); ?>" class="page-siblings__link page-siblings__link--next">
                        <span class="page-siblings__text">
                            <span class="page-siblings__label"><?php esc_html_e('Next', 'golden-globe'); ?></span>
                            <span class="page-siblings__title"><?php echo esc_html($next_sibling->post_title); ?></span>
                        </span>
                        <span class="page-siblings__arrow" aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div><!-- /.page-layout -->

</main>

<?php endwhile; ?>

<?php get_footer(); ?>

