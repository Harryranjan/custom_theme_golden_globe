<?php
/**
 * SEO â€” Open Graph, Twitter Card, canonical, robots, JSON-LD structured data,
 *        BreadcrumbList, FAQ schema, and LCP preload.
 *
 * Gracefully skips if a dedicated SEO plugin (Yoast / RankMath / SEOPress)
 * is active, because those plugins already output these tags.
 */
defined('ABSPATH') || exit;

/**
 * Returns true when a known SEO plugin is handling meta output.
 */
function agency_seo_plugin_active(): bool {
    return defined('WPSEO_VERSION')       // Yoast SEO
        || defined('RANK_MATH_VERSION')   // RankMath
        || defined('SEOPRESS_VERSION');   // SEOPress
}

// â”€â”€â”€ 1. ROBOTS META â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Instructs Google not to index low-value pages that should never appear
// in search results (search results, 404, date/author archives, paginated
// duplicates, and non-public post statuses).

add_action('wp_head', function (): void {
    if (agency_seo_plugin_active()) return;

    $noindex = false;
    $nofollow = false;

    if (is_search())                                         $noindex = true; // ?s= pages
    if (is_404())                                            $noindex = true; // 404 error page
    if (is_date())                                           $noindex = true; // /2024/01/ date archives
    if (is_author() && get_the_author_posts() === 0)         $noindex = true; // authors with no posts
    if (is_paged() && (is_category() || is_tag() || is_tax())) $noindex = true; // page 2+ of taxonomy archives
    if (is_singular() && get_post_status() !== 'publish')   $noindex = true; // drafts, private etc previewed

    // Paginated home/blog (page 2+) â€” allow indexing but no canonical confusion
    if (is_home() && is_paged()) $noindex = true;

    if ($noindex || $nofollow) {
        $directives = [];
        if ($noindex)  $directives[] = 'noindex';
        if ($nofollow) $directives[] = 'nofollow';
        echo '<meta name="robots" content="' . esc_attr(implode(', ', $directives)) . '">' . "\n";
    } else {
        echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    }
}, 1);

// â”€â”€â”€ 2. LCP HERO IMAGE PRELOAD â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Emits a <link rel="preload"> for the featured image on single posts and pages.
// This directly improves Largest Contentful Paint (Core Web Vitals).

add_action('wp_head', function (): void {
    if (!is_singular()) return;

    $post_id = get_queried_object_id();
    if (!has_post_thumbnail($post_id)) return;

    $img_id = get_post_thumbnail_id($post_id);

    // Full srcset for responsive preload
    $full  = wp_get_attachment_image_src($img_id, 'hero-large');
    if (!$full) return;

    // Build a srcset from registered sizes for the preload imagesrcset attribute
    $srcset = wp_get_attachment_image_srcset($img_id, 'hero-large');
    $sizes  = '(max-width: 768px) 100vw, (max-width: 1280px) 1280px, 1920px';

    $tag = '<link rel="preload" as="image" href="' . esc_url($full[0]) . '"';
    if ($srcset) {
        $tag .= ' imagesrcset="' . esc_attr($srcset) . '"';
        $tag .= ' imagesizes="' . esc_attr($sizes) . '"';
    }
    $tag .= ' fetchpriority="high">' . "\n";

    echo $tag;
}, 2);

// â”€â”€â”€ 3. OPEN GRAPH + TWITTER CARD â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
// Extended to cover archives, categories, tags, and author pages
// in addition to singular posts/pages.

