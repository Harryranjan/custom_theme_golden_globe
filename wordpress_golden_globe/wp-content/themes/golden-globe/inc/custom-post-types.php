<?php
defined('ABSPATH') || exit;

// ─── REGISTER CPTs ───────────────────────────────────────────
function agency_register_post_types(): void {

    // PORTFOLIO
    register_post_type('portfolio', [
        'labels' => [
            'name'               => __('Portfolio',           'golden-globe'),
            'singular_name'      => __('Project',             'golden-globe'),
            'add_new_item'       => __('Add New Project',     'golden-globe'),
            'edit_item'          => __('Edit Project',        'golden-globe'),
            'new_item'           => __('New Project',         'golden-globe'),
            'view_item'          => __('View Project',        'golden-globe'),
            'search_items'       => __('Search Projects',     'golden-globe'),
            'not_found'          => __('No projects found',   'golden-globe'),
            'not_found_in_trash' => __('No projects in trash','golden-globe'),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'portfolio', 'with_front' => false],
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 5,
    ]);

    // SERVICES
    register_post_type('service', [
        'labels' => [
            'name'          => __('Services',    'golden-globe'),
            'singular_name' => __('Service',     'golden-globe'),
            'add_new_item'  => __('Add Service', 'golden-globe'),
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'services'],
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'     => 'dashicons-admin-tools',
        'menu_position' => 6,
    ]);

    // TESTIMONIALS
    register_post_type('testimonial', [
        'labels' => [
            'name'          => __('Testimonials', 'golden-globe'),
            'singular_name' => __('Testimonial',  'golden-globe'),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_rest'  => true,
        'supports'      => ['title', 'editor', 'thumbnail'],
        'menu_icon'     => 'dashicons-format-quote',
        'menu_position' => 7,
    ]);

    // TEAM
    register_post_type('team_member', [
        'labels' => [
            'name'          => __('Team',        'golden-globe'),
            'singular_name' => __('Team Member', 'golden-globe'),
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'rewrite'       => ['slug' => 'team'],
        'supports'      => ['title', 'editor', 'thumbnail'],
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 8,
    ]);

    // LOCATIONS
    register_post_type('location', [
        'labels' => [
            'name'               => __('Locations',          'golden-globe'),
            'singular_name'      => __('Location',           'golden-globe'),
            'add_new_item'       => __('Add New Location',   'golden-globe'),
            'edit_item'          => __('Edit Location',      'golden-globe'),
            'new_item'           => __('New Location',       'golden-globe'),
            'view_item'          => __('View Location',      'golden-globe'),
            'search_items'       => __('Search Locations',   'golden-globe'),
            'not_found'          => __('No locations found', 'golden-globe'),
            'not_found_in_trash' => __('No locations in trash', 'golden-globe'),
            'parent_item_colon'  => __('Parent Location:',   'golden-globe'),
            'all_items'          => __('All Locations',      'golden-globe'),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'hierarchical'       => true,   // enables parent-child: city → neighbourhood → street
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'locations', 'with_front' => false],
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'],
        'menu_icon'          => 'dashicons-location',
        'menu_position'      => 9,
    ]);
}
add_action('init', 'agency_register_post_types');

// ─── REGISTER TAXONOMIES ─────────────────────────────────────
function agency_register_taxonomies(): void {

    register_taxonomy('portfolio_cat', 'portfolio', [
        'labels' => [
            'name'          => __('Portfolio Categories', 'golden-globe'),
            'singular_name' => __('Portfolio Category',  'golden-globe'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'portfolio-category'],
    ]);

    register_taxonomy('service_type', 'service', [
        'labels' => [
            'name'          => __('Service Types', 'golden-globe'),
            'singular_name' => __('Service Type',  'golden-globe'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'service-type'],
    ]);

    register_taxonomy('industry', ['portfolio', 'service'], [
        'labels' => [
            'name'          => __('Industries', 'golden-globe'),
            'singular_name' => __('Industry',   'golden-globe'),
        ],
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'industry'],
    ]);

    // LOCATION REGION — groups location posts by region (e.g. "Western NY")
    register_taxonomy('location_region', 'location', [
        'labels' => [
            'name'          => __('Regions',  'golden-globe'),
            'singular_name' => __('Region',   'golden-globe'),
            'add_new_item'  => __('Add New Region', 'golden-globe'),
        ],
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_ui'      => true,
        'rewrite'      => ['slug' => 'region'],
    ]);
}
add_action('init', 'agency_register_taxonomies');

// ─── FLUSH REWRITE RULES (version-based, fires once) ─────────
/**
 * Bumping AGENCY_CPT_VERSION triggers a one-time flush the next page load.
 * Bump it any time a CPT rewrite slug or has_archive setting changes.
 */
define('AGENCY_CPT_VERSION', '1.1');

add_action('init', 'agency_maybe_flush_rewrites', 999);
function agency_maybe_flush_rewrites(): void {
    if (get_option('agency_cpt_version') !== AGENCY_CPT_VERSION) {
        flush_rewrite_rules(false);   // false = skip .htaccess rewrite (Apache handles it)
        update_option('agency_cpt_version', AGENCY_CPT_VERSION, false);
    }
}

// Also flush on theme activation so switching themes just works.
add_action('after_switch_theme', function (): void {
    agency_register_post_types();
    agency_register_taxonomies();
    flush_rewrite_rules(false);
    update_option('agency_cpt_version', AGENCY_CPT_VERSION, false);
});

// ─── AUTO-SET LOCATION SLUG FROM ACF city_name FIELD ─────────
/**
 * After ACF saves its fields (priority 20), read city_name and state_abbr
 * and use them to build a clean URL slug, e.g. "buffalo" or "buffalo-ny".
 * A static guard prevents wp_update_post() from triggering a second run.
 */
add_action('acf/save_post', 'agency_set_location_slug', 20);
function agency_set_location_slug( $post_id ): void {
    static $running = false;
    if ($running) {
        return;
    }

    if (get_post_type($post_id) !== 'location') {
        return;
    }

    $city  = trim((string) agency_get_field('city_name',   $post_id));
    $state = trim((string) agency_get_field('state_abbr',  $post_id));

    if ($city === '') {
        return;  // nothing to derive a slug from
    }

    // Build desired slug: "buffalo" or "buffalo-ny"
    $parts        = array_filter([$city, $state]);
    $desired_slug = sanitize_title(implode(' ', $parts));

    $post = get_post($post_id);
    if ($post && $post->post_name !== $desired_slug) {
        $running = true;
        wp_update_post([
            'ID'        => (int) $post_id,
            'post_name' => $desired_slug,
        ]);
        $running = false;
    }
}
