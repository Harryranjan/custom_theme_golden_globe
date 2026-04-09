<?php
defined('ABSPATH') || exit;

get_header();

// ─── Taxonomy context ────────────────────────────────────────
$current_cat  = is_tax('portfolio_cat') ? get_queried_object() : null;
$current_ind  = is_tax('industry')      ? get_queried_object() : null;

// All portfolio categories for the filter bar
$portfolio_cats = get_terms([
    'taxonomy'   => 'portfolio_cat',
    'hide_empty' => true,
]);

// Active filter slug (from URL ?filter= or current taxonomy)
$active_slug = 'all';
if ($current_cat) {
    $active_slug = $current_cat->slug;
} elseif (isset($_GET['filter'])) {
    $active_slug = sanitize_title(wp_unslash($_GET['filter']));
}
?>

<main id="main-content" class="site-main">

    <!-- ── ARCHIVE BANNER ── -->
    <header class="portfolio-archive-banner">
        <div class="container portfolio-archive-banner__inner">
            <nav class="breadcrumbs breadcrumbs--light" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item" aria-current="page">
                        <?php echo $current_cat ? esc_html($current_cat->name) : esc_html__('Our Work', 'golden-globe'); ?>
                    </li>
                </ol>
            </nav>

            <h1 class="portfolio-archive-banner__title">
                <?php
                if ($current_cat) {
                    echo esc_html($current_cat->name);
                } elseif ($current_ind) {
                    echo esc_html($current_ind->name);
                } else {
                    esc_html_e('Our Work', 'golden-globe');
                }
                ?>
            </h1>

            <?php
            $description = $current_cat ? $current_cat->description : ($current_ind ? $current_ind->description : '');
            if ($description) : ?>
                <p class="portfolio-archive-banner__desc"><?php echo esc_html($description); ?></p>
            <?php else : ?>
                <p class="portfolio-archive-banner__desc"><?php esc_html_e('Projects we\'re proud of — explore our work across design, development, and strategy.', 'golden-globe'); ?></p>
            <?php endif; ?>

            <!-- Stats row -->
            <div class="portfolio-archive-banner__stats">
                <?php
                $total = wp_count_posts('portfolio')->publish;
                $cats  = wp_count_terms(['taxonomy' => 'portfolio_cat']);
                ?>
                <span>
                    <strong><?php echo esc_html($total); ?></strong>
                    <?php esc_html_e('Projects', 'golden-globe'); ?>
                </span>
                <span class="portfolio-archive-banner__stats-divider" aria-hidden="true"></span>
                <span>
                    <strong><?php echo esc_html($cats); ?></strong>
                    <?php esc_html_e('Categories', 'golden-globe'); ?>
                </span>
            </div>
        </div>
    </header>

    <!-- ── FILTER BAR ── -->
    <?php if (!empty($portfolio_cats) && !is_wp_error($portfolio_cats)) : ?>
        <div class="portfolio-filter-bar" id="portfolio-filter-bar">
            <div class="container">
                <ul class="portfolio-filter-bar__list" role="list">
                    <li>
                        <button class="portfolio-filter-btn<?php echo $active_slug === 'all' ? ' portfolio-filter-btn--active' : ''; ?>"
                                data-filter="all"
                                aria-pressed="<?php echo $active_slug === 'all' ? 'true' : 'false'; ?>">
                            <?php esc_html_e('All Work', 'golden-globe'); ?>
                        </button>
                    </li>
                    <?php foreach ($portfolio_cats as $cat) : ?>
                        <li>
                            <button class="portfolio-filter-btn<?php echo $active_slug === $cat->slug ? ' portfolio-filter-btn--active' : ''; ?>"
                                    data-filter="<?php echo esc_attr($cat->slug); ?>"
                                    aria-pressed="<?php echo $active_slug === $cat->slug ? 'true' : 'false'; ?>">
                                <?php echo esc_html($cat->name); ?>
                                <span class="portfolio-filter-btn__count"><?php echo esc_html($cat->count); ?></span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- ── PORTFOLIO GRID ── -->
    <section class="portfolio-archive-section">
        <div class="container">

            <!-- Grid: populated by PHP on page load, replaced by AJAX on filter -->
            <div class="portfolio-grid" id="portfolio-grid" aria-live="polite" aria-atomic="false">
                <?php
                // Initial PHP render — matches what AJAX returns
                $init_args = [
                    'post_type'      => 'portfolio',
                    'post_status'    => 'publish',
                    'posts_per_page' => 9,
                    'paged'          => get_query_var('paged', 1),
                ];
                if ($active_slug !== 'all') {
                    $init_args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
                        'taxonomy' => 'portfolio_cat',
                        'field'    => 'slug',
                        'terms'    => $active_slug,
                    ]];
                }
                $portfolio_query = new WP_Query($init_args);

                if ($portfolio_query->have_posts()) :
                    while ($portfolio_query->have_posts()) :
                        $portfolio_query->the_post();
                        $pid   = get_the_ID();
                        $cats  = get_the_terms($pid, 'portfolio_cat') ?: [];
                        get_template_part('template-parts/components/card', 'portfolio', [
                            'post_id'    => $pid,
                            'title'      => get_the_title(),
                            'permalink'  => get_permalink(),
                            'excerpt'    => get_the_excerpt(),
                            'thumbnail'  => get_the_post_thumbnail_url($pid, 'card-thumb'),
                            'categories' => $cats,
                        ]);
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="portfolio-empty">
                        <p><?php esc_html_e('No projects found in this category.', 'golden-globe'); ?></p>
                        <button class="portfolio-filter-btn portfolio-filter-btn--active" data-filter="all">
                            <?php esc_html_e('View All Work', 'golden-globe'); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div><!-- #portfolio-grid -->

            <!-- Spinner shown during AJAX load -->
            <div class="portfolio-spinner" id="portfolio-spinner" aria-hidden="true" aria-label="<?php esc_attr_e('Loading', 'golden-globe'); ?>">
                <span></span><span></span><span></span>
            </div>

            <!-- Load More -->
            <?php
            $total_pages = $portfolio_query->max_num_pages ?? 1;
            if (isset($portfolio_query) && $portfolio_query->max_num_pages > 1) : ?>
                <div class="portfolio-load-more" id="portfolio-load-more">
                    <button class="btn btn--outline" id="portfolio-load-btn"
                            data-page="1"
                            data-max="<?php echo esc_attr($portfolio_query->max_num_pages); ?>"
                            data-filter="<?php echo esc_attr($active_slug); ?>">
                        <?php esc_html_e('Load More Projects', 'golden-globe'); ?>
                    </button>
                    <p class="portfolio-load-more__count" id="portfolio-load-count">
                        <?php
                        $shown = min(9, $portfolio_query->found_posts);
                        printf(
                            /* translators: 1: shown count 2: total count */
                            esc_html__('Showing %1$s of %2$s projects', 'golden-globe'),
                            '<strong>' . esc_html($shown) . '</strong>',
                            '<strong>' . esc_html($portfolio_query->found_posts) . '</strong>'
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- .container -->
    </section>

</main>

<!-- ── INLINE SCRIPT — portfolio filter & load-more ── -->
<script>
(function () {
    'use strict';

    const grid      = document.getElementById('portfolio-grid');
    const spinner   = document.getElementById('portfolio-spinner');
    const loadMore  = document.getElementById('portfolio-load-more');
    const loadBtn   = document.getElementById('portfolio-load-btn');
    const loadCount = document.getElementById('portfolio-load-count');
    const filterBar = document.getElementById('portfolio-filter-bar');

    if (!grid) return;

    const ajaxUrl = <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
    const nonce   = <?php echo wp_json_encode(wp_create_nonce('agency_nonce')); ?>;

    let currentFilter = <?php echo wp_json_encode($active_slug); ?>;
    let currentPage   = 1;
    let maxPages      = <?php echo esc_js(isset($portfolio_query) ? (int) $portfolio_query->max_num_pages : 1); ?>;
    let isLoading     = false;

    // ── Helpers ──────────────────────────────────────────────
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
                    __('Showing %1$s of %2$s projects', 'golden-globe')
                ); ?>.replace('%1$s', '<strong>' + shownCount + '</strong>')
                 .replace('%2$s', '<strong>' + found + '</strong>');
            }
        }
    }

    // ── Fetch via AJAX ───────────────────────────────────────
    function fetchProjects(filter, page, append) {
        if (isLoading) return;
        setLoading(true);

        const body = new FormData();
        body.append('action',   'filter_portfolio');
        body.append('nonce',    nonce);
        body.append('category', filter);
        body.append('page',     page);

        fetch(ajaxUrl, { method: 'POST', body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success) {
                    console.error('Portfolio filter error:', data.data);
                    setLoading(false);
                    return;
                }

                const r = data.data;

                if (append) {
                    // Append new cards
                    const frag = document.createRange().createContextualFragment(r.html);
                    grid.appendChild(frag);
                } else {
                    // Replace grid
                    grid.innerHTML = r.html || '<div class="portfolio-empty"><p>' +
                        <?php echo wp_json_encode(__('No projects found in this category.', 'golden-globe')); ?> +
                        '</p></div>';
                }

                maxPages      = r.max_pages;
                currentPage   = r.current;

                const cards = grid.querySelectorAll('.portfolio-card, .card');
                updateLoadMore(r.found, cards.length, maxPages);
                if (loadBtn) {
                    loadBtn.dataset.page   = currentPage;
                    loadBtn.dataset.max    = maxPages;
                    loadBtn.dataset.filter = filter;
                }

                setLoading(false);
            })
            .catch(function (err) {
                console.error('Portfolio fetch failed:', err);
                setLoading(false);
            });
    }

    // ── Filter buttons ───────────────────────────────────────
    if (filterBar) {
        filterBar.addEventListener('click', function (e) {
            const btn = e.target.closest('.portfolio-filter-btn');
            if (!btn) return;

            const filter = btn.dataset.filter;
            if (filter === currentFilter) return;

            // Update active state
            filterBar.querySelectorAll('.portfolio-filter-btn').forEach(function (b) {
                b.classList.toggle('portfolio-filter-btn--active', b === btn);
                b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
            });

            // Also update "View All Work" button inside empty state if present
            grid.querySelectorAll('[data-filter]').forEach(function (b) {
                b.classList.toggle('portfolio-filter-btn--active', b.dataset.filter === filter);
            });

            currentFilter = filter;
            currentPage   = 1;
            fetchProjects(filter, 1, false);
        });
    }

    // ── "View All Work" button inside empty state ─────────────
    grid.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-filter="all"]');
        if (!btn) return;
        filterBar && filterBar.querySelector('[data-filter="all"]').click();
    });

    // ── Load More ────────────────────────────────────────────
    if (loadBtn) {
        loadBtn.addEventListener('click', function () {
            const nextPage = currentPage + 1;
            fetchProjects(currentFilter, nextPage, true);
        });
    }

    // ── Init spinner hidden ──────────────────────────────────
    setLoading(false);
}());
</script>

<?php get_footer(); ?>
