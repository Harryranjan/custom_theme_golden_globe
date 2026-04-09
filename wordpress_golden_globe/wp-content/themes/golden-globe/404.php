<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="main-content" class="site-main">
    <section class="error-404">
        <div class="container">

            <div class="error-404__inner">

                <!-- Big "404" display -->
                <p class="error-404__code" aria-hidden="true">404</p>

                <h1 class="error-404__title">
                    <?php esc_html_e('Page not found', 'golden-globe'); ?>
                </h1>
                <p class="error-404__message">
                    <?php esc_html_e("The page you're looking for may have been moved, renamed, or doesn't exist.", 'golden-globe'); ?>
                </p>

                <!-- Search -->
                <div class="error-404__search">
                    <?php get_search_form(); ?>
                </div>

                <!-- Quick links -->
                <div class="error-404__actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                        <?php esc_html_e('Go to Homepage', 'golden-globe'); ?>
                    </a>
                    <?php
                    $blog = get_option('page_for_posts');
                    if ($blog) : ?>
                        <a href="<?php echo esc_url(get_permalink($blog)); ?>" class="btn btn--outline">
                            <?php esc_html_e('Browse Blog', 'golden-globe'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Recent Posts -->
                <?php
                $recent = new WP_Query([
                    'post_type'           => 'post',
                    'posts_per_page'      => 4,
                    'no_found_rows'       => true,
                    'ignore_sticky_posts' => true,
                ]);
                if ($recent->have_posts()) : ?>
                    <div class="error-404__recent">
                        <h2 class="error-404__recent-title">
                            <?php esc_html_e('Recent Posts', 'golden-globe'); ?>
                        </h2>
                        <ul class="error-404__post-list">
                            <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                                <li class="error-404__post-item">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>" class="error-404__post-thumb" tabindex="-1" aria-hidden="true">
                                            <?php the_post_thumbnail('thumbnail', ['loading' => 'lazy']); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="error-404__post-info">
                                        <a href="<?php the_permalink(); ?>" class="error-404__post-link">
                                            <?php the_title(); ?>
                                        </a>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="error-404__post-date">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </div>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div><!-- /.error-404__inner -->

        </div><!-- /.container -->
    </section>
</main>
<?php get_footer(); ?>

