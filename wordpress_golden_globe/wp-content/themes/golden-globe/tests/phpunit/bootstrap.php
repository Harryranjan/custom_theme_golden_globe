<?php
/**
 * PHPUnit Bootstrap
 * Sets up the WordPress test environment.
 *
 * @package GoldenGlobe
 */

// Path to WordPress test library (installed by bin/install-wp-tests.sh or wp-phpunit)
$_tests_dir = getenv('WP_TESTS_DIR');

if ( ! $_tests_dir ) {
    $_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( "$_tests_dir/includes/functions.php" ) ) {
    echo "Could not find $_tests_dir/includes/functions.php\n";
    exit( 1 );
}

// Give access to tests functions.
require_once "$_tests_dir/includes/functions.php";

// Load the theme.
function _manually_load_theme(): void {
    switch_theme( 'golden-globe' );
    require get_template_directory() . '/functions.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_theme' );

// Start up the WP testing environment.
require "$_tests_dir/includes/bootstrap.php";
