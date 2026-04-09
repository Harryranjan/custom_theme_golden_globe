<?php
defined('ABSPATH') || exit;

// ─── AJAX: Filter portfolio ───────────────────────────────────
function agency_ajax_filter_portfolio(): void {
    if (!check_ajax_referer('agency_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => __('Security check failed.', 'golden-globe')], 403);
    }

    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
    $page     = isset($_POST['page'])     ? absint($_POST['page']) : 1;
    $per_page = 9;

    $args = [
        'post_type'      => 'portfolio',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'no_found_rows'  => false,
    ];

    if (!empty($category) && $category !== 'all') {
        $args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
            'taxonomy' => 'portfolio_cat',
            'field'    => 'slug',
            'terms'    => $category,
        ]];
    }

    $query  = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/components/card', 'portfolio', [
                'post_id'    => get_the_ID(),
                'title'      => get_the_title(),
                'permalink'  => get_permalink(),
                'excerpt'    => get_the_excerpt(),
                'thumbnail'  => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                'categories' => get_the_terms(get_the_ID(), 'portfolio_cat'),
            ]);
        }
        $output = ob_get_clean();
        wp_reset_postdata();
    }

    wp_send_json_success([
        'html'      => $output,
        'found'     => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'current'   => $page,
        'has_more'  => $page < $query->max_num_pages,
    ]);
}
add_action('wp_ajax_filter_portfolio',        'agency_ajax_filter_portfolio');
add_action('wp_ajax_nopriv_filter_portfolio', 'agency_ajax_filter_portfolio');

// ─── AJAX: Filter services ────────────────────────────────────
function agency_ajax_filter_service(): void {
    if (!check_ajax_referer('agency_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => __('Security check failed.', 'golden-globe')], 403);
    }

    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
    $page     = isset($_POST['page'])     ? absint($_POST['page']) : 1;
    $per_page = 9;

    $args = [
        'post_type'      => 'service',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => false,
    ];

    if (!empty($category) && $category !== 'all') {
        $args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
            'taxonomy' => 'service_type',
            'field'    => 'slug',
            'terms'    => $category,
        ]];
    }

    $query  = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $sid     = get_the_ID();
            $types   = get_the_terms($sid, 'service_type') ?: [];
            $tagline = function_exists('get_field') ? agency_get_field('service_tagline', $sid) : '';
            $icon    = function_exists('get_field') ? agency_get_field('service_icon', $sid)    : null;
            get_template_part('template-parts/components/card', 'service', [
                'post_id'   => $sid,
                'title'     => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt'   => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url($sid, 'card-thumb'),
                'types'     => $types,
                'tagline'   => $tagline,
                'icon'      => $icon,
            ]);
        }
        $output = ob_get_clean();
        wp_reset_postdata();
    }

    wp_send_json_success([
        'html'      => $output,
        'found'     => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'current'   => $page,
        'has_more'  => $page < $query->max_num_pages,
    ]);
}
add_action('wp_ajax_filter_service',        'agency_ajax_filter_service');
add_action('wp_ajax_nopriv_filter_service', 'agency_ajax_filter_service');

/**
 * AJAX handler for the contact form submission.
 */
function agency_ajax_submit_contact_form(): void {
    // 1. Security Check: Nonce
    if (!check_ajax_referer('agency_contact_form_submit', 'nonce', false)) {
        wp_send_json_error(['message' => __('Security check failed.', 'golden-globe')], 403);
    }

    // 2. Spam Check: Honeypot
    if (!empty($_POST['agency_hp_field'])) {
        wp_send_json_error(['message' => __('Spam detected.', 'golden-globe')], 403);
    }

    // 3. Get and Sanitize Data
    $name    = sanitize_text_field(wp_unslash($_POST['agency_name']    ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['agency_email']       ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['agency_subject'] ?? __('New Contact Form Submission', 'golden-globe')));
    $message = sanitize_textarea_field(wp_unslash($_POST['agency_message'] ?? ''));

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(['message' => __('Please fill in all required fields.', 'golden-globe')]);
    }

    // 4. Get Form Config
    $options   = get_option('agency_theme_options');
    $to        = $options['form_recipient']   ?? get_option('admin_email');
    $success   = $options['form_success_msg'] ?? __('Success! Your message has been sent.', 'golden-globe');
    $error     = $options['form_error_msg']   ?? __('Error! Please try again later.', 'golden-globe');

    // 5. Build Email
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $body = "<h2>" . __('New Message from Contact Form', 'golden-globe') . "</h2>";
    $body .= "<p><strong>" . __('Name', 'golden-globe') . ":</strong> {$name}</p>";
    $body .= "<p><strong>" . __('Email', 'golden-globe') . ":</strong> {$email}</p>";
    $body .= "<p><strong>" . __('Subject', 'golden-globe') . ":</strong> {$subject}</p>";
    $body .= "<p><strong>" . __('Message', 'golden-globe') . ":</strong><br>" . nl2br($message) . "</p>";

    // 6. Send Mail
    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success(['message' => $success]);
    } else {
        wp_send_json_error(['message' => $error]);
    }
}
add_action('wp_ajax_submit_contact_form',        'agency_ajax_submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'agency_ajax_submit_contact_form');
