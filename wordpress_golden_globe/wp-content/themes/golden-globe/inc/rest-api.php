<?php
defined('ABSPATH') || exit;

add_action('rest_api_init', function (): void {

    // GET /wp-json/golden-globe/v1/portfolio
    register_rest_route('golden-globe/v1', '/portfolio', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'agency_rest_get_portfolio',
        'permission_callback' => '__return_true',
        'args'                => [
            'category' => ['sanitize_callback' => 'sanitize_text_field'],
            'per_page' => ['sanitize_callback' => 'absint', 'default' => 9],
            'page'     => ['sanitize_callback' => 'absint', 'default' => 1],
        ],
    ]);
});

function agency_rest_get_portfolio(WP_REST_Request $request): WP_REST_Response {
    $category = $request->get_param('category');
    $per_page = $request->get_param('per_page');
    $page     = $request->get_param('page');

    $args = [
        'post_type'      => 'portfolio',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if (!empty($category) && $category !== 'all') {
        $args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
            'taxonomy' => 'portfolio_cat',
            'field'    => 'slug',
            'terms'    => $category,
        ]];
    }

    $query = new WP_Query($args);
    $items = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $items[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'excerpt'   => get_the_excerpt(),
                'permalink' => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
            ];
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response([
        'items'     => $items,
        'found'     => $query->found_posts,
        'max_pages' => $query->max_num_pages,
    ], 200);
}
