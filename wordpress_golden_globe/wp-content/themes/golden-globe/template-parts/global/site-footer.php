<?php
$footer_style = function_exists('agency_page_setting') ? agency_page_setting('page_footer_style', 'default') : 'default';
$is_minimal   = ( 'minimal' === $footer_style );
?>
<footer class="site-footer<?php echo $is_minimal ? ' site-footer--minimal' : ''; ?>" role="contentinfo">
    <div class="container">

        <?php if ( ! $is_minimal ) : ?>
        <div class="site-footer__grid">

            <!-- Col 1 -->
            <div class="site-footer__col">
                <?php if (is_active_sidebar('footer-col-1')) : ?>
                    <?php dynamic_sidebar('footer-col-1'); ?>
                <?php else : ?>
                    <h3 class="site-footer__title"><?php bloginfo('name'); ?></h3>
                    <p><?php bloginfo('description'); ?></p>
                <?php endif; ?>
            </div>

            <!-- Col 2 -->
            <?php if (is_active_sidebar('footer-col-2')) : ?>
                <div class="site-footer__col">
                    <?php dynamic_sidebar('footer-col-2'); ?>
                </div>
            <?php endif; ?>

            <!-- Col 3 -->
            <?php if (is_active_sidebar('footer-col-3')) : ?>
                <div class="site-footer__col">
                    <?php dynamic_sidebar('footer-col-3'); ?>
                </div>
            <?php endif; ?>

        </div>
        <?php endif; ?>

        <div class="site-footer__bottom">
            <p>
                &copy; <?php echo esc_html(date('Y')); ?>
                <?php echo esc_html(get_bloginfo('name')); ?>.
                <?php esc_html_e('All rights reserved.', 'golden-globe'); ?>
            </p>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => 'nav',
                'container_attr' => ['aria-label' => __('Footer Navigation', 'golden-globe')],
                'menu_class'     => 'footer-nav',
                'fallback_cb'    => false,
                'depth'          => 1,
            ]);
            ?>
        </div>

    </div>
</footer>
