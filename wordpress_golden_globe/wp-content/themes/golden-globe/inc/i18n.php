<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('golden-globe', AGENCY_THEME_DIR . '/languages');
});
