<?php
/**
 * Template Part: Breadcrumbs
 * Accessible, schema.org-structured breadcrumb trail.
 *
 * @package GoldenGlobe
 */

defined('ABSPATH') || exit;

// Don't show on the homepage.
if (is_front_page()) {
    return;
}

$items = [];

// Home crumb.
$items[] = [
    'label' => __('Home', 'golden-globe'),
    'url'   => home_url('/'),
];

// Category / taxonomy archive.
if (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    if ($term && $term->parent) {
        $parent = get_term($term->parent, $term->taxonomy);
        if ($parent && ! is_wp_error($parent)) {
            $items[] = [
                'label' => esc_html($parent->name),
                'url'   => get_term_link($parent),
            ];
        }
    }
    $items[] = [
        'label' => esc_html($term->name),
        'url'   => '',
    ];

// Single post / CPT.
} elseif (is_singular()) {
    $post = get_queried_object();

    // Post: show category.
    if (is_single() && 'post' === $post->post_type) {
        $cats = get_the_category($post->ID);
        if (! empty($cats)) {
            $items[] = [
                'label' => esc_html($cats[0]->name),
                'url'   => get_category_link($cats[0]->term_id),
            ];
        }
    }

    // CPT: show archive link.
    if (is_single() && 'post' !== $post->post_type) {
        $archive = get_post_type_archive_link($post->post_type);
        $obj     = get_post_type_object($post->post_type);
        if ($archive && $obj) {
            $items[] = [
                'label' => esc_html($obj->labels->name),
                'url'   => $archive,
            ];
        }
    }

    // Page: walk up parent chain.
    if (is_page() && $post->post_parent) {
        $ancestors = get_post_ancestors($post->ID);
        foreach (array_reverse($ancestors) as $ancestor_id) {
            $items[] = [
                'label' => esc_html(get_the_title($ancestor_id)),
                'url'   => get_permalink($ancestor_id),
            ];
        }
    }

    $items[] = [
        'label' => esc_html(get_the_title($post->ID)),
        'url'   => '',
    ];

// Post type archive.
} elseif (is_post_type_archive()) {
    $items[] = [
        'label' => esc_html(post_type_archive_title('', false)),
        'url'   => '',
    ];

// Search results.
} elseif (is_search()) {
    $items[] = [
        /* translators: %s: search query */
        'label' => sprintf(__('Search: %s', 'golden-globe'), get_search_query()),
        'url'   => '',
    ];

// 404.
} elseif (is_404()) {
    $items[] = [
        'label' => __('Page Not Found', 'golden-globe'),
        'url'   => '',
    ];
}

$total = count($items);
?>

<nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
    <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($items as $index => $crumb) :
            $position = $index + 1;
            $is_last  = ($position === $total);
        ?>
        <li class="breadcrumbs__item<?php echo $is_last ? ' breadcrumbs__item--current' : ''; ?>"
            itemprop="itemListElement"
            itemscope
            itemtype="https://schema.org/ListItem">

            <?php if (! $is_last && ! empty($crumb['url'])) : ?>
                <a class="breadcrumbs__link"
                   href="<?php echo esc_url($crumb['url']); ?>"
                   itemprop="item">
                    <span itemprop="name"><?php echo esc_html($crumb['label']); ?></span>
                </a>
            <?php else : ?>
                <span class="breadcrumbs__current" itemprop="name" aria-current="page">
                    <?php echo esc_html($crumb['label']); ?>
                </span>
            <?php endif; ?>

            <meta itemprop="position" content="<?php echo esc_attr($position); ?>" />

            <?php if (! $is_last) : ?>
                <span class="breadcrumbs__sep" aria-hidden="true">/</span>
            <?php endif; ?>

        </li>
        <?php endforeach; ?>
    </ol>
</nav>