add_action('wp_head', function (): void {
    if (agency_seo_plugin_active()) return;

    $title       = '';
    $description = '';
    $canonical   = '';
    $image_url   = '';
    $image_w     = 0;
    $image_h     = 0;
    $type        = 'website';

    $site_name = get_bloginfo('name');

    if (is_front_page()) {
        $title       = $site_name;
        $description = get_bloginfo('description');
        $canonical   = home_url('/');

    } elseif (is_singular()) {
        $post_id     = get_queried_object_id();
        $title       = wp_strip_all_tags(get_the_title($post_id));
        $description = wp_strip_all_tags(get_the_excerpt($post_id));
        $canonical   = get_permalink($post_id);
        $type        = is_singular('post') ? 'article' : 'website';

        if (has_post_thumbnail($post_id)) {
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'og-image');
            if ($img) [$image_url, $image_w, $image_h] = $img;
        }

    } elseif (is_category() || is_tag() || is_tax()) {
        $term        = get_queried_object();
        $title       = $site_name . ' â€” ' . single_term_title('', false);
        $description = wp_strip_all_tags(term_description());
        $canonical   = get_term_link($term);

    } elseif (is_author()) {
        $author_id   = get_queried_object_id();
        $title       = $site_name . ' â€” ' . get_the_author_meta('display_name', $author_id);
        $description = wp_strip_all_tags(get_the_author_meta('description', $author_id));
        $canonical   = get_author_posts_url($author_id);

    } elseif (is_home()) {
        $blog_page   = get_option('page_for_posts');
        $title       = $blog_page ? wp_strip_all_tags(get_the_title($blog_page)) : $site_name;
        $description = get_bloginfo('description');
        $canonical   = $blog_page ? get_permalink($blog_page) : home_url('/');

    } else {
        return; // date archives, search etc â€” noindexed, skip OG
    }

    // Fallback image: site logo
    if (!$image_url) {
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $img = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($img) $image_url = $img[0];
        }
    }

    // Canonical
    if ($canonical) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }

    // Open Graph core
    echo '<meta property="og:type"        content="' . esc_attr($type)        . '">' . "\n";
    echo '<meta property="og:title"       content="' . esc_attr($title)       . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url"         content="' . esc_url($canonical)    . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr($site_name)   . '">' . "\n";
    echo '<meta property="og:locale"      content="' . esc_attr(get_locale()) . '">' . "\n";

    if ($image_url) {
        echo '<meta property="og:image"        content="' . esc_url($image_url)      . '">' . "\n";
        if ($image_w) echo '<meta property="og:image:width"  content="' . esc_attr($image_w) . '">' . "\n";
        if ($image_h) echo '<meta property="og:image:height" content="' . esc_attr($image_h) . '">' . "\n";
        echo '<meta property="og:image:alt"    content="' . esc_attr($title)         . '">' . "\n";
    }

    // Article-specific
    if ($type === 'article' && is_singular('post')) {
        $post_id = get_queried_object_id();
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c', $post_id))          . '">' . "\n";
        echo '<meta property="article:modified_time"  content="' . esc_attr(get_the_modified_date('c', $post_id)) . '">' . "\n";
        echo '<meta property="article:author"         content="' . esc_url(get_author_posts_url(get_post_field('post_author', $post_id))) . '">' . "\n";
        $cats = get_the_category($post_id);
        if (!empty($cats)) echo '<meta property="article:section" content="' . esc_attr($cats[0]->name) . '">' . "\n";
        $tags = get_the_tags($post_id);
        if ($tags && !is_wp_error($tags)) {
            foreach ($tags as $tag) echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
        }
    }

    // Twitter Card
    echo '<meta name="twitter:card"        content="' . ($image_url ? 'summary_large_image' : 'summary') . '">' . "\n";
    echo '<meta name="twitter:title"       content="' . esc_attr($title)       . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    if ($image_url) {
        echo '<meta name="twitter:image"     content="' . esc_url($image_url) . '">' . "\n";
        echo '<meta name="twitter:image:alt" content="' . esc_attr($title)    . '">' . "\n";
    }

    // Meta description
    if ($description) {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 30)) . '">' . "\n";
    }
}, 3);

// â”€â”€â”€ 4. JSON-LD STRUCTURED DATA (@graph) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

