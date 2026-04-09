<?php
/**
 * Template Name: With Sidebar
 * Template Post Type: page
 */

defined('ABSPATH') || exit;

get_header();
while (have_posts()) : the_post(); ?>

<main id="main-content" class="site-main">
    <div class="container">
        <div class="sidebar-layout">

            <!-- Main Content -->
            <div class="sidebar-layout__content">
                <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                        wp_link_pages([
                            'before' => '<nav class="page-links" aria-label="' . esc_attr__('Page navigation', 'golden-globe') . '">',
                            'after'  => '</nav>',
                        ]);
                        ?>
                    </div>

                </article>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar-layout__sidebar"
                   role="complementary"
                   aria-label="<?php esc_attr_e('Sidebar', 'golden-globe'); ?>">
                <?php if (is_active_sidebar('sidebar-main')) : ?>
                    <?php dynamic_sidebar('sidebar-main'); ?>
                <?php else : ?>
                    <div class="sidebar-placeholder">
                        <p><?php esc_html_e('Add widgets from Appearance → Widgets → Sidebar.', 'golden-globe'); ?></p>
                    </div>
                <?php endif; ?>
            </aside>

        </div><!-- .sidebar-layout -->
    </div><!-- .container -->
</main>

<?php endwhile;
get_footer();
