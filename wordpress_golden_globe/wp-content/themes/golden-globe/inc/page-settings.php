<?php
defined('ABSPATH') || exit;

/**
 * Per-page settings: hide header/footer, header style, custom menu, footer style.
 *
 * Works two ways:
 *  - With ACF active   → registers an ACF local field group (sidebar meta box)
 *  - Without ACF       → registers a native WordPress meta box (always works)
 *
 * Data is stored in the same post-meta keys either way, so the helper functions
 * work identically regardless of which method saved the values.
 */

// ─── HELPER FUNCTIONS ────────────────────────────────────────────────────────

/**
 * Get a per-page setting. Tries ACF first, then falls back to plain post meta.
 */
function agency_page_setting( string $key, $default = '' ) {
    $post_id = get_queried_object_id();

    if ( function_exists( 'get_field' ) ) {
        $value = agency_get_field( $key, $post_id );
        if ( $value !== null && $value !== '' && $value !== false ) return $value;
    }

    // Native post meta fallback
    $value = get_post_meta( $post_id, $key, true );
    return ( $value !== null && $value !== '' && $value !== false ) ? $value : $default;
}

/**
 * Should the header render on this page?
 */
function agency_show_header(): bool {
    return ! (bool) agency_page_setting( 'page_hide_header', false );
}

/**
 * Should the footer render on this page?
 */
function agency_show_footer(): bool {
    return ! (bool) agency_page_setting( 'page_hide_footer', false );
}

/**
 * Return the CSS class string for the <header> element.
 */
function agency_header_classes(): string {
    $style   = agency_page_setting( 'page_header_style', 'default' );
    $classes = [ 'site-header' ];

    if ( 'transparent' === $style ) $classes[] = 'site-header--transparent';
    if ( 'dark'        === $style ) $classes[] = 'site-header--dark';
    if ( 'minimal'     === $style ) $classes[] = 'site-header--minimal';

    return implode( ' ', $classes );
}

/**
 * Return the nav menu args for the primary nav.
 * Uses a per-page custom menu if set, otherwise falls back to the 'primary' location.
 */
function agency_primary_nav_args(): array {
    $custom_slug = agency_page_setting( 'page_custom_menu', '' );

    if ( ! empty( $custom_slug ) ) {
        return [
            'menu'        => $custom_slug,   // WP accepts menu slug here
            'container'   => false,
            'menu_class'  => 'nav-primary__list',
            'fallback_cb' => false,
            'depth'       => 2,
        ];
    }

    return [
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav-primary__list',
        'fallback_cb'    => false,
        'depth'          => 2,
    ];
}

// ─── ROUTE: use ACF if available, otherwise native meta box ──────────────────

add_action( 'acf/init',     '_agency_register_acf_page_settings' );
add_action( 'add_meta_boxes', '_agency_register_native_meta_box' );
add_action( 'save_post_page', '_agency_save_native_meta_box', 10, 2 );

// ─── ACF FIELD GROUP ─────────────────────────────────────────────────────────

