<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    $post_id   = get_the_ID();
    $has_thumb = has_post_thumbnail();
    $thumb_url = $has_thumb ? get_the_post_thumbnail_url($post_id, 'full') : '';

    // Taxonomy terms
    $service_types = get_the_terms($post_id, 'service_type') ?: [];
    $industries    = get_the_terms($post_id, 'industry')     ?: [];

    // ACF fields with post_meta fallback
    if (function_exists('get_field')) {
        $tagline       = agency_get_field('service_tagline',      $post_id);
        $icon          = agency_get_field('service_icon',         $post_id); // image array
        $price_label   = agency_get_field('service_price',        $post_id);
        $duration      = agency_get_field('service_duration',     $post_id);
        $deliverables  = agency_get_field('service_deliverables', $post_id); // repeater: item
        $process_steps = agency_get_field('service_process',      $post_id); // repeater: step_title, step_desc
        $cta_label     = agency_get_field('service_cta_label',    $post_id);
        $cta_url       = agency_get_field('service_cta_url',      $post_id);
    } else {
        $tagline       = get_post_meta($post_id, 'service_tagline',   true);
        $icon          = null;
        $price_label   = get_post_meta($post_id, 'service_price',     true);
        $duration      = get_post_meta($post_id, 'service_duration',  true);
        $deliverables  = null;
        $process_steps = null;
        $cta_label     = get_post_meta($post_id, 'service_cta_label', true);
        $cta_url       = get_post_meta($post_id, 'service_cta_url',   true);
    }

    // Prev / next within service CPT
    $prev_service = get_previous_post(false, '', 'service_type');
    $next_service = get_next_post(false, '', 'service_type');
?>

