<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    $post_id    = get_the_ID();
    $has_thumb  = has_post_thumbnail();
    $thumb_url  = $has_thumb ? get_the_post_thumbnail_url($post_id, 'full') : '';

    // Taxonomy terms
    $portfolio_cats = get_the_terms($post_id, 'portfolio_cat') ?: [];
    $industries     = get_the_terms($post_id, 'industry')      ?: [];

    // ACF fields (with native fallbacks)
    if (function_exists('get_field')) {
        $client        = agency_get_field('project_client',   $post_id);
        $year          = agency_get_field('project_year',     $post_id);
        $services_used = agency_get_field('project_services', $post_id);
        $website_url   = agency_get_field('project_url',      $post_id);
        $gallery       = agency_get_field('project_gallery',  $post_id);
        $challenge     = agency_get_field('project_challenge', $post_id);
        $result        = agency_get_field('project_result',   $post_id);
    } else {
        $client        = get_post_meta($post_id, 'project_client',   true);
        $year          = get_post_meta($post_id, 'project_year',     true);
        $services_used = get_post_meta($post_id, 'project_services', true);
        $website_url   = get_post_meta($post_id, 'project_url',      true);
        $gallery       = null;
        $challenge     = get_post_meta($post_id, 'project_challenge', true);
        $result        = get_post_meta($post_id, 'project_result',   true);
    }

    // Prev / next within portfolio CPT
    $prev_project = get_previous_post(false, '', 'portfolio_cat');
    $next_project = get_next_post(false, '', 'portfolio_cat');
?>