function _agency_register_acf_page_settings(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_page_settings',
        'title'  => 'Page Settings',
        'fields' => [

            // ── Header tab ──────────────────────────────────────────────────
            [ 'key' => 'field_ps_tab_header', 'label' => 'Header', 'name' => '', 'type' => 'tab' ],
            [
                'key'           => 'field_ps_hide_header',
                'label'         => 'Hide Header',
                'name'          => 'page_hide_header',
                'type'          => 'true_false',
                'ui'            => 1,
                'default_value' => 0,
                'instructions'  => 'Completely remove the header on this page.',
            ],
            [
                'key'               => 'field_ps_header_style',
                'label'             => 'Header Style',
                'name'              => 'page_header_style',
                'type'              => 'select',
                'choices'           => [
                    'default'     => 'Default — white background',
                    'transparent' => 'Transparent — overlays the hero image',
                    'dark'        => 'Dark — dark background, light text',
                    'minimal'     => 'Minimal — logo only, no navigation',
                ],
                'default_value'     => 'default',
                'instructions'      => 'Choose how the header looks on this page.',
                'conditional_logic' => [
                    [ [ 'field' => 'field_ps_hide_header', 'operator' => '==', 'value' => '0' ] ],
                ],
            ],
            [
                'key'               => 'field_ps_custom_menu',
                'label'             => 'Custom Navigation Menu',
                'name'              => 'page_custom_menu',
                'type'              => 'select',
                'choices'           => [],  // populated via acf/load_field filter below
                'allow_null'        => 1,
                'instructions'      => 'Override the default Primary menu on this page.',
                'conditional_logic' => [
                    [
                        [ 'field' => 'field_ps_hide_header',  'operator' => '==', 'value' => '0' ],
                        [ 'field' => 'field_ps_header_style', 'operator' => '!=', 'value' => 'minimal' ],
                    ],
                ],
            ],

            // ── Footer tab ──────────────────────────────────────────────────
            [ 'key' => 'field_ps_tab_footer', 'label' => 'Footer', 'name' => '', 'type' => 'tab' ],
            [
                'key'           => 'field_ps_hide_footer',
                'label'         => 'Hide Footer',
                'name'          => 'page_hide_footer',
                'type'          => 'true_false',
                'ui'            => 1,
                'default_value' => 0,
                'instructions'  => 'Completely remove the footer on this page.',
            ],
            [
                'key'               => 'field_ps_footer_style',
                'label'             => 'Footer Style',
                'name'              => 'page_footer_style',
                'type'              => 'select',
                'choices'           => [
                    'default' => 'Default — full footer with columns',
                    'minimal' => 'Minimal — copyright bar only',
                ],
                'default_value'     => 'default',
                'instructions'      => 'Choose the footer layout for this page.',
                'conditional_logic' => [
                    [ [ 'field' => 'field_ps_hide_footer', 'operator' => '==', 'value' => '0' ] ],
                ],
            ],
        ],

        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ] ],
        ],

        'menu_order'            => 100,
        'position'              => 'side',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
    ] );
}

// Dynamically populate custom menu choices
add_filter( 'acf/load_field/key=field_ps_custom_menu', function ( array $field ): array {
    $field['choices'] = [];
    foreach ( (array) wp_get_nav_menus() as $menu ) {
        $field['choices'][ sanitize_title( $menu->slug ) ] = esc_html( $menu->name );
    }
    return $field;
} );

// ─── NATIVE META BOX (no ACF required) ───────────────────────────────────────

function _agency_register_native_meta_box(): void {
    // If ACF is handling the UI, skip the native box to avoid duplicates
    if ( function_exists( 'acf_add_local_field_group' ) ) return;

    add_meta_box(
        'agency-page-settings',
        __( 'Page Settings', 'golden-globe' ),
        '_agency_render_native_meta_box',
        'page',
        'side',
        'default'
    );
}

