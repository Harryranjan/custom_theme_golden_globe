<?php
$header_classes  = function_exists('agency_header_classes') ? agency_header_classes() : 'site-header';
$header_style    = function_exists('agency_page_setting')   ? agency_page_setting('page_header_style', 'default') : 'default';
$is_minimal      = ( 'minimal' === $header_style );
$nav_args        = function_exists('agency_primary_nav_args') ? agency_primary_nav_args() : [
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-primary__list',
    'fallback_cb'    => false,
    'depth'          => 2,
];
?>
<header class="<?php echo esc_attr( $header_classes ); ?>" role="banner">
    <div class="container">
        <div class="site-header__inner">

            <!-- Logo -->
            <div class="site-header__logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <strong><?php bloginfo('name'); ?></strong>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ( ! $is_minimal ) : ?>
            <!-- Primary Nav -->
            <nav class="nav-primary" aria-label="<?php esc_attr_e('Primary Navigation', 'golden-globe'); ?>" data-nav-menu>
                <?php wp_nav_menu( $nav_args ); ?>
            </nav>

            <!-- Mobile Toggle -->
            <button class="nav-toggle" data-nav-toggle
                    aria-expanded="false"
                    aria-controls="primary-nav"
                    aria-label="<?php esc_attr_e('Toggle Navigation', 'golden-globe'); ?>">
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>
            <?php endif; ?>

        </div>
    </div>
</header>
<div class="nav-overlay" data-nav-overlay aria-hidden="true"></div>
