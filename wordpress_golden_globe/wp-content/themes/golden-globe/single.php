<?php
/**
 * Single post template.
 * Zones: post hero → article body → author box → related posts → comments.
 */
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    // ── Computed values ────────────────────────────────────────────
    $post_id        = get_the_ID();
    $author_id      = get_the_author_meta('ID');
    $primary_cat    = agency_get_first_term($post_id, 'category');
    $word_count     = str_word_count(wp_strip_all_tags(get_the_content()));
    $reading_time   = max(1, (int) ceil($word_count / 200));
    $adjacent       = agency_get_adjacent_posts();

    ?>

    <!-- ═══ 1. POST HERO ════════════════════════════════════════════ -->
    <header class="post-hero" role="banner">

        <?php if (has_post_thumbnail()) : ?>
            <div class="post-hero__image">
                <?php the_post_thumbnail('hero-large', [
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'alt'     => esc_attr(get_the_title()),
                ]); ?>
            </div>
        <?php endif; ?>

        <div class="post-hero__overlay">
            <div class="container">

                <?php if ($primary_cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($primary_cat)); ?>"
                       class="post-hero__cat">
                        <?php echo esc_html($primary_cat->name); ?>
                    </a>
                <?php endif; ?>

                <h1 class="post-hero__title"><?php the_title(); ?></h1>

                <div class="post-hero__meta">
                    <address class="post-hero__author">
                        <?php echo get_avatar($author_id, 32, '', '', ['class' => 'post-hero__avatar']); ?>
                        <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"
                           rel="author">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </address>

                    <time class="post-hero__date"
                          datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>

                    <?php if (get_the_modified_date('c') !== get_the_date('c')) : ?>
                        <span class="post-hero__updated">
                            <?php printf(
                                /* translators: %s = human-readable date */
                                esc_html__('Updated %s', 'golden-globe'),
                                '<time datetime="' . esc_attr(get_the_modified_date('c')) . '">'
                                    . esc_html(get_the_modified_date()) . '</time>'
                            ); ?>
                        </span>
                    <?php endif; ?>

                    <span class="post-hero__read-time">
                        <?php printf(
                            /* translators: %d = number of minutes */
                            esc_html(_n('%d min read', '%d min read', $reading_time, 'golden-globe')),
                            $reading_time
                        ); ?>
                    </span>
                </div><!-- .post-hero__meta -->

            </div>
        </div><!-- .post-hero__overlay -->
    </header><!-- .post-hero -->


    <!-- ═══ 2. ARTICLE BODY ═════════════════════════════════════════ -->
    <main id="main-content" class="site-main">
        <div class="container">
            <div class="post-layout">

                <article id="post-<?php the_ID(); ?>" <?php post_class('post-article'); ?>>

                    <!-- Breadcrumb -->
                    <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                        <ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                                    <span itemprop="name"><?php esc_html_e('Home', 'golden-globe'); ?></span>
                                </a>
                                <meta itemprop="position" content="1">
                            </li>
                            <?php if (get_option('page_for_posts')) : ?>
                                <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" itemprop="item">
                                        <span itemprop="name"><?php echo esc_html(get_the_title(get_option('page_for_posts'))); ?></span>
                                    </a>
                                    <meta itemprop="position" content="2">
                                </li>
                            <?php endif; ?>
                            <?php if ($primary_cat) : ?>
                                <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="<?php echo esc_url(get_term_link($primary_cat)); ?>" itemprop="item">
                                        <span itemprop="name"><?php echo esc_html($primary_cat->name); ?></span>
                                    </a>
                                    <meta itemprop="position" content="3">
                                </li>
                            <?php endif; ?>
                            <li class="breadcrumb__item breadcrumb__item--current"
                                aria-current="page"
                                itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <span itemprop="name"><?php the_title(); ?></span>
                                <meta itemprop="position" content="<?php echo $primary_cat ? '4' : (get_option('page_for_posts') ? '3' : '2'); ?>">
                            </li>
                        </ol>
                    </nav>

                    <!-- Body -->
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if ($tags && !is_wp_error($tags)) :
                    ?>
                        <div class="post-tags">
                            <span class="post-tags__label"><?php esc_html_e('Tagged:', 'golden-globe'); ?></span>
                            <?php foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                   class="post-tag"
                                   rel="tag">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Prev / Next Post Navigation -->
                    <?php if ($adjacent['prev'] || $adjacent['next']) : ?>
                        <nav class="post-nav" aria-label="<?php esc_attr_e('Post Navigation', 'golden-globe'); ?>">
                            <?php if ($adjacent['prev']) : ?>
                                <a href="<?php echo esc_url(get_permalink($adjacent['prev'])); ?>"
                                   class="post-nav__link post-nav__link--prev"
                                   rel="prev">
                                    <span class="post-nav__direction"><?php esc_html_e('← Previous', 'golden-globe'); ?></span>
                                    <span class="post-nav__title"><?php echo esc_html(get_the_title($adjacent['prev'])); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if ($adjacent['next']) : ?>
                                <a href="<?php echo esc_url(get_permalink($adjacent['next'])); ?>"
                                   class="post-nav__link post-nav__link--next"
                                   rel="next">
                                    <span class="post-nav__direction"><?php esc_html_e('Next →', 'golden-globe'); ?></span>
                                    <span class="post-nav__title"><?php echo esc_html(get_the_title($adjacent['next'])); ?></span>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>

                </article><!-- .post-article -->


                <!-- ═══ 3. AUTHOR BOX ══════════════════════════════════════ -->
                <?php
                $author_bio = get_the_author_meta('description', $author_id);
                if ($author_bio) :
                ?>
                    <aside class="author-box" aria-label="<?php esc_attr_e('About the author', 'golden-globe'); ?>">
                        <div class="author-box__avatar">
                            <?php echo get_avatar($author_id, 80, '', esc_attr(get_the_author_meta('display_name', $author_id)), ['class' => 'author-box__img']); ?>
                        </div>
                        <div class="author-box__info">
                            <p class="author-box__label"><?php esc_html_e('Written by', 'golden-globe'); ?></p>
                            <h3 class="author-box__name">
                                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
                                    <?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?>
                                </a>
                            </h3>
                            <p class="author-box__bio"><?php echo wp_kses_post($author_bio); ?></p>
                            <?php
                            $author_url = get_the_author_meta('user_url', $author_id);
                            if ($author_url) :
                            ?>
                                <a href="<?php echo esc_url($author_url); ?>"
                                   class="author-box__website"
                                   rel="noopener noreferrer"
                                   target="_blank">
                                    <?php esc_html_e('Visit website →', 'golden-globe'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </aside>
                <?php endif; ?>


                <!-- ═══ 4. RELATED POSTS ═══════════════════════════════════ -->
                <?php
                $related_args = [
                    'post_type'           => 'post',
                    'posts_per_page'      => 3,
                    'post__not_in'        => [$post_id],
                    'post_status'         => 'publish',
                    'no_found_rows'       => true,
                    'ignore_sticky_posts' => true,
                ];

                if ($primary_cat) {
                    $related_args['tax_query'] = [[
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => $primary_cat->term_id,
                    ]];
                }

                $related = new WP_Query($related_args);

                if ($related->have_posts()) :
                ?>
                    <section class="related-posts" aria-label="<?php esc_attr_e('Related Posts', 'golden-globe'); ?>">
                        <h2 class="related-posts__title"><?php esc_html_e('Related Posts', 'golden-globe'); ?></h2>
                        <div class="grid grid--3">
                            <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <?php get_template_part('template-parts/components/card', null, [
                                    'post_id'    => get_the_ID(),
                                    'title'      => get_the_title(),
                                    'permalink'  => get_permalink(),
                                    'excerpt'    => get_the_excerpt(),
                                    'thumbnail'  => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                                    'categories' => get_the_terms(get_the_ID(), 'category') ?: [],
                                ]); ?>
                            <?php endwhile; ?>
                        </div>
                    </section>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>


                <!-- ═══ 5. COMMENTS ════════════════════════════════════════ -->
                <?php comments_template(); ?>

            </div><!-- .post-layout -->
        </div><!-- .container -->
    </main>

<?php endwhile;

get_footer();

