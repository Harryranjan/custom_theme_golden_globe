<?php
defined('ABSPATH') || exit;
get_header();

global $wp_query;
$query       = get_search_query();
$found       = (int) $wp_query->found_posts;
$post_types  = isset($_GET['post_type']) ? sanitize_text_field(wp_unslash($_GET['post_type'])) : '';
?>

<main id="main-content" class="site-main">

    <!-- ── SEARCH BANNER ── -->
    <header class="search-banner">
        <div class="container">

            <?php if ($query) : ?>
                <p class="search-banner__label"><?php esc_html_e('Search results for', 'golden-globe'); ?></p>
                <h1 class="search-banner__query">&ldquo;<?php echo esc_html($query); ?>&rdquo;</h1>
                <?php if ($found > 0) : ?>
                    <p class="search-banner__count">
                        <?php printf(
                            esc_html(_n('%s result found', '%s results found', $found, 'golden-globe')),
                            '<strong>' . esc_html(number_format_i18n($found)) . '</strong>'
                        ); ?>
                    </p>
                <?php endif; ?>
            <?php else : ?>
                <h1 class="search-banner__query"><?php esc_html_e('Search', 'golden-globe'); ?></h1>
            <?php endif; ?>

            <!-- Inline search form -->
            <div class="search-banner__form">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                    <label class="screen-reader-text" for="search-banner-input">
                        <?php esc_html_e('Search for:', 'golden-globe'); ?>
                    </label>
                    <input type="search"
                           id="search-banner-input"
                           class="search-field"
                           name="s"
                           value="<?php echo esc_attr($query); ?>"
                           placeholder="<?php esc_attr_e('Search&hellip;', 'golden-globe'); ?>"
                           autocomplete="off">
                    <button type="submit" class="search-submit btn btn--primary">
                        <?php esc_html_e('Search', 'golden-globe'); ?>
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- ── RESULTS ── -->
    <div class="container search-container">

        <?php if (have_posts()) : ?>

            <!-- Post type filter tabs (only shown when results span multiple types) -->
            <?php
            $result_types = [];
            $all_posts    = new WP_Query([
                's'              => $query,
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => false,
                'post_type'      => ['post', 'page', 'portfolio', 'service'],
            ]);
            if ($all_posts->have_posts()) {
                while ($all_posts->have_posts()) {
                    $all_posts->the_post();
                    $result_types[get_post_type()][] = get_the_ID();
                }
                wp_reset_postdata();
            }
            ?>
            <?php if (count($result_types) > 1) : ?>
                <nav class="search-type-tabs" aria-label="<?php esc_attr_e('Filter results by type', 'golden-globe'); ?>">
                    <a href="<?php echo esc_url(add_query_arg(['s' => rawurlencode($query), 'post_type' => ''], home_url('/'))); ?>"
                       class="search-type-tab<?php echo !$post_types ? ' search-type-tab--active' : ''; ?>">
                        <?php esc_html_e('All', 'golden-globe'); ?>
                        <span class="search-type-tab__count"><?php echo esc_html($found); ?></span>
                    </a>
                    <?php foreach ($result_types as $type => $ids) :
                        $obj = get_post_type_object($type);
                        if (!$obj) continue;
                    ?>
                        <a href="<?php echo esc_url(add_query_arg(['s' => rawurlencode($query), 'post_type' => $type], home_url('/'))); ?>"
                           class="search-type-tab<?php echo $post_types === $type ? ' search-type-tab--active' : ''; ?>">
                            <?php echo esc_html($obj->labels->name ?? $type); ?>
                            <span class="search-type-tab__count"><?php echo esc_html(count($ids)); ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <!-- Results grid -->
            <div class="search-results">
                <?php while (have_posts()) : the_post();
                    $type = get_post_type();
                    $cats = ($type === 'post') ? get_the_category(get_the_ID()) : [];
                ?>
                    <article class="search-result" id="post-<?php the_ID(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="search-result__thumb" tabindex="-1" aria-hidden="true">
                                <?php the_post_thumbnail('card-thumb', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]); ?>
                            </a>
                        <?php endif; ?>
                        <div class="search-result__body">
                            <div class="search-result__meta">
                                <?php if (!empty($cats)) : ?>
                                    <a href="<?php echo esc_url(get_term_link($cats[0])); ?>" class="search-result__cat">
                                        <?php echo esc_html($cats[0]->name); ?>
                                    </a>
                                <?php else : ?>
                                    <?php $obj = get_post_type_object($type); ?>
                                    <span class="search-result__cat"><?php echo esc_html($obj->labels->singular_name ?? $type); ?></span>
                                <?php endif; ?>
                                <time class="search-result__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </div>
                            <h2 class="search-result__title">
                                <a href="<?php the_permalink(); ?>" class="search-result__link">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <?php $excerpt = get_the_excerpt(); ?>
                            <?php if ($excerpt) : ?>
                                <p class="search-result__excerpt">
                                    <?php echo esc_html(wp_trim_words($excerpt, 25)); ?>
                                </p>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="search-result__more">
                                <?php esc_html_e('Read more', 'golden-globe'); ?>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="archive-pagination" aria-label="<?php esc_attr_e('Search result pages', 'golden-globe'); ?>">
                <?php the_posts_pagination([
                    'mid_size'           => 2,
                    'prev_text'          => '<span aria-hidden="true">&larr;</span> ' . __('Previous', 'golden-globe'),
                    'next_text'          => __('Next', 'golden-globe') . ' <span aria-hidden="true">&rarr;</span>',
                    'screen_reader_text' => __('Search results navigation', 'golden-globe'),
                ]); ?>
            </nav>

        <?php else : ?>

            <!-- No results -->
            <div class="search-empty">
                <p class="search-empty__message">
                    <?php if ($query) : ?>
                        <?php printf(
                            esc_html__('Nothing found for &ldquo;%s&rdquo;. Try a different search.', 'golden-globe'),
                            esc_html($query)
                        ); ?>
                    <?php else : ?>
                        <?php esc_html_e('Enter a search term above to find content.', 'golden-globe'); ?>
                    <?php endif; ?>
                </p>

                <?php if ($query) : ?>
                    <!-- Suggestions -->
                    <ul class="search-empty__tips">
                        <li><?php esc_html_e('Check the spelling of your search term.', 'golden-globe'); ?></li>
                        <li><?php esc_html_e('Try using fewer or more general words.', 'golden-globe'); ?></li>
                        <li><?php esc_html_e('Browse our categories instead.', 'golden-globe'); ?></li>
                    </ul>

                    <!-- Recent posts fallback -->
                    <?php
                    $recent = new WP_Query([
                        'post_type'           => 'post',
                        'posts_per_page'      => 3,
                        'no_found_rows'       => true,
                        'ignore_sticky_posts' => true,
                    ]);
                    if ($recent->have_posts()) : ?>
                        <div class="search-empty__recent">
                            <h2 class="search-empty__recent-title">
                                <?php esc_html_e('Recent Posts', 'golden-globe'); ?>
                            </h2>
                            <div class="grid grid--3">
                                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                                    <?php get_template_part('template-parts/components/card', null, [
                                        'post_id'    => get_the_ID(),
                                        'title'      => get_the_title(),
                                        'permalink'  => get_permalink(),
                                        'excerpt'    => get_the_excerpt(),
                                        'thumbnail'  => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                                        'categories' => get_the_category(get_the_ID()) ?: [],
                                    ]); ?>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div><!-- /.search-container -->

</main>

<?php get_footer(); ?>