<main id="main-content" class="site-main">

    <!-- ── PROJECT HERO ── -->
    <header class="project-hero<?php echo $has_thumb ? ' project-hero--image' : ''; ?>"
        <?php if ($has_thumb) : ?>style="background-image:url('<?php echo esc_url($thumb_url); ?>')"<?php endif; ?>>
        <?php if ($has_thumb) : ?>
            <div class="project-hero__overlay"></div>
        <?php endif; ?>
        <div class="container project-hero__inner">

            <!-- Breadcrumb -->
            <nav class="breadcrumbs<?php echo $has_thumb ? ' breadcrumbs--light' : ''; ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>"><?php esc_html_e('Portfolio', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>

            <!-- Category pills -->
            <?php if (!empty($portfolio_cats) && !is_wp_error($portfolio_cats)) : ?>
                <div class="project-hero__cats">
                    <?php foreach ($portfolio_cats as $cat) : ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="tag tag--primary">
                            <?php echo esc_html($cat->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="project-hero__title"><?php the_title(); ?></h1>

            <?php $excerpt = get_the_excerpt(); if ($excerpt) : ?>
                <p class="project-hero__excerpt"><?php echo esc_html($excerpt); ?></p>
            <?php endif; ?>

        </div>
    </header>

    <div class="container project-layout">

        <!-- ── MAIN CONTENT + SIDEBAR ── -->
        <div class="project-columns">

            <!-- Content -->
            <article id="post-<?php the_ID(); ?>" <?php post_class('project-content'); ?>>

                <?php if ($challenge) : ?>
                    <section class="project-section">
                        <h2 class="project-section__title"><?php esc_html_e('The Challenge', 'golden-globe'); ?></h2>
                        <div class="entry-content"><?php echo wp_kses_post($challenge); ?></div>
                    </section>
                <?php endif; ?>

                <?php if (have_posts()) : // the_content() uses the Loop ?>
                    <section class="project-section">
                        <?php if (!$challenge) : ?>
                            <h2 class="project-section__title"><?php esc_html_e('Project Overview', 'golden-globe'); ?></h2>
                        <?php else : ?>
                            <h2 class="project-section__title"><?php esc_html_e('Our Approach', 'golden-globe'); ?></h2>
                        <?php endif; ?>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($result) : ?>
                    <section class="project-section project-section--highlight">
                        <h2 class="project-section__title"><?php esc_html_e('The Result', 'golden-globe'); ?></h2>
                        <div class="entry-content"><?php echo wp_kses_post($result); ?></div>
                    </section>
                <?php endif; ?>

                <!-- Gallery -->
                <?php if (!empty($gallery) && is_array($gallery)) : ?>
                    <section class="project-section">
                        <h2 class="project-section__title"><?php esc_html_e('Project Gallery', 'golden-globe'); ?></h2>
                        <div class="project-gallery">
                            <?php foreach ($gallery as $img) :
                                $img_url   = is_array($img) ? ($img['url'] ?? '') : wp_get_attachment_url($img);
                                $img_alt   = is_array($img) ? ($img['alt'] ?? '') : get_post_meta($img, '_wp_attachment_image_alt', true);
                                $large_url = is_array($img) ? ($img['sizes']['large'] ?? $img_url) : wp_get_attachment_image_url($img, 'large');
                                if (!$img_url) continue;
                            ?>
                                <a href="<?php echo esc_url($img_url); ?>" class="project-gallery__item" data-lightbox="project">
                                    <img src="<?php echo esc_url($large_url); ?>"
                                         alt="<?php echo esc_attr($img_alt); ?>"
                                         loading="lazy">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </article>

            <!-- Sidebar: project details -->
            <aside class="project-sidebar">
                <div class="project-meta-card">
                    <h2 class="project-meta-card__title"><?php esc_html_e('Project Details', 'golden-globe'); ?></h2>

                    <dl class="project-meta-list">

                        <?php if ($client) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Client', 'golden-globe'); ?></dt>
                                <dd><?php echo esc_html($client); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if ($year) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Year', 'golden-globe'); ?></dt>
                                <dd><?php echo esc_html($year); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($portfolio_cats) && !is_wp_error($portfolio_cats)) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Category', 'golden-globe'); ?></dt>
                                <dd>
                                    <?php foreach ($portfolio_cats as $i => $cat) :
                                        echo ($i > 0 ? ', ' : '');
                                    ?><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a><?php
                                    endforeach; ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($industries) && !is_wp_error($industries)) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Industry', 'golden-globe'); ?></dt>
                                <dd>
                                    <?php foreach ($industries as $i => $tag) :
                                        echo ($i > 0 ? ', ' : '');
                                    ?><a href="<?php echo esc_url(get_term_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a><?php
                                    endforeach; ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if ($services_used) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Services', 'golden-globe'); ?></dt>
                                <dd><?php echo esc_html(is_array($services_used) ? implode(', ', $services_used) : $services_used); ?></dd>
                            </div>
                        <?php endif; ?>

                    </dl>

                    <?php if ($website_url) : ?>
                        <a href="<?php echo esc_url($website_url); ?>"
                           class="btn btn--primary btn--full"
                           target="_blank"
                           rel="noopener noreferrer">
                            <?php esc_html_e('Visit Website', 'golden-globe'); ?>
                            <span aria-hidden="true">&nearr;</span>
                        </a>
                    <?php endif; ?>
                </div>
            </aside>

        </div><!-- /.project-columns -->

        <!-- ── PREV / NEXT PROJECTS ── -->
        <?php if ($prev_project || $next_project) : ?>
            <nav class="project-nav" aria-label="<?php esc_attr_e('More projects', 'golden-globe'); ?>">
                <?php if ($prev_project) :
                    $prev_thumb = get_the_post_thumbnail_url($prev_project->ID, 'card-thumb');
                ?>
                    <a href="<?php echo esc_url(get_permalink($prev_project->ID)); ?>" class="project-nav__item project-nav__item--prev">
                        <?php if ($prev_thumb) : ?>
                            <div class="project-nav__thumb" style="background-image:url('<?php echo esc_url($prev_thumb); ?>')"></div>
                        <?php endif; ?>
                        <div class="project-nav__info">
                            <span class="project-nav__label"><span aria-hidden="true">&larr;</span> <?php esc_html_e('Previous Project', 'golden-globe'); ?></span>
                            <span class="project-nav__name"><?php echo esc_html($prev_project->post_title); ?></span>
                        </div>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" class="project-nav__all" aria-label="<?php esc_attr_e('All projects', 'golden-globe'); ?>">
                    <span class="project-nav__grid-icon" aria-hidden="true">&#9783;</span>
                    <span><?php esc_html_e('All Work', 'golden-globe'); ?></span>
                </a>

                <?php if ($next_project) :
                    $next_thumb = get_the_post_thumbnail_url($next_project->ID, 'card-thumb');
                ?>
                    <a href="<?php echo esc_url(get_permalink($next_project->ID)); ?>" class="project-nav__item project-nav__item--next">
                        <div class="project-nav__info project-nav__info--right">
                            <span class="project-nav__label"><?php esc_html_e('Next Project', 'golden-globe'); ?> <span aria-hidden="true">&rarr;</span></span>
                            <span class="project-nav__name"><?php echo esc_html($next_project->post_title); ?></span>
                        </div>
                        <?php if ($next_thumb) : ?>
                            <div class="project-nav__thumb" style="background-image:url('<?php echo esc_url($next_thumb); ?>')"></div>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div><!-- /.project-layout -->

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