<main id="main-content" class="site-main">

    <!-- ── SERVICE HERO ── -->
    <header class="service-hero<?php echo $has_thumb ? ' service-hero--image' : ''; ?>"
        <?php if ($has_thumb) : ?>style="background-image:url('<?php echo esc_url($thumb_url); ?>')"<?php endif; ?>>
        <?php if ($has_thumb) : ?>
            <div class="service-hero__overlay"></div>
        <?php endif; ?>
        <div class="container service-hero__inner">

            <!-- Breadcrumb -->
            <nav class="breadcrumbs<?php echo $has_thumb ? ' breadcrumbs--light' : ''; ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(get_post_type_archive_link('service')); ?>"><?php esc_html_e('Services', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>

            <!-- Type pills -->
            <?php if (!empty($service_types) && !is_wp_error($service_types)) : ?>
                <div class="service-hero__types">
                    <?php foreach ($service_types as $type) : ?>
                        <a href="<?php echo esc_url(get_term_link($type)); ?>" class="tag tag--primary">
                            <?php echo esc_html($type->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Icon + title -->
            <div class="service-hero__heading-row">
                <?php if ($icon && !empty($icon['url'])) : ?>
                    <div class="service-hero__icon">
                        <img src="<?php echo esc_url($icon['url']); ?>"
                             alt=""
                             width="48" height="48"
                             aria-hidden="true">
                    </div>
                <?php endif; ?>
                <h1 class="service-hero__title"><?php the_title(); ?></h1>
            </div>

            <?php $tagline = $tagline ?: get_the_excerpt(); if ($tagline) : ?>
                <p class="service-hero__tagline"><?php echo esc_html(wp_strip_all_tags($tagline)); ?></p>
            <?php endif; ?>

        </div>
    </header>

    <!-- ── LAYOUT ── -->
    <div class="container service-layout">
        <div class="service-columns">

            <!-- ── MAIN CONTENT ── -->
            <article id="post-<?php the_ID(); ?>" <?php post_class('service-content'); ?>>

                <!-- Editor content -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Process steps -->
                <?php if (!empty($process_steps)) : ?>
                    <section class="service-section">
                        <h2 class="service-section__title"><?php esc_html_e('How It Works', 'golden-globe'); ?></h2>
                        <ol class="service-process">
                            <?php foreach ($process_steps as $i => $step) :
                                $step_title = is_array($step) ? ($step['step_title'] ?? '') : $step;
                                $step_desc  = is_array($step) ? ($step['step_desc']  ?? '') : '';
                            ?>
                                <li class="service-process__step">
                                    <span class="service-process__num" aria-hidden="true"><?php echo esc_html($i + 1); ?></span>
                                    <div class="service-process__body">
                                        <?php if ($step_title) : ?>
                                            <h3 class="service-process__name"><?php echo esc_html($step_title); ?></h3>
                                        <?php endif; ?>
                                        <?php if ($step_desc) : ?>
                                            <p class="service-process__desc"><?php echo esc_html($step_desc); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </section>
                <?php endif; ?>

                <!-- Deliverables -->
                <?php if (!empty($deliverables)) : ?>
                    <section class="service-section">
                        <h2 class="service-section__title"><?php esc_html_e("What You'll Receive", 'golden-globe'); ?></h2>
                        <ul class="service-deliverables">
                            <?php foreach ($deliverables as $row) :
                                $item = is_array($row) ? ($row['item'] ?? $row[0] ?? '') : $row;
                                if (!$item) continue;
                            ?>
                                <li class="service-deliverables__item">
                                    <span class="service-deliverables__check" aria-hidden="true">&#10003;</span>
                                    <?php echo esc_html($item); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>

                <!-- Related portfolio projects -->
                <?php
                $related = new WP_Query([
                    'post_type'      => 'portfolio',
                    'posts_per_page' => 3,
                    'no_found_rows'  => true,
                    'post_status'    => 'publish',
                ]);
                if ($related->have_posts()) : ?>
                    <section class="service-section">
                        <h2 class="service-section__title"><?php esc_html_e('Related Work', 'golden-globe'); ?></h2>
                        <div class="grid grid--3">
                            <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <?php get_template_part('template-parts/components/card', null, [
                                    'post_id'    => get_the_ID(),
                                    'title'      => get_the_title(),
                                    'permalink'  => get_permalink(),
                                    'excerpt'    => get_the_excerpt(),
                                    'thumbnail'  => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                                    'categories' => get_the_terms(get_the_ID(), 'portfolio_cat') ?: [],
                                ]); ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </section>
                <?php endif; ?>

            </article>

            <!-- ── SIDEBAR ── -->
            <aside class="service-sidebar">

                <!-- Service details card -->
                <div class="service-meta-card">
                    <h2 class="service-meta-card__title"><?php esc_html_e('Service Details', 'golden-globe'); ?></h2>

                    <dl class="project-meta-list">

                        <?php if ($price_label) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Starting From', 'golden-globe'); ?></dt>
                                <dd class="service-meta__price"><?php echo esc_html($price_label); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if ($duration) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Timeline', 'golden-globe'); ?></dt>
                                <dd><?php echo esc_html($duration); ?></dd>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($service_types) && !is_wp_error($service_types)) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Type', 'golden-globe'); ?></dt>
                                <dd>
                                    <?php foreach ($service_types as $i => $type) :
                                        echo ($i > 0 ? ', ' : '');
                                    ?><a href="<?php echo esc_url(get_term_link($type)); ?>"><?php echo esc_html($type->name); ?></a><?php
                                    endforeach; ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($industries) && !is_wp_error($industries)) : ?>
                            <div class="project-meta-row">
                                <dt><?php esc_html_e('Industry', 'golden-globe'); ?></dt>
                                <dd>
                                    <?php foreach ($industries as $i => $ind) :
                                        echo ($i > 0 ? ', ' : '');
                                    ?><a href="<?php echo esc_url(get_term_link($ind)); ?>"><?php echo esc_html($ind->name); ?></a><?php
                                    endforeach; ?>
                                </dd>
                            </div>
                        <?php endif; ?>

                    </dl>

                    <?php
                    $cta_href  = $cta_url   ?: (get_page_by_path('contact') ? get_permalink(get_page_by_path('contact')) : home_url('/contact/'));
                    $cta_text  = $cta_label ?: __('Get a Quote', 'golden-globe');
                    ?>
                    <a href="<?php echo esc_url($cta_href); ?>" class="btn btn--primary btn--full">
                        <?php echo esc_html($cta_text); ?>
                    </a>
                </div>

                <!-- All services list -->
                <?php
                $all_services = new WP_Query([
                    'post_type'      => 'service',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'no_found_rows'  => true,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ]);
                if ($all_services->have_posts()) : ?>
                    <div class="service-list-card">
                        <h2 class="service-meta-card__title"><?php esc_html_e('All Services', 'golden-globe'); ?></h2>
                        <ul class="service-list">
                            <?php while ($all_services->have_posts()) : $all_services->the_post(); ?>
                                <li class="service-list__item<?php echo (get_the_ID() === $post_id) ? ' service-list__item--active' : ''; ?>">
                                    <a href="<?php the_permalink(); ?>" class="service-list__link">
                                        <?php the_title(); ?>
                                        <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </aside>

        </div><!-- /.service-columns -->

        <!-- ── PREV / NEXT SERVICES ── -->
        <?php if ($prev_service || $next_service) : ?>
            <nav class="project-nav" aria-label="<?php esc_attr_e('More services', 'golden-globe'); ?>">
                <?php if ($prev_service) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev_service->ID)); ?>" class="project-nav__item project-nav__item--prev">
                        <div class="project-nav__info">
                            <span class="project-nav__label"><span aria-hidden="true">&larr;</span> <?php esc_html_e('Previous Service', 'golden-globe'); ?></span>
                            <span class="project-nav__name"><?php echo esc_html($prev_service->post_title); ?></span>
                        </div>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(get_post_type_archive_link('service')); ?>" class="project-nav__all" aria-label="<?php esc_attr_e('All services', 'golden-globe'); ?>">
                    <span class="project-nav__grid-icon" aria-hidden="true">&#9783;</span>
                    <span><?php esc_html_e('All Services', 'golden-globe'); ?></span>
                </a>

                <?php if ($next_service) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_service->ID)); ?>" class="project-nav__item project-nav__item--next">
                        <div class="project-nav__info project-nav__info--right">
                            <span class="project-nav__label"><?php esc_html_e('Next Service', 'golden-globe'); ?> <span aria-hidden="true">&rarr;</span></span>
                            <span class="project-nav__name"><?php echo esc_html($next_service->post_title); ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div><!-- /.service-layout -->

</main>

<?php endwhile; ?>

<?php get_footer(); ?>
