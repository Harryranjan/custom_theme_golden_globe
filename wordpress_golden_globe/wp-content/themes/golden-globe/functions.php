<?php
/**
 * Golden Globe Theme — functions.php
 * Clean bootstrapper — loads all inc/ modules
 */

defined('ABSPATH') || exit;

define('AGENCY_THEME_VERSION', '1.0.0');
define('AGENCY_THEME_DIR',     get_template_directory());
define('AGENCY_THEME_URI',     get_template_directory_uri());

// Load all modules
$modules = [
    '/inc/setup.php',
    '/inc/enqueue.php',
    '/inc/menus.php',
    '/inc/widgets.php',
    '/inc/custom-post-types.php',
    '/inc/fields-core.php',
    '/inc/native-fields.php',
    '/inc/rest-api.php',
    '/inc/ajax-handlers.php',
    '/inc/theme-options.php',
    '/inc/branding.php',
    '/inc/security.php',
    '/inc/performance.php',
    '/inc/accessibility.php',
    '/inc/i18n.php',
    '/inc/helpers.php',
    '/inc/shortcodes.php',
    '/inc/class-component.php',
    '/inc/page-settings.php',
    '/inc/seo.php',
];

foreach ($modules as $module) {
    $path = AGENCY_THEME_DIR . $module;
    if (file_exists($path)) {
        require_once $path;
    }
}