function _agency_render_native_meta_box( WP_Post $post ): void {
    wp_nonce_field( 'agency_page_settings_save', 'agency_page_settings_nonce' );

    $hide_header  = get_post_meta( $post->ID, 'page_hide_header',  true );
    $header_style = get_post_meta( $post->ID, 'page_header_style', true ) ?: 'default';
    $custom_menu  = get_post_meta( $post->ID, 'page_custom_menu',  true );
    $hide_footer  = get_post_meta( $post->ID, 'page_hide_footer',  true );
    $footer_style = get_post_meta( $post->ID, 'page_footer_style', true ) ?: 'default';

    $menus = wp_get_nav_menus();
    ?>
    <style>
        #agency-page-settings .ps-row { margin-bottom: 12px; }
        #agency-page-settings .ps-row label { display: block; font-weight: 600; margin-bottom: 4px; }
        #agency-page-settings select { width: 100%; }
        #agency-page-settings .ps-section-title {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: .5px; color: #666; border-bottom: 1px solid #ddd;
            padding-bottom: 4px; margin: 12px 0 8px;
        }
    </style>

    <div class="ps-section-title"><?php esc_html_e( 'Header', 'golden-globe' ); ?></div>

    <div class="ps-row">
        <label>
            <input type="checkbox" name="page_hide_header" value="1" <?php checked( $hide_header, '1' ); ?>>
            <?php esc_html_e( 'Hide header on this page', 'golden-globe' ); ?>
        </label>
    </div>

    <div class="ps-row">
        <label for="ps_header_style"><?php esc_html_e( 'Header Style', 'golden-globe' ); ?></label>
        <select id="ps_header_style" name="page_header_style">
            <option value="default"     <?php selected( $header_style, 'default' ); ?>><?php esc_html_e( 'Default — white background', 'golden-globe' ); ?></option>
            <option value="transparent" <?php selected( $header_style, 'transparent' ); ?>><?php esc_html_e( 'Transparent — overlays hero', 'golden-globe' ); ?></option>
            <option value="dark"        <?php selected( $header_style, 'dark' ); ?>><?php esc_html_e( 'Dark — dark bg, light text', 'golden-globe' ); ?></option>
            <option value="minimal"     <?php selected( $header_style, 'minimal' ); ?>><?php esc_html_e( 'Minimal — logo only', 'golden-globe' ); ?></option>
        </select>
    </div>

    <?php if ( ! empty( $menus ) ) : ?>
    <div class="ps-row">
        <label for="ps_custom_menu"><?php esc_html_e( 'Custom Navigation Menu', 'golden-globe' ); ?></label>
        <select id="ps_custom_menu" name="page_custom_menu">
            <option value=""><?php esc_html_e( '— Use default Primary menu —', 'golden-globe' ); ?></option>
            <?php foreach ( $menus as $menu ) : ?>
                <option value="<?php echo esc_attr( sanitize_title( $menu->slug ) ); ?>"
                        <?php selected( $custom_menu, sanitize_title( $menu->slug ) ); ?>>
                    <?php echo esc_html( $menu->name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>

    <div class="ps-section-title"><?php esc_html_e( 'Footer', 'golden-globe' ); ?></div>

    <div class="ps-row">
        <label>
            <input type="checkbox" name="page_hide_footer" value="1" <?php checked( $hide_footer, '1' ); ?>>
            <?php esc_html_e( 'Hide footer on this page', 'golden-globe' ); ?>
        </label>
    </div>

    <div class="ps-row">
        <label for="ps_footer_style"><?php esc_html_e( 'Footer Style', 'golden-globe' ); ?></label>
        <select id="ps_footer_style" name="page_footer_style">
            <option value="default" <?php selected( $footer_style, 'default' ); ?>><?php esc_html_e( 'Default — full footer', 'golden-globe' ); ?></option>
            <option value="minimal" <?php selected( $footer_style, 'minimal' ); ?>><?php esc_html_e( 'Minimal — copyright bar only', 'golden-globe' ); ?></option>
        </select>
    </div>
    <?php
}

function _agency_save_native_meta_box( int $post_id, WP_Post $post ): void {
    // Nonce check
    if ( ! isset( $_POST['agency_page_settings_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_key( $_POST['agency_page_settings_nonce'] ), 'agency_page_settings_save' ) ) return;

    // Capability check
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;

    // Skip autosave / revision
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) return;

    // page_hide_header — checkbox: present = '1', absent = '0'
    update_post_meta( $post_id, 'page_hide_header', isset( $_POST['page_hide_header'] ) ? '1' : '0' );

    // page_header_style — allowlist
    $allowed_header = [ 'default', 'transparent', 'dark', 'minimal' ];
    $header_style   = sanitize_text_field( wp_unslash( $_POST['page_header_style'] ?? 'default' ) );
    update_post_meta( $post_id, 'page_header_style', in_array( $header_style, $allowed_header, true ) ? $header_style : 'default' );

    // page_custom_menu — sanitise as slug
    update_post_meta( $post_id, 'page_custom_menu', sanitize_title( wp_unslash( $_POST['page_custom_menu'] ?? '' ) ) );

    // page_hide_footer — checkbox
    update_post_meta( $post_id, 'page_hide_footer', isset( $_POST['page_hide_footer'] ) ? '1' : '0' );

    // page_footer_style — allowlist
    $allowed_footer = [ 'default', 'minimal' ];
    $footer_style   = sanitize_text_field( wp_unslash( $_POST['page_footer_style'] ?? 'default' ) );
    update_post_meta( $post_id, 'page_footer_style', in_array( $footer_style, $allowed_footer, true ) ? $footer_style : 'default' );
}
