<?php
defined('ABSPATH') || exit;

get_header();

// ─── Taxonomy context ─────────────────────────────────────────
$current_type = is_tax('service_type') ? get_queried_object() : null;
$current_ind  = is_tax('industry')     ? get_queried_object() : null;

// All service types for the filter bar
$service_types = get_terms([
    'taxonomy'   => 'service_type',
    'hide_empty' => true,
]);

// Active filter slug
$active_slug = 'all';
if ($current_type) {
    $active_slug = $current_type->slug;
} elseif (isset($_GET['filter'])) {
    $active_slug = sanitize_title(wp_unslash($_GET['filter']));
}
?>

<main id="main-content" class="site-main">

    <!-- ── ARCHIVE BANNER ── -->
    <header class="service-archive-banner">
        <div class="container service-archive-banner__inner">
            <nav class="breadcrumbs breadcrumbs--light" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item" aria-current="page">
                        <?php echo $current_type ? esc_html($current_type->name) : esc_html__('Services', 'golden-globe'); ?>
                    </li>
                </ol>
            </nav>

            <h1 class="service-archive-banner__title">
                <?php
                if ($current_type) {
                    echo esc_html($current_type->name);
                } elseif ($current_ind) {
                    echo esc_html($current_ind->name);
                } else {
                    esc_html_e('What We Do', 'golden-globe');
                }
                ?>
            </h1>

            <?php
            $desc = $current_type ? $current_type->description : ($current_ind ? $current_ind->description : '');
            if ($desc) : ?>
                <p class="service-archive-banner__desc"><?php echo esc_html($desc); ?></p>
            <?php else : ?>
                <p class="service-archive-banner__desc">
                    <?php esc_html_e('Expert solutions tailored to your goals — from strategy to execution.', 'golden-globe'); ?>
                </p>
            <?php endif; ?>

            <!-- Stats row -->
            <div class="service-archive-banner__stats">
                <?php
                $total_services = wp_count_posts('service')->publish;
                $type_count     = wp_count_terms(['taxonomy' => 'service_type']);
                ?>
                <span>
                    <strong><?php echo esc_html($total_services); ?></strong>
                    <?php esc_html_e('Services', 'golden-globe'); ?>
                </span>
                <span class="service-archive-banner__stats-divider" aria-hidden="true"></span>
                <span>
                    <strong><?php echo esc_html($type_count); ?></strong>
                    <?php esc_html_e('Specialisms', 'golden-globe'); ?>
                </span>
            </div>
        </div>
        <div class="service-archive-banner__shape" aria-hidden="true"></div>
    </header>

    <!-- ── FILTER BAR ── -->
    <?php if (!empty($service_types) && !is_wp_error($service_types)) : ?>
        <div class="service-archive-filter-bar" id="service-filter-bar">
            <div class="container">
                <ul class="service-archive-filter-bar__list" role="list">
                    <li>
                        <button class="service-filter-btn<?php echo $active_slug === 'all' ? ' service-filter-btn--active' : ''; ?>"
                                data-filter="all"
                                aria-pressed="<?php echo $active_slug === 'all' ? 'true' : 'false'; ?>">
                            <?php esc_html_e('All Services', 'golden-globe'); ?>
                        </button>
                    </li>
                    <?php foreach ($service_types as $type) : ?>
                        <li>
                            <button class="service-filter-btn<?php echo $active_slug === $type->slug ? ' service-filter-btn--active' : ''; ?>"
                                    data-filter="<?php echo esc_attr($type->slug); ?>"
                                    aria-pressed="<?php echo $active_slug === $type->slug ? 'true' : 'false'; ?>">
                                <?php echo esc_html($type->name); ?>
                                <span class="service-filter-btn__count"><?php echo esc_html($type->count); ?></span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- ── SERVICES GRID ── -->
    <section class="service-archive-section">
        <div class="container">

            <div class="service-archive-grid" id="service-grid" aria-live="polite" aria-atomic="false">
                <?php
                $init_args = [
                    'post_type'      => 'service',
                    'post_status'    => 'publish',
                    'posts_per_page' => 9,
                    'paged'          => get_query_var('paged', 1),
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ];
                if ($active_slug !== 'all') {
                    $init_args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
                        'taxonomy' => 'service_type',
                        'field'    => 'slug',
                        'terms'    => $active_slug,
                    ]];
                }
                $service_query = new WP_Query($init_args);

                if ($service_query->have_posts()) :
                    while ($service_query->have_posts()) :
                        $service_query->the_post();
                        $sid    = get_the_ID();
                        $types  = get_the_terms($sid, 'service_type') ?: [];
                        $tagline = function_exists('get_field') ? agency_get_field('service_tagline', $sid) : '';
                        $icon    = function_exists('get_field') ? agency_get_field('service_icon', $sid)    : null;
                        get_template_part('template-parts/components/card', 'service', [
                            'post_id'   => $sid,
                            'title'     => get_the_title(),
                            'permalink' => get_permalink(),
                            'excerpt'   => get_the_excerpt(),
                            'thumbnail' => get_the_post_thumbnail_url($sid, 'card-thumb'),
                            'types'     => $types,
                            'tagline'   => $tagline,
                            'icon'      => $icon,
                        ]);
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="service-empty">
                        <p><?php esc_html_e('No services found in this category.', 'golden-globe'); ?></p>
                        <button class="service-filter-btn service-filter-btn--active" data-filter="all">
                            <?php esc_html_e('View All Services', 'golden-globe'); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div><!-- #service-grid -->

            <!-- Spinner -->
            <div class="service-spinner" id="service-spinner" aria-hidden="true" aria-label="<?php esc_attr_e('Loading', 'golden-globe'); ?>">
                <span></span><span></span><span></span>
            </div>

            <!-- Load More -->
            <?php if (isset($service_query) && $service_query->max_num_pages > 1) : ?>
                <div class="service-load-more" id="service-load-more">
                    <button class="btn btn--outline" id="service-load-btn"
                            data-page="1"
                            data-max="<?php echo esc_attr($service_query->max_num_pages); ?>"
                            data-filter="<?php echo esc_attr($active_slug); ?>">
                        <?php esc_html_e('Load More Services', 'golden-globe'); ?>
                    </button>
                    <p class="service-load-more__count" id="service-load-count">
                        <?php
                        $shown = min(9, $service_query->found_posts);
                        printf(
                            /* translators: 1: shown count 2: total count */
                            esc_html__('Showing %1$s of %2$s services', 'golden-globe'),
                            '<strong>' . esc_html($shown) . '</strong>',
                            '<strong>' . esc_html($service_query->found_posts) . '</strong>'
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- .container -->
    </section>

</main>

<!-- ── INLINE SCRIPT — service filter & load-more ── -->
<script>
(function () {
    'use strict';

    const grid      = document.getElementById('service-grid');
    const spinner   = document.getElementById('service-spinner');
    const loadMore  = document.getElementById('service-load-more');
    const loadBtn   = document.getElementById('service-load-btn');
    const loadCount = document.getElementById('service-load-count');
    const filterBar = document.getElementById('service-filter-bar');

    if (!grid) return;

    const ajaxUrl = <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
    const nonce   = <?php echo wp_json_encode(wp_create_nonce('agency_nonce')); ?>;

    let currentFilter = <?php echo wp_json_encode($active_slug); ?>;
    let currentPage   = 1;
    let maxPages      = <?php echo esc_js(isset($service_query) ? (int) $service_query->max_num_pages : 1); ?>;
    let isLoading     = false;

    function setLoading(state) {
        isLoading = state;
        spinner.setAttribute('aria-hidden', state ? 'false' : 'true');
        spinner.style.display = state ? 'flex' : 'none';
        if (loadBtn) loadBtn.disabled = state;
    }

    function updateLoadMore(found, shownCount, pages) {
        if (!loadMore) return;
        if (currentPage >= pages) {
            loadMore.style.display = 'none';
        } else {
            loadMore.style.display = 'flex';
            if (loadCount) {
                loadCount.innerHTML = <?php echo wp_json_encode(
                    /* translators: 1: shown 2: total */
                    __('Showing %1$s of %2$s services', 'golden-globe')
                ); ?>.replace('%1$s', '<strong>' + shownCount + '</strong>')
                 .replace('%2$s', '<strong>' + found + '</strong>');
            }
        }
    }

    function fetchServices(filter, page, append) {
        if (isLoading) return;
        setLoading(true);

        const body = new FormData();
        body.append('action',   'filter_service');
        body.append('nonce',    nonce);
        body.append('category', filter);
        body.append('page',     page);

        fetch(ajaxUrl, { method: 'POST', body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) {
                    console.error('Service filter error:', data.data);
                    setLoading(false);
                    return;
                }

                const r = data.data;

                if (append) {
                    const frag = document.createRange().createContextualFragment(r.html);
                    grid.appendChild(frag);
                } else {
                    grid.innerHTML = r.html || '<div class="service-empty"><p>' +
                        <?php echo wp_json_encode(__('No services found in this category.', 'golden-globe')); ?> +
                        '</p></div>';
                }

                maxPages    = r.max_pages;
                currentPage = r.current;

                const cards = grid.querySelectorAll('.service-card, .card');
                updateLoadMore(r.found, cards.length, maxPages);
                if (loadBtn) {
                    loadBtn.dataset.page   = currentPage;
                    loadBtn.dataset.max    = maxPages;
                    loadBtn.dataset.filter = filter;
                }

                setLoading(false);
            })
            .catch(function (err) {
                console.error('Service fetch failed:', err);
                setLoading(false);
            });
    }

    // ── Filter buttons ───────────────────────────────────────
    if (filterBar) {
        filterBar.addEventListener('click', function (e) {
            const btn = e.target.closest('.service-filter-btn');
            if (!btn) return;

            const filter = btn.dataset.filter;
            if (filter === currentFilter) return;

            filterBar.querySelectorAll('.service-filter-btn').forEach(function (b) {
                b.classList.toggle('service-filter-btn--active', b === btn);
                b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
            });

            currentFilter = filter;
            currentPage   = 1;
            fetchServices(filter, 1, false);
        });
    }

    // ── "View All Services" inside empty state ───────────────
    grid.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-filter="all"]');
        if (!btn) return;
        filterBar && filterBar.querySelector('[data-filter="all"]').click();
    });

    // ── Load More ────────────────────────────────────────────
    if (loadBtn) {
        loadBtn.addEventListener('click', function () {
            fetchServices(currentFilter, currentPage + 1, true);
        });
    }

    setLoading(false);
}());
</script>

<?php get_footer(); ?>
