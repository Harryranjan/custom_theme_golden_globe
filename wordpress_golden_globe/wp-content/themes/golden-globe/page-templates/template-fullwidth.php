<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 */

defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post(); ?>
<main id="main-content" class="site-main site-main--fullwidth">
    <div class="container container--wide">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    </div>
</main>
<?php endwhile;
get_footer();