add_action('wp_head', function (): void {
    if (agency_seo_plugin_active()) return;

    $schemas = [];

    // â”€â”€ WebSite (every page) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    $schemas[] = [
        '@type'           => 'WebSite',
        '@id'             => home_url('/') . '#website',
        'url'             => home_url('/'),
        'name'            => get_bloginfo('name'),
        'description'     => get_bloginfo('description'),
        'inLanguage'      => get_locale(),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}'),
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // â”€â”€ Organization (every page) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    $org = [
        '@type' => 'Organization',
        '@id'   => home_url('/') . '#organization',
        'name'  => get_bloginfo('name'),
        'url'   => home_url('/'),
    ];
    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $logo_src = wp_get_attachment_image_url($logo_id, 'full');
        if ($logo_src) {
            $org['logo'] = [
                '@type'      => 'ImageObject',
                'url'        => $logo_src,
                'contentUrl' => $logo_src,
            ];
        }
    }
    $schemas[] = $org;

    // â”€â”€ Article + WebPage (single posts) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    if (is_singular('post')) {
        $post_id   = get_queried_object_id();
        $author_id = (int) get_post_field('post_author', $post_id);

        $article = [
            '@type'            => 'Article',
            '@id'              => get_permalink($post_id) . '#article',
            'isPartOf'         => ['@id' => get_permalink($post_id) . '#webpage'],
            'headline'         => wp_strip_all_tags(get_the_title($post_id)),
            'description'      => wp_strip_all_tags(get_the_excerpt($post_id)),
            'inLanguage'       => get_locale(),
            'datePublished'    => get_the_date('c', $post_id),
            'dateModified'     => get_the_modified_date('c', $post_id),
            'author'           => [
                '@type' => 'Person',
                '@id'   => get_author_posts_url($author_id) . '#person',
                'name'  => get_the_author_meta('display_name', $author_id),
                'url'   => get_author_posts_url($author_id),
            ],
            'publisher'        => ['@id' => home_url('/') . '#organization'],
            'mainEntityOfPage' => ['@id' => get_permalink($post_id) . '#webpage'],
            'wordCount'        => str_word_count(wp_strip_all_tags(get_the_content(null, false, $post_id))),
        ];

        if (has_post_thumbnail($post_id)) {
            $img_id  = get_post_thumbnail_id($post_id);
            $img_src = wp_get_attachment_image_src($img_id, 'og-image');
            $img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
            if ($img_src) {
                $article['image'] = [
                    '@type'      => 'ImageObject',
                    '@id'        => $img_src[0] . '#primaryimage',
                    'url'        => $img_src[0],
                    'contentUrl' => $img_src[0],
                    'width'      => $img_src[1],
                    'height'     => $img_src[2],
                    'caption'    => $img_alt ?: wp_strip_all_tags(get_the_title($post_id)),
                ];
            }
        }

        $cats = get_the_category($post_id);
        if (!empty($cats)) $article['articleSection'] = array_map(fn($c) => $c->name, $cats);
        $post_tags = get_the_tags($post_id);
        if ($post_tags && !is_wp_error($post_tags)) {
            $article['keywords'] = implode(', ', array_map(fn($t) => $t->name, $post_tags));
        }

        $schemas[] = $article;

        $webpage = [
            '@type'         => 'WebPage',
            '@id'           => get_permalink($post_id) . '#webpage',
            'url'           => get_permalink($post_id),
            'name'          => wp_strip_all_tags(get_the_title($post_id)),
            'isPartOf'      => ['@id' => home_url('/') . '#website'],
            'datePublished' => get_the_date('c', $post_id),
            'dateModified'  => get_the_modified_date('c', $post_id),
            'inLanguage'    => get_locale(),
        ];
        if (isset($article['image'])) {
            $webpage['primaryImageOfPage'] = ['@id' => $article['image']['@id']];
        }
        $schemas[] = $webpage;
    }

    // â”€â”€ CollectionPage (category / tag / taxonomy archive) â”€â”€â”€â”€â”€â”€â”€
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $schemas[] = [
            '@type'       => 'CollectionPage',
            '@id'         => get_term_link($term) . '#webpage',
            'url'         => get_term_link($term),
            'name'        => single_term_title('', false),
            'description' => wp_strip_all_tags(term_description()),
            'inLanguage'  => get_locale(),
            'isPartOf'    => ['@id' => home_url('/') . '#website'],
        ];
    }

    // â”€â”€ ProfilePage (author archive) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    if (is_author()) {
        $author_id = get_queried_object_id();
        $schemas[] = [
            '@type'      => 'ProfilePage',
            '@id'        => get_author_posts_url($author_id) . '#webpage',
            'url'        => get_author_posts_url($author_id),
            'name'       => get_the_author_meta('display_name', $author_id),
            'inLanguage' => get_locale(),
            'isPartOf'   => ['@id' => home_url('/') . '#website'],
            'about'      => [
                '@type' => 'Person',
                '@id'   => get_author_posts_url($author_id) . '#person',
                'name'  => get_the_author_meta('display_name', $author_id),
                'url'   => get_author_posts_url($author_id),
            ],
        ];
    }

    // â”€â”€ 4a. BreadcrumbList â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // Built for every context: singular, category, tag, author, home/blog.
    $breadcrumb_items = [];
    $position = 1;

    // Home is always item 1
    $breadcrumb_items[] = [
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => __('Home', 'golden-globe'),
        'item'     => home_url('/'),
    ];

    if (is_singular('post')) {
        $post_id     = get_queried_object_id();
        $blog_page   = get_option('page_for_posts');
        if ($blog_page) {
            $breadcrumb_items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => wp_strip_all_tags(get_the_title($blog_page)),
                'item'     => get_permalink($blog_page),
            ];
        }
        $primary_cat = agency_get_first_term($post_id, 'category');
        if ($primary_cat) {
            $breadcrumb_items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $primary_cat->name,
                'item'     => get_term_link($primary_cat),
            ];
        }
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => wp_strip_all_tags(get_the_title($post_id)),
            'item'     => get_permalink($post_id),
        ];

    } elseif (is_singular('page')) {
        $post_id = get_queried_object_id();
        // Walk up parent chain
        $ancestors = array_reverse((array) get_post_ancestors($post_id));
        foreach ($ancestors as $ancestor_id) {
            $breadcrumb_items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => wp_strip_all_tags(get_the_title($ancestor_id)),
                'item'     => get_permalink($ancestor_id),
            ];
        }
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => wp_strip_all_tags(get_the_title($post_id)),
            'item'     => get_permalink($post_id),
        ];

    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        // Walk up term ancestors
        if (!empty($term->parent)) {
            $ancestors = array_reverse(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy'));
            foreach ($ancestors as $ancestor_tid) {
                $ancestor_term = get_term($ancestor_tid, $term->taxonomy);
                if ($ancestor_term && !is_wp_error($ancestor_term)) {
                    $breadcrumb_items[] = [
                        '@type'    => 'ListItem',
                        'position' => $position++,
                        'name'     => $ancestor_term->name,
                        'item'     => get_term_link($ancestor_term),
                    ];
                }
            }
        }
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => single_term_title('', false),
            'item'     => get_term_link($term),
        ];

    } elseif (is_author()) {
        $author_id = get_queried_object_id();
        $breadcrumb_items[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => get_the_author_meta('display_name', $author_id),
            'item'     => get_author_posts_url($author_id),
        ];

    } elseif (is_home() && !is_front_page()) {
        $blog_page = get_option('page_for_posts');
        if ($blog_page) {
            $breadcrumb_items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => wp_strip_all_tags(get_the_title($blog_page)),
                'item'     => get_permalink($blog_page),
            ];
        }
    }

    if (count($breadcrumb_items) > 1) {
        $schemas[] = [
            '@type'           => 'BreadcrumbList',
            '@id'             => get_pagenum_link() . '#breadcrumb',
            'itemListElement' => $breadcrumb_items,
        ];
    }

    // â”€â”€ 4b. FAQPage (from ACF accordion on landing/page templates) â”€â”€
    // Reads the same 'page_sections' flexible content field we use for
    // the FAQ Accordion section on the Landing Page template.
    if (is_singular() && function_exists('have_rows') && function_exists('get_sub_field')) {
        $post_id = get_queried_object_id();
        $faq_entities = [];

        if (agency_have_rows('page_sections', $post_id)) {
            while (agency_have_rows('page_sections', $post_id)) {
                agency_the_row();
                if (get_row_layout() !== 'faq') continue;

                if (agency_have_rows('items')) {
                    while (agency_have_rows('items')) {
                        agency_the_row();
                        $q = wp_strip_all_tags(agency_get_sub_field('question'));
                        $a = wp_strip_all_tags(agency_get_sub_field('answer'));
                        if ($q && $a) {
                            $faq_entities[] = [
                                '@type'          => 'Question',
                                'name'           => $q,
                                'acceptedAnswer' => [
                                    '@type' => 'Answer',
                                    'text'  => $a,
                                ],
                            ];
                        }
                    }
                }
            }
        }

        if (!empty($faq_entities)) {
            $schemas[] = [
                '@type'      => 'FAQPage',
                '@id'        => get_permalink($post_id) . '#faqpage',
                'mainEntity' => $faq_entities,
            ];
        }
    }

    // Output @graph
    echo '<script type="application/ld+json">'
        . wp_json_encode(
            ['@context' => 'https://schema.org', '@graph' => $schemas],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
          )
        . '</script>' . "\n";
}, 5);
