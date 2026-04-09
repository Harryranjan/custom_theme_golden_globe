# Agency-Grade WordPress Theme — Complete 10/10 Guide

> Full code, all 17 steps, production-ready

---

## TABLE OF CONTENTS

1. [Project Structure & Architecture](#1-project-structure--architecture)
2. [Design System & CSS Tokens](#2-design-system--css-tokens)
3. [Modular functions.php Bootstrapper](#3-modular-functionsphp-bootstrapper)
4. [inc/ — All PHP Logic Modules](#4-inc--all-php-logic-modules)
5. [Static Page Templates + ACF](#5-static-page-templates--acf)
6. [Dynamic CPTs, Taxonomies & AJAX](#6-dynamic-cpts-taxonomies--ajax)
7. [Component-Based PHP Architecture](#7-component-based-php-architecture)
8. [PHP Template Parts Architecture](#8-php-template-parts-architecture)
9. [Custom Gutenberg Blocks (ACF)](#9-custom-gutenberg-blocks-acf)
10. [REST API & Headless Support](#10-rest-api--headless-support)
11. [Theme Options Panel (ACF)](#11-theme-options-panel-acf)
12. [Accessibility (WCAG 2.1 AA)](#12-accessibility-wcag-21-aa)
13. [i18n / l10n — Translations](#13-i18n--l10n--translations)
14. [Security & DB Query Optimization](#14-security--db-query-optimization)
15. [Plain CSS & Vanilla JS (No Build Step)](#15-plain-css--vanilla-js-no-build-step)
16. [Automated Testing & CI/CD](#16-automated-testing--cicd)
17. [❌ What NOT To Do In This Workspace](#17--what-not-to-do-in-this-workspace)

---

## 1. Project Structure & Architecture

```
golden-globe/
│
├── assets/
│   ├── css/
│   │   └── main.css              ← All styles (CSS custom properties, no build step)
│   └── js/
│       └── main.js               ← All JS (flat vanilla JS, no bundler)
│
├── inc/
│   ├── setup.php                 ← Theme supports
│   ├── enqueue.php               ← Scripts & styles
│   ├── menus.php                 ← Nav menus
│   ├── widgets.php               ← Sidebar areas
│   ├── custom-post-types.php     ← CPTs & Taxonomies
│   ├── acf-fields.php            ← ACF field groups (code-registered)
│   ├── acf-blocks.php            ← Gutenberg ACF blocks
│   ├── rest-api.php              ← Custom REST endpoints
│   ├── ajax-handlers.php         ← AJAX actions
│   ├── theme-options.php         ← ACF global options page
│   ├── security.php              ← Hardening
│   ├── performance.php           ← Optimizations
│   ├── accessibility.php         ← A11y helpers
│   ├── i18n.php                  ← Translation setup
│   ├── helpers.php               ← Utility functions
│   ├── shortcodes.php            ← Custom shortcodes
│   └── class-component.php      ← Component renderer class
│
├── template-parts/
│   ├── global/
│   │   ├── site-header.php
│   │   ├── site-footer.php
│   │   ├── skip-link.php
│   │   └── breadcrumbs.php
│   ├── components/
│   │   ├── card.php
│   │   ├── hero.php
│   │   ├── cta.php
│   │   ├── testimonial.php
│   │   └── pagination.php
│   └── blocks/
│       ├── block-hero.php
│       ├── block-cards.php
│       ├── block-cta.php
│       └── block-testimonials.php
│
├── page-templates/
│   ├── template-landing.php
│   ├── template-fullwidth.php
│   ├── template-static.php
│   └── template-home.php
│
├── languages/
│   └── golden-globe.pot
│
├── tests/
│   ├── phpunit/
│   │   └── test-helpers.php
│   └── e2e/
│       └── homepage.spec.js
│
├── .github/
│   └── workflows/
│       └── deploy.yml
│
├── functions.php
├── index.php
├── header.php
├── footer.php
├── single.php
├── page.php
├── archive.php
├── search.php
├── 404.php
├── style.css
├── screenshot.png
├── phpunit.xml
└── .gitignore
```

---

## 2. Design System & CSS Tokens

### `style.css` — Theme Header

```css
/*
Theme Name:  Agency Theme
Author:      Your Agency Name
Description: Production-grade agency WordPress theme
Version:     1.0.0
Text Domain: golden-globe
Tags:        custom-background, custom-logo, custom-menu, featured-images, threaded-comments
*/
```

### `assets/css/main.css` — Design Tokens (CSS Custom Properties)

```css
/* =============================================================
   GOLDEN GLOBE — MAIN STYLESHEET
   Plain CSS — no build step required
   ============================================================= */

/* ── 1. CSS CUSTOM PROPERTIES (Design Tokens) ── */
:root {
  /* Colors */
  --color-primary: #2563eb;
  --color-primary-dark: #1d4ed8;
  --color-primary-light: #dbeafe;
  --color-secondary: #64748b;
  --color-accent: #f59e0b;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-danger: #ef4444;
  --color-white: #ffffff;
  --color-black: #0f172a;
  --color-gray-50: #f8fafc;
  --color-gray-100: #f1f5f9;
  --color-gray-200: #e2e8f0;
  --color-gray-300: #cbd5e1;
  --color-gray-400: #94a3b8;
  --color-gray-500: #64748b;
  --color-gray-600: #475569;
  --color-gray-700: #334155;
  --color-gray-800: #1e293b;
  --color-gray-900: #0f172a;

  /* Typography */
  --font-sans: "Inter", system-ui, -apple-system, sans-serif;
  --font-serif: "Playfair Display", Georgia, serif;
  --font-mono: "JetBrains Mono", "Fira Code", monospace;

  --font-size-xs: 0.75rem;
  --font-size-sm: 0.875rem;
  --font-size-base: 1rem;
  --font-size-md: 1.125rem;
  --font-size-lg: 1.25rem;
  --font-size-xl: 1.5rem;
  --font-size-2xl: 1.875rem;
  --font-size-3xl: 2.25rem;
  --font-size-4xl: 3rem;
  --font-size-5xl: 3.75rem;

  --font-weight-light: 300;
  --font-weight-regular: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-black: 900;

  --line-height-tight: 1.2;
  --line-height-snug: 1.375;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.625;
  --line-height-loose: 2;

  /* Spacing (8pt grid) */
  --spacing-1: 0.25rem;
  --spacing-2: 0.5rem;
  --spacing-3: 0.75rem;
  --spacing-4: 1rem;
  --spacing-5: 1.25rem;
  --spacing-6: 1.5rem;
  --spacing-8: 2rem;
  --spacing-10: 2.5rem;
  --spacing-12: 3rem;
  --spacing-16: 4rem;
  --spacing-20: 5rem;
  --spacing-24: 6rem;
  --spacing-32: 8rem;

  /* Border Radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-2xl: 24px;
  --radius-full: 9999px;

  /* Shadows */
  --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
  --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07), 0 2px 4px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
  --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.04);
  --shadow-2xl: 0 25px 50px rgba(0, 0, 0, 0.25);

  /* Transitions */
  --transition-fast: 150ms ease;
  --transition-normal: 300ms ease;
  --transition-slow: 500ms ease;

  /* Breakpoints (reference only — use media queries directly) */
  /* sm: 640px | md: 768px | lg: 1024px | xl: 1280px | 2xl: 1536px */

  /* Z-index scale */
  --z-behind: -1;
  --z-base: 0;
  --z-raised: 10;
  --z-dropdown: 100;
  --z-sticky: 200;
  --z-overlay: 300;
  --z-modal: 400;
  --z-toast: 500;

  /* Container */
  --container-max: 1280px;
  --container-padding: var(--spacing-6);
}
```

### Layout utilities in `assets/css/main.css`

```css
/* ── Container ── */
.container {
  width: 100%;
  max-width: var(--container-max);
  margin-inline: auto;
  padding-inline: var(--container-padding);
}

@media (min-width: 1024px) {
  .container {
    padding-inline: var(--spacing-8);
  }
}

/* ── Grid ── */
.grid {
  display: grid;
  gap: var(--spacing-6);
}
.grid--2 {
  grid-template-columns: repeat(2, 1fr);
}
.grid--3 {
  grid-template-columns: repeat(3, 1fr);
}
.grid--4 {
  grid-template-columns: repeat(4, 1fr);
}
.grid--auto {
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
}

@media (max-width: 768px) {
  .grid--2,
  .grid--3,
  .grid--4 {
    grid-template-columns: 1fr;
  }
}

/* ── Section spacing ── */
.section {
  padding-block: var(--spacing-20);
}
.section--sm {
  padding-block: var(--spacing-12);
}
.section--lg {
  padding-block: var(--spacing-32);
}
.section--none {
  padding-block: 0;
}
```

---

## 3. Modular functions.php Bootstrapper

```php
<?php
/**
 * Agency Theme — functions.php
 * Clean bootstrapper — loads all inc/ modules
 */

defined('ABSPATH') || exit;

// Theme version constant
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
    '/inc/acf-fields.php',
    '/inc/acf-blocks.php',
    '/inc/rest-api.php',
    '/inc/ajax-handlers.php',
    '/inc/theme-options.php',
    '/inc/security.php',
    '/inc/performance.php',
    '/inc/accessibility.php',
    '/inc/i18n.php',
    '/inc/helpers.php',
    '/inc/shortcodes.php',
    '/inc/class-component.php',
];

foreach ($modules as $module) {
    $path = AGENCY_THEME_DIR . $module;
    if (file_exists($path)) {
        require_once $path;
    }
}
```

---

## 4. inc/ — All PHP Logic Modules

### `inc/setup.php`

```php
<?php
defined('ABSPATH') || exit;

function agency_theme_setup(): void {
    // Language support
    load_theme_textdomain('golden-globe', AGENCY_THEME_DIR . '/languages');

    // Automatic feed links
    add_theme_support('automatic-feed-links');

    // Title tag (never hardcode <title>)
    add_theme_support('title-tag');

    // Post thumbnails
    add_theme_support('post-thumbnails');

    // Custom image sizes
    add_image_size('card-thumb',    600, 400, true);
    add_image_size('hero-large',   1920, 800, true);
    add_image_size('hero-medium',  1280, 600, true);
    add_image_size('team-square',   500, 500, true);
    add_image_size('og-image',     1200, 630, true);

    // HTML5 support
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ]);

    // Custom logo
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Custom background
    add_theme_support('custom-background');

    // Gutenberg wide/full alignment
    add_theme_support('align-wide');

    // Gutenberg block styles
    add_theme_support('wp-block-styles');

    // Responsive embeds
    add_theme_support('responsive-embeds');

    // Editor color palette (matches design tokens)
    add_theme_support('editor-color-palette', [
        ['name' => __('Primary',   'golden-globe'), 'slug' => 'primary',   'color' => '#2563eb'],
        ['name' => __('Secondary', 'golden-globe'), 'slug' => 'secondary', 'color' => '#64748b'],
        ['name' => __('Accent',    'golden-globe'), 'slug' => 'accent',    'color' => '#f59e0b'],
        ['name' => __('Dark',      'golden-globe'), 'slug' => 'dark',      'color' => '#0f172a'],
        ['name' => __('Light',     'golden-globe'), 'slug' => 'light',     'color' => '#f8fafc'],
    ]);

    // Editor font sizes
    add_theme_support('editor-font-sizes', [
        ['name' => __('Small',  'golden-globe'), 'shortName' => __('S', 'golden-globe'), 'size' => 14, 'slug' => 'small'],
        ['name' => __('Normal', 'golden-globe'), 'shortName' => __('M', 'golden-globe'), 'size' => 16, 'slug' => 'normal'],
        ['name' => __('Large',  'golden-globe'), 'shortName' => __('L', 'golden-globe'), 'size' => 20, 'slug' => 'large'],
        ['name' => __('Huge',   'golden-globe'), 'shortName' => __('XL','golden-globe'), 'size' => 30, 'slug' => 'huge'],
    ]);

    // Content width
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1280;
    }
}
add_action('after_setup_theme', 'agency_theme_setup');
```

### `inc/enqueue.php`

```php
<?php
defined('ABSPATH') || exit;

function agency_enqueue_scripts(): void {
    $ver = AGENCY_THEME_VERSION;
    $uri = AGENCY_THEME_URI;

    // Google Fonts (preconnect first)
    wp_enqueue_style(
        'google-fonts-preconnect',
        'https://fonts.googleapis.com',
        [], null
    );

    // Main stylesheet
    wp_enqueue_style(
        'agency-main',
        $uri . '/assets/css/main.css',
        [],
        $ver
    );

    // Gutenberg editor styles
    add_editor_style('assets/css/editor.css');

    // Main JS (module, deferred)
    wp_enqueue_script(
        'agency-main',
        $uri . '/assets/js/main.js',
        [],
        $ver,
        ['strategy' => 'defer', 'in_footer' => true]
    );

    // Localize script (pass PHP data to JS)
    wp_localize_script('agency-main', 'AgencyTheme', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'restUrl'   => esc_url_raw(rest_url('mytheme/v1/')),
        'nonce'     => wp_create_nonce('agency_nonce'),
        'restNonce' => wp_create_nonce('wp_rest'),
        'isLoggedIn'=> is_user_logged_in(),
        'i18n'      => [
            'loading'  => __('Loading...', 'golden-globe'),
            'noMore'   => __('No more posts', 'golden-globe'),
            'error'    => __('Something went wrong', 'golden-globe'),
        ],
    ]);

    // Comment reply script (only where needed)
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'agency_enqueue_scripts');

// Admin scripts
function agency_admin_scripts(string $hook): void {
    wp_enqueue_style(
        'agency-admin',
        AGENCY_THEME_URI . '/assets/css/admin.css',
        [],
        AGENCY_THEME_VERSION
    );
}
add_action('admin_enqueue_scripts', 'agency_admin_scripts');

// Defer all theme scripts
add_filter('script_loader_tag', function (string $tag, string $handle): string {
    $defer_handles = ['agency-main'];
    if (in_array($handle, $defer_handles, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);
```

### `inc/menus.php`

```php
<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    register_nav_menus([
        'primary'   => __('Primary Navigation', 'golden-globe'),
        'footer'    => __('Footer Navigation',  'golden-globe'),
        'mobile'    => __('Mobile Navigation',  'golden-globe'),
        'social'    => __('Social Links',        'golden-globe'),
    ]);
});
```

### `inc/widgets.php`

```php
<?php
defined('ABSPATH') || exit;

function agency_register_sidebars(): void {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar(array_merge($defaults, [
        'name' => __('Sidebar', 'golden-globe'),
        'id'   => 'sidebar-main',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 1', 'golden-globe'),
        'id'   => 'footer-col-1',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 2', 'golden-globe'),
        'id'   => 'footer-col-2',
    ]));

    register_sidebar(array_merge($defaults, [
        'name' => __('Footer Column 3', 'golden-globe'),
        'id'   => 'footer-col-3',
    ]));
}
add_action('widgets_init', 'agency_register_sidebars');
```

### `inc/custom-post-types.php`

```php
<?php
defined('ABSPATH') || exit;

// ─── REGISTER CPTs ───────────────────────────────────────────
function agency_register_post_types(): void {

    // PORTFOLIO
    register_post_type('portfolio', [
        'labels' => [
            'name'               => __('Portfolio',    'golden-globe'),
            'singular_name'      => __('Project',      'golden-globe'),
            'add_new_item'       => __('Add New Project', 'golden-globe'),
            'edit_item'          => __('Edit Project', 'golden-globe'),
            'new_item'           => __('New Project',  'golden-globe'),
            'view_item'          => __('View Project', 'golden-globe'),
            'search_items'       => __('Search Projects', 'golden-globe'),
            'not_found'          => __('No projects found', 'golden-globe'),
            'not_found_in_trash' => __('No projects found in trash', 'golden-globe'),
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,  // Gutenberg + REST API
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'portfolio', 'with_front' => false],
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon'           => 'dashicons-portfolio',
        'menu_position'       => 5,
    ]);

    // SERVICES
    register_post_type('service', [
        'labels' => [
            'name'          => __('Services',    'golden-globe'),
            'singular_name' => __('Service',     'golden-globe'),
            'add_new_item'  => __('Add Service', 'golden-globe'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'    => 'dashicons-admin-tools',
        'menu_position'=> 6,
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
            'name'          => __('Team', 'golden-globe'),
            'singular_name' => __('Team Member', 'golden-globe'),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'team'],
        'supports'     => ['title', 'editor', 'thumbnail'],
        'menu_icon'    => 'dashicons-groups',
        'menu_position'=> 8,
    ]);
}
add_action('init', 'agency_register_post_types');

// ─── REGISTER TAXONOMIES ─────────────────────────────────────
function agency_register_taxonomies(): void {

    // Portfolio Category
    register_taxonomy('portfolio_cat', 'portfolio', [
        'labels' => [
            'name'          => __('Portfolio Categories', 'golden-globe'),
            'singular_name' => __('Portfolio Category',  'golden-globe'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'portfolio-category'],
    ]);

    // Service Type
    register_taxonomy('service_type', 'service', [
        'labels' => [
            'name'          => __('Service Types',  'golden-globe'),
            'singular_name' => __('Service Type',   'golden-globe'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'service-type'],
    ]);

    // Industry (cross-CPT)
    register_taxonomy('industry', ['portfolio', 'service'], [
        'labels' => [
            'name'          => __('Industries',  'golden-globe'),
            'singular_name' => __('Industry',    'golden-globe'),
        ],
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'industry'],
    ]);
}
add_action('init', 'agency_register_taxonomies');
```

### `inc/helpers.php`

```php
<?php
defined('ABSPATH') || exit;

/**
 * Render a template part with context variables.
 *
 * @param string $slug  Path under template-parts/ (e.g. 'components/card')
 * @param array  $args  Variables to extract into template scope
 */
function agency_render(string $slug, array $args = []): void {
    // Extract args safely into local scope
    if (!empty($args)) {
        extract($args, EXTR_PREFIX_ALL, 'tpl'); // Prefixed to avoid conflicts
    }
    get_template_part('template-parts/' . $slug, null, $args);
}

/**
 * Get adjacent posts for prev/next navigation.
 */
function agency_get_adjacent_posts(): array {
    return [
        'prev' => get_previous_post(),
        'next' => get_next_post(),
    ];
}

/**
 * Get a formatted ACF date field.
 */
function agency_acf_date(string $field, int $post_id = 0, string $format = 'F j, Y'): string {
    $raw = get_field($field, $post_id ?: null);
    if (!$raw) return '';
    return date_i18n($format, strtotime($raw));
}

/**
 * Truncate text to a word limit.
 */
function agency_excerpt(string $text, int $words = 20): string {
    return wp_trim_words(wp_strip_all_tags($text), $words, '&hellip;');
}

/**
 * Get the first term of a taxonomy for a post.
 */
function agency_get_first_term(int $post_id, string $taxonomy): ?WP_Term {
    $terms = get_the_terms($post_id, $taxonomy);
    return (!empty($terms) && !is_wp_error($terms)) ? reset($terms) : null;
}

/**
 * Decode a hex color to rgba.
 */
function agency_hex_to_rgba(string $hex, float $alpha = 1): string {
    $hex = ltrim($hex, '#');
    [$r, $g, $b] = str_split(strlen($hex) === 3
        ? implode('', array_map(fn($c) => $c . $c, str_split($hex)))
        : $hex, 2);
    return "rgba({$r},{$g},{$b},{$alpha})";
}

/**
 * Check if the current page uses a specific template.
 */
function agency_is_template(string $template): bool {
    return is_page_template("page-templates/{$template}.php");
}

/**
 * Output SVG icon from theme icons folder.
 */
function agency_icon(string $name, string $class = ''): void {
    $path = AGENCY_THEME_DIR . "/assets/images/icons/{$name}.svg";
    if (file_exists($path)) {
        $svg = file_get_contents($path);
        if ($class) {
            $svg = str_replace('<svg', "<svg class=\"{$class}\"", $svg);
        }
        echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput
    }
}
```

---

## 5. Static Page Templates + ACF

### `inc/acf-fields.php` — Code-Registered Fields

```php
<?php
defined('ABSPATH') || exit;

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) return;

    // ─── HERO SECTION ───
    acf_add_local_field_group([
        'key'    => 'group_hero',
        'title'  => 'Hero Section',
        'fields' => [
            ['key' => 'field_hero_heading',    'label' => 'Heading',       'name' => 'hero_heading',    'type' => 'text'],
            ['key' => 'field_hero_subheading', 'label' => 'Sub Heading',   'name' => 'hero_subheading', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_hero_image',      'label' => 'Background',    'name' => 'hero_image',      'type' => 'image', 'return_format' => 'array', 'preview_size' => 'hero-medium'],
            ['key' => 'field_hero_btn_label',  'label' => 'Button Label',  'name' => 'hero_btn_label',  'type' => 'text'],
            ['key' => 'field_hero_btn_url',    'label' => 'Button URL',    'name' => 'hero_btn_url',    'type' => 'url'],
            ['key' => 'field_hero_layout',     'label' => 'Layout',        'name' => 'hero_layout',     'type' => 'select',
                'choices' => ['centered' => 'Centered', 'left' => 'Left Aligned', 'split' => 'Split (Text + Image)'],
                'default_value' => 'centered',
            ],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-landing.php']]],
        'menu_order' => 0,
    ]);

    // ─── FLEXIBLE CONTENT SECTIONS ───
    acf_add_local_field_group([
        'key'    => 'group_page_sections',
        'title'  => 'Page Sections',
        'fields' => [
            [
                'key'     => 'field_sections',
                'label'   => 'Sections',
                'name'    => 'page_sections',
                'type'    => 'flexible_content',
                'layouts' => [
                    // Features Grid
                    'layout_features' => [
                        'key'        => 'layout_features',
                        'name'       => 'features_grid',
                        'label'      => 'Features Grid',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_feat_title',   'label' => 'Section Title', 'name' => 'title',    'type' => 'text'],
                            ['key' => 'field_feat_items',   'label' => 'Features',      'name' => 'items',    'type' => 'repeater',
                                'sub_fields' => [
                                    ['key' => 'field_feat_icon',  'label' => 'Icon',        'name' => 'icon',  'type' => 'image'],
                                    ['key' => 'field_feat_name',  'label' => 'Name',        'name' => 'name',  'type' => 'text'],
                                    ['key' => 'field_feat_desc',  'label' => 'Description', 'name' => 'desc',  'type' => 'textarea'],
                                ],
                            ],
                        ],
                    ],
                    // CTA Banner
                    'layout_cta' => [
                        'key'        => 'layout_cta',
                        'name'       => 'cta_banner',
                        'label'      => 'CTA Banner',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_cta_title',    'label' => 'Title',        'name' => 'title',     'type' => 'text'],
                            ['key' => 'field_cta_text',     'label' => 'Text',         'name' => 'text',      'type' => 'textarea'],
                            ['key' => 'field_cta_btn',      'label' => 'Button',       'name' => 'button',    'type' => 'link'],
                            ['key' => 'field_cta_bg',       'label' => 'Background Color', 'name' => 'bg_color', 'type' => 'color_picker'],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-landing.php']]],
        'menu_order' => 10,
    ]);
});
```

### `page-templates/template-landing.php`

```php
<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 */

defined('ABSPATH') || exit;

get_header();

// Hero fields
$hero_heading    = get_field('hero_heading');
$hero_subheading = get_field('hero_subheading');
$hero_image      = get_field('hero_image');
$hero_btn_label  = get_field('hero_btn_label');
$hero_btn_url    = get_field('hero_btn_url');
$hero_layout     = get_field('hero_layout') ?: 'centered';
?>

<main id="main-content" class="landing-page">

    <!-- HERO -->
    <?php agency_render('components/hero', compact(
        'hero_heading', 'hero_subheading',
        'hero_image', 'hero_btn_label',
        'hero_btn_url', 'hero_layout'
    )); ?>

    <!-- FLEXIBLE CONTENT SECTIONS -->
    <?php if (have_rows('page_sections')) : ?>
        <?php while (have_rows('page_sections')) : the_row(); ?>

            <?php if (get_row_layout() === 'features_grid') : ?>
                <section class="section section--features">
                    <div class="container">
                        <h2><?php echo esc_html(get_sub_field('title')); ?></h2>
                        <div class="grid grid--3">
                            <?php if (have_rows('items')) : ?>
                                <?php while (have_rows('items')) : the_row(); ?>
                                    <div class="feature-card">
                                        <?php $icon = get_sub_field('icon'); ?>
                                        <?php if ($icon) : ?>
                                            <img src="<?php echo esc_url($icon['url']); ?>"
                                                 alt="<?php echo esc_attr($icon['alt']); ?>"
                                                 width="48" height="48" loading="lazy">
                                        <?php endif; ?>
                                        <h3><?php echo esc_html(get_sub_field('name')); ?></h3>
                                        <p><?php echo esc_html(get_sub_field('desc')); ?></p>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

            <?php elseif (get_row_layout() === 'cta_banner') : ?>
                <?php
                $cta_btn = get_sub_field('button');
                $cta_bg  = get_sub_field('bg_color') ?: '#2563eb';
                ?>
                <section class="section section--cta" style="background-color:<?php echo esc_attr($cta_bg); ?>">
                    <div class="container container--narrow">
                        <h2><?php echo esc_html(get_sub_field('title')); ?></h2>
                        <p><?php echo esc_html(get_sub_field('text')); ?></p>
                        <?php if ($cta_btn) : ?>
                            <a href="<?php echo esc_url($cta_btn['url']); ?>"
                               class="btn btn--white"
                               <?php echo $cta_btn['target'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo esc_html($cta_btn['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </section>

            <?php endif; ?>

        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
```

---

## 6. Dynamic CPTs, Taxonomies & AJAX

### `inc/ajax-handlers.php` — AJAX Filter with Nonce

```php
<?php
defined('ABSPATH') || exit;

/**
 * AJAX: Filter portfolio items
 * Called via JS: $.post(ajaxUrl, { action: 'filter_portfolio', category, page, nonce })
 */
function agency_ajax_filter_portfolio(): void {
    // Security check
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
        'no_found_rows'  => false, // We need pagination info
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
```

### `assets/js/main.js` — AJAX Filter IIFE

```javascript
/* ── AJAX Portfolio Filter ── */
(function () {
  var container = document.querySelector(".portfolio-grid");
  if (!container) return;

  var grid = container.querySelector("[data-grid]");
  var filters = container.querySelectorAll("[data-filter]");
  var loadMoreBtn = container.querySelector("[data-load-more]");
  var currentPage = 1;
  var currentCat = "all";
  var isLoading = false;

  function setLoadingState(loading) {
    container.classList.toggle("is-loading", loading);
    if (loadMoreBtn) loadMoreBtn.disabled = loading;
  }

  function updateLoadMore(hasMore) {
    if (!loadMoreBtn) return;
    loadMoreBtn.style.display = hasMore ? "inline-flex" : "none";
  }

  function fetchPosts(replace) {
    if (isLoading) return;
    isLoading = true;
    setLoadingState(true);

    var body = new FormData();
    body.append("action", "filter_portfolio");
    body.append(
      "nonce",
      (window.AgencyTheme && window.AgencyTheme.nonce) || "",
    );
    body.append("category", currentCat);
    body.append("page", String(currentPage));

    fetch(
      (window.AgencyTheme && window.AgencyTheme.ajaxUrl) ||
        "/wp-admin/admin-ajax.php",
      {
        method: "POST",
        body: body,
      },
    )
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        if (data.success && grid) {
          if (replace) {
            grid.innerHTML = data.data.html;
          } else {
            grid.insertAdjacentHTML("beforeend", data.data.html);
          }
          updateLoadMore(data.data.has_more);
        }
      })
      .catch(function (err) {
        console.error("AjaxFilter error:", err);
      })
      .finally(function () {
        isLoading = false;
        setLoadingState(false);
      });
  }

  filters.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      filters.forEach(function (b) {
        b.setAttribute("aria-pressed", "false");
      });
      btn.setAttribute("aria-pressed", "true");
      currentPage = 1;
      currentCat = btn.dataset.filter;
      fetchPosts(true);
    });
  });

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", function () {
      currentPage++;
      fetchPosts(false);
    });
  }
})();
```

---

## 7. Component-Based PHP Architecture

### `inc/class-component.php`

```php
<?php
defined('ABSPATH') || exit;

/**
 * Component Renderer
 * Renders template parts with typed props and validation.
 */
class Agency_Component {

    private static array $registry = [];

    /**
     * Register a component schema.
     *
     * @param string $name   Component name (e.g. 'card')
     * @param array  $schema Prop definitions with types and defaults
     */
    public static function register(string $name, array $schema): void {
        self::$registry[$name] = $schema;
    }

    /**
     * Render a component.
     *
     * @param string $name  Component name
     * @param array  $props Props to pass
     */
    public static function render(string $name, array $props = []): void {
        // Merge defaults
        if (isset(self::$registry[$name])) {
            $schema = self::$registry[$name];
            foreach ($schema as $key => $config) {
                if (!isset($props[$key]) && isset($config['default'])) {
                    $props[$key] = $config['default'];
                }
            }
        }

        get_template_part("template-parts/components/{$name}", null, $props);
    }

    /**
     * Render and return output as string.
     */
    public static function get(string $name, array $props = []): string {
        ob_start();
        self::render($name, $props);
        return ob_get_clean();
    }
}

// ─── Register Components ───────────────────────────────────
Agency_Component::register('card', [
    'post_id'   => ['type' => 'int',    'default' => 0],
    'title'     => ['type' => 'string', 'default' => ''],
    'permalink' => ['type' => 'string', 'default' => '#'],
    'excerpt'   => ['type' => 'string', 'default' => ''],
    'thumbnail' => ['type' => 'string', 'default' => ''],
    'categories'=> ['type' => 'array',  'default' => []],
    'variant'   => ['type' => 'string', 'default' => 'default'],
]);

Agency_Component::register('hero', [
    'heading'    => ['type' => 'string', 'default' => ''],
    'subheading' => ['type' => 'string', 'default' => ''],
    'image'      => ['type' => 'array',  'default' => []],
    'btn_label'  => ['type' => 'string', 'default' => ''],
    'btn_url'    => ['type' => 'string', 'default' => '#'],
    'layout'     => ['type' => 'string', 'default' => 'centered'],
]);
```

### `template-parts/components/card.php`

```php
<?php
defined('ABSPATH') || exit;

// Props (passed via get_template_part 3rd arg)
$post_id    = $args['post_id']    ?? 0;
$title      = $args['title']      ?? '';
$permalink  = $args['permalink']  ?? '#';
$excerpt    = $args['excerpt']    ?? '';
$thumbnail  = $args['thumbnail']  ?? '';
$categories = $args['categories'] ?? [];
$variant    = $args['variant']    ?? 'default';

$card_class = 'card card--' . esc_attr($variant);
?>

<article class="<?php echo $card_class; ?>" data-post-id="<?php echo esc_attr($post_id); ?>">

    <?php if ($thumbnail) : ?>
    <div class="card__image">
        <a href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
            <img src="<?php echo esc_url($thumbnail); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy"
                 width="600"
                 height="400">
        </a>
    </div>
    <?php endif; ?>

    <div class="card__body">

        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
        <div class="card__cats" aria-label="<?php esc_attr_e('Categories', 'golden-globe'); ?>">
            <?php foreach ($categories as $cat) : ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                   class="tag">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h3 class="card__title">
            <a href="<?php echo esc_url($permalink); ?>">
                <?php echo esc_html($title); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
        <p class="card__excerpt"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_url($permalink); ?>"
           class="card__link btn btn--ghost"
           aria-label="<?php printf(esc_attr__('Read more about %s', 'golden-globe'), esc_attr($title)); ?>">
            <?php esc_html_e('View Project', 'golden-globe'); ?>
            <span aria-hidden="true">→</span>
        </a>

    </div>

</article>
```

---

## 8. PHP Template Parts Architecture

No Timber or Twig — all templating is pure PHP using `get_template_part()` with an `$args` array.

### How template parts work

```php
// Caller passes data as $args array
get_template_part('template-parts/components/card', null, [
    'post_id'   => get_the_ID(),
    'title'     => get_the_title(),
    'permalink' => get_permalink(),
    'excerpt'   => get_the_excerpt(),
    'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
]);

// Inside the template part, receive via $args
$post_id   = $args['post_id']   ?? get_the_ID();
$title     = $args['title']     ?? get_the_title();
```

### `index.php` — Main loop template

```php
<?php
defined('ABSPATH') || exit;

get_header();
?>
<main id="main-content" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="grid grid--3">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/components/card', null, [
                        'post_id'   => get_the_ID(),
                        'title'     => get_the_title(),
                        'permalink' => get_permalink(),
                        'excerpt'   => get_the_excerpt(),
                        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
                    ]); ?>
                <?php endwhile; ?>
            </div>
            <?php get_template_part('template-parts/components/pagination'); ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'golden-globe'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
```

### `template-parts/components/card.php`

```php
<?php
defined('ABSPATH') || exit;

$post_id    = $args['post_id']    ?? get_the_ID();
$title      = $args['title']      ?? get_the_title();
$permalink  = $args['permalink']  ?? get_permalink();
$excerpt    = $args['excerpt']    ?? get_the_excerpt();
$thumbnail  = $args['thumbnail']  ?? get_the_post_thumbnail_url($post_id, 'card-thumb');
$categories = $args['categories'] ?? [];
?>
<article class="card" id="post-<?php echo esc_attr($post_id); ?>">
    <?php if ($thumbnail) : ?>
        <a href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
            <img class="card__image"
                 src="<?php echo esc_url($thumbnail); ?>"
                 alt="<?php echo esc_attr($title); ?>"
                 loading="lazy">
        </a>
    <?php endif; ?>

    <div class="card__body">
        <?php if (!empty($categories)) : ?>
            <div class="card__cats">
                <?php foreach ($categories as $cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="card__cat-tag">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3 class="card__title">
            <a href="<?php echo esc_url($permalink); ?>" class="card__link">
                <?php echo esc_html($title); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="card__excerpt"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
        <?php endif; ?>

        <a href="<?php echo esc_url($permalink); ?>"
           class="btn btn--outline btn--sm"
           aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'golden-globe'), $title)); ?>">
            <?php esc_html_e('Read More', 'golden-globe'); ?>
        </a>
    </div>
</article>
```

### Template parts directory structure

```
template-parts/
├── global/
│   ├── site-header.php   ← loaded by header.php
│   ├── site-footer.php   ← loaded by footer.php
│   ├── skip-link.php
│   └── breadcrumbs.php
├── components/
│   ├── card.php          ← reusable post card
│   ├── hero.php
│   ├── cta.php
│   ├── testimonial.php
│   └── pagination.php
└── blocks/
    ├── block-hero.php         ← ACF Gutenberg block render
    ├── block-cards.php
    ├── block-cta.php
    └── block-testimonials.php
```

---

## 9. Custom Gutenberg Blocks (ACF)

### `inc/acf-blocks.php`

```php
<?php
defined('ABSPATH') || exit;

add_action('acf/init', function (): void {
    if (!function_exists('acf_register_block_type')) return;

    $blocks = [
        [
            'name'             => 'hero-banner',
            'title'            => __('Hero Banner', 'golden-globe'),
            'description'      => __('Full-width hero section with heading and CTA.', 'golden-globe'),
            'render_template'  => 'template-parts/blocks/block-hero.php',
            'category'         => 'agency-blocks',
            'icon'             => 'cover-image',
            'keywords'         => ['hero', 'banner', 'header'],
            'supports'         => ['align' => ['full', 'wide'], 'anchor' => true],
            'example'          => ['attributes' => ['mode' => 'preview', 'data' => ['hero_heading' => 'Your Heading']]],
        ],
        [
            'name'             => 'cards-grid',
            'title'            => __('Cards Grid', 'golden-globe'),
            'description'      => __('A responsive grid of content cards.', 'golden-globe'),
            'render_template'  => 'template-parts/blocks/block-cards.php',
            'category'         => 'agency-blocks',
            'icon'             => 'grid-view',
            'keywords'         => ['cards', 'grid', 'posts'],
            'supports'         => ['align' => true],
        ],
        [
            'name'             => 'cta-banner',
            'title'            => __('CTA Banner', 'golden-globe'),
            'description'      => __('Call to action with background color control.', 'golden-globe'),
            'render_template'  => 'template-parts/blocks/block-cta.php',
            'category'         => 'agency-blocks',
            'icon'             => 'megaphone',
            'keywords'         => ['cta', 'call to action', 'button'],
        ],
        [
            'name'             => 'testimonials',
            'title'            => __('Testimonials', 'golden-globe'),
            'description'      => __('Testimonial slider or grid.', 'golden-globe'),
            'render_template'  => 'template-parts/blocks/block-testimonials.php',
            'category'         => 'agency-blocks',
            'icon'             => 'format-quote',
            'keywords'         => ['testimonials', 'reviews', 'quotes'],
        ],
    ];

    foreach ($blocks as $block) {
        acf_register_block_type($block);
    }
});

// Register custom block category
add_filter('block_categories_all', function (array $categories): array {
    return array_merge([
        [
            'slug'  => 'agency-blocks',
            'title' => __('Agency Blocks', 'golden-globe'),
            'icon'  => 'layout',
        ],
    ], $categories);
});
```

### `template-parts/blocks/block-hero.php`

```php
<?php
defined('ABSPATH') || exit;

$heading  = get_field('hero_heading')  ?: __('Your Heading Here', 'golden-globe');
$sub      = get_field('hero_subheading');
$image    = get_field('hero_image');
$btn      = get_field('hero_cta_button');
$overlay  = get_field('hero_overlay_opacity') ?: 0.4;
$min_h    = get_field('hero_min_height') ?: '600px';

$anchor   = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '"' : '';
$align    = !empty($block['align']) ? ' align' . $block['align'] : '';
$classes  = 'block-hero' . $align . ' ' . ($block['className'] ?? '');
?>

<section <?php echo $anchor; ?>
         class="<?php echo esc_attr(trim($classes)); ?>"
         style="min-height: <?php echo esc_attr($min_h); ?>">

    <?php if ($image) : ?>
    <div class="block-hero__bg"
         style="background-image: url('<?php echo esc_url($image['url']); ?>');"
         role="img"
         aria-label="<?php echo esc_attr($image['alt']); ?>">
    </div>
    <div class="block-hero__overlay" style="opacity:<?php echo esc_attr($overlay); ?>"></div>
    <?php endif; ?>

    <div class="block-hero__content container">
        <h1 class="block-hero__heading"><?php echo esc_html($heading); ?></h1>

        <?php if ($sub) : ?>
        <p class="block-hero__sub"><?php echo esc_html($sub); ?></p>
        <?php endif; ?>

        <?php if ($btn) : ?>
        <a href="<?php echo esc_url($btn['url']); ?>"
           class="btn btn--primary btn--lg"
           <?php echo $btn['target'] === '_blank' ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
            <?php echo esc_html($btn['title']); ?>
        </a>
        <?php endif; ?>
    </div>

</section>
```

---

## 10. REST API & Headless Support

### `inc/rest-api.php`

```php
<?php
defined('ABSPATH') || exit;

add_action('rest_api_init', function (): void {

    $namespace = 'mytheme/v1';

    // ─── Expose ACF fields on all CPTs ───
    $cpts = ['portfolio', 'service', 'testimonial', 'team_member'];
    foreach ($cpts as $cpt) {
        register_rest_field($cpt, 'acf', [
            'get_callback' => fn($post) => get_fields($post['id']) ?: [],
            'schema'       => null,
        ]);

        // Also expose featured image URL
        register_rest_field($cpt, 'featured_image_url', [
            'get_callback' => fn($post) => get_the_post_thumbnail_url($post['id'], 'large') ?: null,
            'schema'       => null,
        ]);
    }

    // ─── Custom Endpoint: Portfolio ───
    register_rest_route($namespace, '/portfolio', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'agency_rest_portfolio',
        'permission_callback' => '__return_true',
        'args'                => [
            'per_page' => ['type' => 'integer', 'default' => 9,   'minimum' => 1, 'maximum' => 50],
            'page'     => ['type' => 'integer', 'default' => 1,   'minimum' => 1],
            'category' => ['type' => 'string',  'default' => ''],
        ],
    ]);

    // ─── Custom Endpoint: Global Options ───
    register_rest_route($namespace, '/options', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'agency_rest_options',
        'permission_callback' => '__return_true',
    ]);

    // ─── Custom Endpoint: Site Metadata ───
    register_rest_route($namespace, '/site-info', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'agency_rest_site_info',
        'permission_callback' => '__return_true',
    ]);
});

function agency_rest_portfolio(WP_REST_Request $request): WP_REST_Response {
    $per_page = $request->get_param('per_page');
    $page     = $request->get_param('page');
    $category = $request->get_param('category');

    $args = [
        'post_type'      => 'portfolio',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if (!empty($category)) {
        $args['tax_query'] = [[ // phpcs:ignore WordPress.DB.SlowDBQuery
            'taxonomy' => 'portfolio_cat',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($category),
        ]];
    }

    $query = new WP_Query($args);
    $data  = [];

    foreach ($query->posts as $post) {
        $data[] = [
            'id'          => $post->ID,
            'title'       => get_the_title($post),
            'slug'        => $post->post_name,
            'link'        => get_permalink($post),
            'excerpt'     => get_the_excerpt($post),
            'date'        => $post->post_date,
            'thumbnail'   => get_the_post_thumbnail_url($post->ID, 'card-thumb'),
            'acf'         => get_fields($post->ID) ?: [],
            'categories'  => get_the_terms($post->ID, 'portfolio_cat') ?: [],
        ];
    }

    $response = rest_ensure_response($data);
    $response->header('X-WP-Total',     $query->found_posts);
    $response->header('X-WP-TotalPages', $query->max_num_pages);

    return $response;
}

function agency_rest_options(): WP_REST_Response {
    if (!function_exists('get_fields')) {
        return rest_ensure_response([]);
    }
    return rest_ensure_response(get_fields('option') ?: []);
}

function agency_rest_site_info(): WP_REST_Response {
    return rest_ensure_response([
        'name'        => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url'         => get_bloginfo('url'),
        'logo'        => wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'),
        'menus'       => [
            'primary' => agency_rest_get_menu('primary'),
            'footer'  => agency_rest_get_menu('footer'),
        ],
    ]);
}

function agency_rest_get_menu(string $location): array {
    $locations = get_nav_menu_locations();
    if (!isset($locations[$location])) return [];

    $menu  = wp_get_nav_menu_object($locations[$location]);
    $items = wp_get_nav_menu_items($menu->term_id);

    return array_map(fn($item) => [
        'id'     => $item->ID,
        'title'  => $item->title,
        'url'    => $item->url,
        'parent' => $item->menu_item_parent,
        'target' => $item->target,
    ], $items ?: []);
}
```

---

## 11. Theme Options Panel (ACF)

### `inc/theme-options.php`

```php
<?php
defined('ABSPATH') || exit;

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_options_page')) return;

    // Parent options page
    acf_add_options_page([
        'page_title'  => __('Theme Settings', 'golden-globe'),
        'menu_title'  => __('Theme Settings', 'golden-globe'),
        'menu_slug'   => 'golden-globe-settings',
        'capability'  => 'manage_options',
        'icon_url'    => 'dashicons-admin-appearance',
        'redirect'    => true,
    ]);

    // Sub pages
    $subpages = [
        ['page_title' => 'Header & Nav', 'menu_title' => 'Header',  'menu_slug' => 'agency-header-settings'],
        ['page_title' => 'Footer',        'menu_title' => 'Footer',  'menu_slug' => 'agency-footer-settings'],
        ['page_title' => 'Social Media',  'menu_title' => 'Social',  'menu_slug' => 'agency-social-settings'],
        ['page_title' => 'SEO & Scripts', 'menu_title' => 'SEO',     'menu_slug' => 'agency-seo-settings'],
        ['page_title' => 'Contact Info',  'menu_title' => 'Contact', 'menu_slug' => 'agency-contact-settings'],
    ];

    foreach ($subpages as $sub) {
        acf_add_options_sub_page(array_merge($sub, ['parent_slug' => 'golden-globe-settings', 'capability' => 'manage_options']));
    }
});

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) return;

    // ─── GLOBAL CONTACT INFO ───
    acf_add_local_field_group([
        'key'    => 'group_global_contact',
        'title'  => 'Contact Information',
        'fields' => [
            ['key' => 'field_global_phone',    'label' => 'Phone',   'name' => 'global_phone',   'type' => 'text'],
            ['key' => 'field_global_email',    'label' => 'Email',   'name' => 'global_email',   'type' => 'email'],
            ['key' => 'field_global_address',  'label' => 'Address', 'name' => 'global_address', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_global_maps_url', 'label' => 'Maps URL','name' => 'global_maps_url','type' => 'url'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'agency-contact-settings']]],
    ]);

    // ─── GLOBAL SOCIAL LINKS ───
    acf_add_local_field_group([
        'key'    => 'group_global_social',
        'title'  => 'Social Media Links',
        'fields' => [
            ['key' => 'field_social_fb',   'label' => 'Facebook',  'name' => 'social_facebook',  'type' => 'url'],
            ['key' => 'field_social_ig',   'label' => 'Instagram', 'name' => 'social_instagram', 'type' => 'url'],
            ['key' => 'field_social_li',   'label' => 'LinkedIn',  'name' => 'social_linkedin',  'type' => 'url'],
            ['key' => 'field_social_tw',   'label' => 'Twitter/X', 'name' => 'social_twitter',   'type' => 'url'],
            ['key' => 'field_social_yt',   'label' => 'YouTube',   'name' => 'social_youtube',   'type' => 'url'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'agency-social-settings']]],
    ]);

    // ─── HEADER SETTINGS ───
    acf_add_local_field_group([
        'key'    => 'group_header',
        'title'  => 'Header Settings',
        'fields' => [
            ['key' => 'field_header_cta_label', 'label' => 'Header CTA Label', 'name' => 'header_cta_label', 'type' => 'text'],
            ['key' => 'field_header_cta_url',   'label' => 'Header CTA URL',   'name' => 'header_cta_url',   'type' => 'url'],
            ['key' => 'field_header_sticky',    'label' => 'Sticky Header',    'name' => 'header_sticky',    'type' => 'true_false', 'default_value' => 1],
            ['key' => 'field_header_transparent','label' => 'Transparent on Home','name' => 'header_transparent','type' => 'true_false'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'agency-header-settings']]],
    ]);
});

// ─── Helper: Get global option safely ─────────────────────
function agency_option(string $key, $default = ''): mixed {
    if (!function_exists('get_field')) return $default;
    $value = get_field($key, 'option');
    return !empty($value) ? $value : $default;
}

// Usage anywhere:
// $phone    = agency_option('global_phone');
// $linkedin = agency_option('social_linkedin');
```

---

## 12. Accessibility (WCAG 2.1 AA)

### `inc/accessibility.php`

```php
<?php
defined('ABSPATH') || exit;

// Add skip link to the top of page
function agency_skip_link(): void {
    echo '<a class="skip-link screen-reader-text" href="#main-content">'
        . esc_html__('Skip to main content', 'golden-globe')
        . '</a>';
}
add_action('wp_body_open', 'agency_skip_link');

// Ensure search form has accessible label
add_filter('get_search_form', function (string $form): string {
    return str_replace(
        '<input type="search"',
        '<label for="s" class="sr-only">' . esc_html__('Search', 'golden-globe') . '</label><input type="search"',
        $form
    );
});
```

### `template-parts/global/site-header.php`

```php
<?php
defined('ABSPATH') || exit;

$is_sticky       = agency_option('header_sticky');
$is_transparent  = agency_option('header_transparent') && is_front_page();
$header_classes  = 'site-header';
if ($is_sticky)      $header_classes .= ' site-header--sticky';
if ($is_transparent) $header_classes .= ' site-header--transparent';
?>

<header class="<?php echo esc_attr($header_classes); ?>"
        role="banner"
        id="site-header">

    <div class="container site-header__inner">

        <!-- LOGO -->
        <a href="<?php echo esc_url(home_url('/')); ?>"
           class="site-header__logo"
           aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> - <?php esc_attr_e('Go to homepage', 'golden-globe'); ?>">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                echo '<span>' . esc_html(get_bloginfo('name')) . '</span>';
            }
            ?>
        </a>

        <!-- PRIMARY NAV -->
        <nav class="site-header__nav"
             role="navigation"
             aria-label="<?php esc_attr_e('Primary Navigation', 'golden-globe'); ?>"
             id="primary-navigation">

            <?php wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'nav-menu',
                'fallback_cb'    => false,
                'depth'          => 3,
                'walker'         => class_exists('Agency_Walker_Nav') ? new Agency_Walker_Nav() : null,
            ]); ?>

        </nav>

        <!-- HEADER CTA -->
        <?php
        $cta_label = agency_option('header_cta_label');
        $cta_url   = agency_option('header_cta_url');
        if ($cta_label && $cta_url) : ?>
            <a href="<?php echo esc_url($cta_url); ?>"
               class="btn btn--primary site-header__cta">
                <?php echo esc_html($cta_label); ?>
            </a>
        <?php endif; ?>

        <!-- MOBILE TOGGLE -->
        <button class="site-header__toggle"
                aria-expanded="false"
                aria-controls="primary-navigation"
                aria-label="<?php esc_attr_e('Toggle mobile menu', 'golden-globe'); ?>">
            <span class="sr-only"><?php esc_html_e('Menu', 'golden-globe'); ?></span>
            <span class="hamburger" aria-hidden="true"></span>
        </button>

    </div>

</header>
```

### Accessibility CSS

```css
/* ─── SKIP LINK ─── */
.skip-link {
  position: absolute;
  top: -100%;
  left: var(--spacing-4);
  z-index: var(--z-toast);
  padding: var(--spacing-2) var(--spacing-4);
  background: var(--color-primary);
  color: var(--color-white);
  font-weight: var(--font-weight-semibold);
  border-radius: var(--radius-md);
  text-decoration: none;
  transition: top var(--transition-fast);
}
.skip-link:focus {
  top: var(--spacing-4);
}

/* ─── SCREEN READER ONLY ─── */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* ─── FOCUS STYLES ─── */
*:focus-visible {
  outline: 3px solid var(--color-primary);
  outline-offset: 3px;
  border-radius: var(--radius-sm);
}

/* Remove focus outline for mouse users only */
*:focus:not(:focus-visible) {
  outline: none;
}
```

---

## 13. i18n / l10n — Translations

### `inc/i18n.php`

```php
<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('golden-globe', AGENCY_THEME_DIR . '/languages');
});

// Example of all translation function types:
// __()         → Returns translated string
// _e()         → Echoes translated string
// _n()         → Singular/plural
// _x()         → With context
// esc_html__() → Translated + escaped
// esc_attr__() → Translated + attribute escaped

// Example:
// printf(
//     _n('%s project', '%s projects', $count, 'golden-globe'),
//     number_format_i18n($count)
// );
```

### Generate .pot file (CLI)

```bash
# Using WP-CLI
wp i18n make-pot . languages/golden-globe.pot --domain=golden-globe

# Using xgettext directly
find . -name "*.php" | xargs xgettext \
    --language=PHP \
    --keyword=__ \
    --keyword=_e \
    --keyword=_n:1,2 \
    --keyword=_x:1,2c \
    --keyword=esc_html__ \
    --keyword=esc_attr__ \
    --from-code=UTF-8 \
    --output=languages/golden-globe.pot
```

---

## 14. Security & DB Query Optimization

### `inc/security.php`

```php
<?php
defined('ABSPATH') || exit;

// ─── Remove version numbers ───
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// ─── Disable xmlrpc.php ───
add_filter('xmlrpc_enabled', '__return_false');

// ─── Remove X-Pingback header ───
add_filter('wp_headers', function (array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});

// ─── Hide login errors ───
add_filter('login_errors', fn() => __('Incorrect username or password.', 'golden-globe'));

// ─── Disable file editing in admin ───
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// ─── Content Security Headers ───
add_action('send_headers', function (): void {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }
});

// ─── Sanitize all inputs (reference) ───
// Always use these before processing user data:
// $text    = sanitize_text_field(wp_unslash($_POST['field']));
// $email   = sanitize_email($_POST['email']);
// $url     = esc_url_raw($_POST['url']);
// $int     = absint($_POST['number']);
// $html    = wp_kses_post($_POST['content']);
// $slug    = sanitize_title($_POST['slug']);
// $key     = sanitize_key($_POST['key']);

// ─── Always verify nonces ───
// wp_verify_nonce($_POST['nonce'], 'my_action');
// check_ajax_referer('my_action', 'nonce');
// check_admin_referer('my_action_' . $post_id);
```

### `inc/performance.php`

```php
<?php
defined('ABSPATH') || exit;

// ─── Remove unnecessary wp_head bloat ───
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'rest_output_link_wp_head', 10);

// ─── Disable emojis (save ~15KB) ───
remove_action('wp_head',             'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles',     'print_emoji_styles');
remove_action('admin_print_styles',  'print_emoji_styles');
remove_filter('the_content_feed',    'wp_staticize_emoji');
remove_filter('comment_text_rss',    'wp_staticize_emoji');
remove_filter('wp_mail',             'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins',       fn($plugins) => array_diff($plugins, ['wpemoji']));

// ─── Add WebP & AVIF support ───
add_filter('mime_types', function (array $mimes): array {
    $mimes['webp'] = 'image/webp';
    $mimes['avif'] = 'image/avif';
    return $mimes;
});

// ─── Lazy load iframes ───
add_filter('the_content', function (string $content): string {
    return str_replace('<iframe ', '<iframe loading="lazy" ', $content);
});

// ─── Optimized DB queries (avoid N+1) ───
// BAD: Querying meta inside a loop
// while (have_posts()) { get_post_meta(get_the_ID(), 'key'); }

// GOOD: Pre-fetch all meta at once
function agency_prefetch_post_meta(array $post_ids, string $key): array {
    global $wpdb;

    if (empty($post_ids)) return [];

    $placeholders = implode(',', array_fill(0, count($post_ids), '%d'));
    $sql          = $wpdb->prepare(
        "SELECT post_id, meta_value FROM {$wpdb->postmeta}
         WHERE post_id IN ({$placeholders}) AND meta_key = %s",
        array_merge($post_ids, [$key])
    );

    $results = $wpdb->get_results($sql);
    $map     = [];

    foreach ($results as $row) {
        $map[$row->post_id] = maybe_unserialize($row->meta_value);
    }

    return $map;
}

// ─── Transient caching for expensive queries ───
function agency_get_cached_posts(string $cache_key, array $query_args, int $expiry = HOUR_IN_SECONDS): array {
    $cached = get_transient($cache_key);

    if ($cached !== false) {
        return $cached;
    }

    $query  = new WP_Query($query_args);
    $posts  = $query->posts;

    set_transient($cache_key, $posts, $expiry);

    return $posts;
}

// Clear transients on post save
add_action('save_post', function (int $post_id): void {
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_agency_%'
            OR option_name LIKE '_transient_timeout_agency_%'"
    );
});
```

---

## 15. Plain CSS & Vanilla JS (No Build Step)

No npm, no Vite, no Sass compiler. All styles live in `assets/css/main.css` and all JavaScript in `assets/js/main.js`. WordPress enqueues them directly.

### `inc/enqueue.php` — Asset loading

```php
<?php
defined('ABSPATH') || exit;

function agency_enqueue_scripts(): void {
    $ver = AGENCY_THEME_VERSION;
    $uri = AGENCY_THEME_URI;

    // Main stylesheet — plain CSS, no build step
    if (file_exists(AGENCY_THEME_DIR . '/assets/css/main.css')) {
        wp_enqueue_style('agency-main', $uri . '/assets/css/main.css', [], $ver);
    } else {
        wp_enqueue_style('agency-main', $uri . '/style.css', [], $ver);
    }

    // Main JS — flat vanilla JS, deferred
    if (file_exists(AGENCY_THEME_DIR . '/assets/js/main.js')) {
        wp_enqueue_script('agency-main', $uri . '/assets/js/main.js', [], $ver, [
            'strategy'  => 'defer',
            'in_footer' => true,
        ]);
    }

    // Pass PHP data to JS
    wp_localize_script('agency-main', 'AgencyTheme', [
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'restUrl'   => esc_url_raw(rest_url('golden-globe/v1/')),
        'nonce'     => wp_create_nonce('agency_nonce'),
        'restNonce' => wp_create_nonce('wp_rest'),
        'isLoggedIn'=> is_user_logged_in(),
        'i18n'      => [
            'loading' => __('Loading...', 'golden-globe'),
            'noMore'  => __('No more posts', 'golden-globe'),
            'error'   => __('Something went wrong', 'golden-globe'),
        ],
    ]);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'agency_enqueue_scripts');

function agency_admin_scripts(): void {
    if (file_exists(AGENCY_THEME_DIR . '/assets/css/admin.css')) {
        wp_enqueue_style('agency-admin', AGENCY_THEME_URI . '/assets/css/admin.css', [], AGENCY_THEME_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'agency_admin_scripts');
```

### `assets/js/main.js` — Flat vanilla JS structure

```javascript
/* =============================================================
   GOLDEN GLOBE — MAIN JS
   Single flat file — no build step required
   ============================================================= */

/* ── Navigation ── */
(function () {
  var header = document.querySelector(".site-header");
  var toggle = document.querySelector("[data-nav-toggle]");
  var nav = document.querySelector("[data-nav-menu]");
  var overlay = document.querySelector("[data-nav-overlay]");

  if (!header) return;

  function toggleMenu() {
    var isOpen = toggle.getAttribute("aria-expanded") === "true";
    toggle.setAttribute("aria-expanded", String(!isOpen));
    if (nav) nav.classList.toggle("is-open");
    if (overlay) overlay.classList.toggle("is-visible");
    document.body.classList.toggle("nav-open");
  }

  if (toggle) toggle.addEventListener("click", toggleMenu);
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") toggleMenu();
  });

  window.addEventListener(
    "scroll",
    function () {
      header.classList.toggle("is-scrolled", window.scrollY > 50);
    },
    { passive: true },
  );
})();

/* ── Accordion ── */
(function () {
  document.querySelectorAll("[data-accordion]").forEach(function (item) {
    var trigger = item.querySelector("[data-accordion-trigger]");
    var panel = item.querySelector("[data-accordion-panel]");
    if (!trigger || !panel) return;

    trigger.addEventListener("click", function () {
      var isOpen = trigger.getAttribute("aria-expanded") === "true";
      trigger.setAttribute("aria-expanded", String(!isOpen));
      panel.hidden = isOpen;
    });
  });
})();
```

### Asset workflow summary

| Tool                  | Required? | Replacement                 |
| --------------------- | --------- | --------------------------- |
| Node / npm            | No        | —                           |
| Vite                  | No        | —                           |
| Sass / SCSS           | No        | Plain CSS custom properties |
| Composer (Timber)     | No        | `get_template_part()`       |
| `assets/css/main.css` | **Yes**   | Edit directly               |
| `assets/js/main.js`   | **Yes**   | Edit directly               |

---

## 16. Automated Testing & CI/CD

### `phpunit.xml`

```xml
<?xml version="1.0"?>
<phpunit
    bootstrap="tests/phpunit/bootstrap.php"
    colors="true"
    verbose="true"
    stopOnError="false"
    stopOnFailure="false">
    <testsuites>
        <testsuite name="golden-globe">
            <directory>tests/phpunit</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory>inc</directory>
        </include>
    </coverage>
</phpunit>
```

### `tests/phpunit/test-helpers.php`

```php
<?php

class Test_Agency_Helpers extends WP_UnitTestCase {

    public function test_agency_excerpt_trims_text(): void {
        $text   = str_repeat('word ', 30);
        $result = agency_excerpt($text, 10);

        $this->assertStringContainsString('&hellip;', $result);
        $this->assertLessThanOrEqual(100, strlen($result));
    }

    public function test_agency_hex_to_rgba(): void {
        $result = agency_hex_to_rgba('#2563eb', 0.5);
        $this->assertStringStartsWith('rgba(', $result);
        $this->assertStringContainsString('0.5', $result);
    }

    public function test_agency_option_returns_default(): void {
        $result = agency_option('nonexistent_key', 'fallback');
        $this->assertEquals('fallback', $result);
    }

    public function test_cpt_portfolio_is_registered(): void {
        $this->assertTrue(post_type_exists('portfolio'));
    }

    public function test_taxonomy_portfolio_cat_is_registered(): void {
        $this->assertTrue(taxonomy_exists('portfolio_cat'));
    }
}
```

### `tests/e2e/homepage.spec.js` — Playwright E2E

```javascript
import { test, expect } from "@playwright/test";

const BASE_URL = process.env.WP_URL || "http://localhost:10000";

test.describe("Homepage", () => {
  test("loads and has correct title", async ({ page }) => {
    await page.goto(BASE_URL);
    await expect(page).toHaveTitle(/.+/); // Has some title
  });

  test("skip link is present and functional", async ({ page }) => {
    await page.goto(BASE_URL);
    const skipLink = page.locator(".skip-link");
    await expect(skipLink).toBeAttached();

    await skipLink.focus();
    await expect(skipLink).toBeVisible();
  });

  test("navigation is accessible", async ({ page }) => {
    await page.goto(BASE_URL);
    const nav = page.locator('[role="navigation"]');
    await expect(nav).toHaveAttribute("aria-label", /.+/);
  });

  test("images have alt attributes", async ({ page }) => {
    await page.goto(BASE_URL);
    const images = page.locator("img:not([alt])");
    await expect(images).toHaveCount(0);
  });

  test("no console errors", async ({ page }) => {
    const errors = [];
    page.on("console", (msg) => {
      if (msg.type() === "error") errors.push(msg.text());
    });
    await page.goto(BASE_URL);
    expect(errors).toHaveLength(0);
  });
});

test.describe("Portfolio Filter", () => {
  test("AJAX filter returns results", async ({ page }) => {
    await page.goto(`${BASE_URL}/portfolio`);
    const filterBtn = page.locator("[data-filter]").first();

    if (await filterBtn.isVisible()) {
      await filterBtn.click();
      await page.waitForResponse((resp) =>
        resp.url().includes("admin-ajax.php"),
      );
      const grid = page.locator("[data-grid]");
      await expect(grid).toBeVisible();
    }
  });
});
```

### `.github/workflows/deploy.yml` — CI/CD Pipeline

No build step needed. The workflow just deploys source files via rsync.

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    name: Deploy to Production
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Deploy via rsync
        uses: burnett01/rsync-deployments@6.0.0
        with:
          switches: -avzr --delete --exclude='.git' --exclude='.github' --exclude='tests'
          path: ./
          remote_path: ${{ secrets.DEPLOY_PATH }}/wp-content/themes/golden-globe/
          remote_host: ${{ secrets.DEPLOY_HOST }}
          remote_user: ${{ secrets.DEPLOY_USER }}
          remote_key: ${{ secrets.DEPLOY_SSH_KEY }}

      - name: Clear WP cache
        uses: appleboy/ssh-action@v1.0.0
        with:
          host: ${{ secrets.DEPLOY_HOST }}
          username: ${{ secrets.DEPLOY_USER }}
          key: ${{ secrets.DEPLOY_SSH_KEY }}
          script: |
            cd ${{ secrets.DEPLOY_PATH }}
            wp cache flush
            wp rewrite flush
```

### `.gitignore`

```gitignore
# Dependencies (not used — kept as safety net)
node_modules/
vendor/

# Logs & OS files
*.log
.DS_Store
Thumbs.db
```

---

## ✅ Final Checklist — 10/10 Launch Criteria

```
ARCHITECTURE
  [ ] Modular folder structure (inc/, template-parts/, page-templates/)
  [ ] Clean bootstrapper functions.php
  [ ] Component class with prop typing

DESIGN
  [ ] Design tokens as CSS custom properties in assets/css/main.css (:root)
  [ ] Mobile-first responsive grid

STATIC SUPPORT
  [ ] Landing page template with ACF
  [ ] Flexible content layouts
  [ ] All ACF fields code-registered

DYNAMIC SUPPORT
  [ ] Custom Post Types (portfolio, services, testimonials, team)
  [ ] Custom taxonomies with REST support
  [ ] AJAX filter with nonce security

GUTENBERG
  [ ] Custom ACF blocks registered in PHP
  [ ] Block category registered
  [ ] Block preview support

REST API
  [ ] ACF fields exposed on all CPTs
  [ ] Custom endpoints (portfolio, options, site-info)
  [ ] Menu data endpoint for headless

THEME OPTIONS
  [ ] ACF Options page (contact, social, header, footer, SEO)
  [ ] agency_option() helper function

ACCESSIBILITY
  [ ] Skip link at top of page
  [ ] All nav menus have aria-label
  [ ] Images have alt text
  [ ] Focus-visible styles
  [ ] ARIA attributes on interactive elements

TRANSLATIONS
  [ ] All strings wrapped in __() or _e()
  [ ] Text domain set in style.css and functions.php
  [ ] .pot file generated

SECURITY
  [ ] All inputs sanitized
  [ ] All outputs escaped
  [ ] Nonces on all forms/AJAX
  [ ] Security headers added

PERFORMANCE
  [ ] WordPress bloat removed
  [ ] Emojis disabled
  [ ] Transient caching on heavy queries
  [ ] WebP support enabled
  [ ] Scripts deferred

BUILD
  [ ] assets/css/main.css in place (plain CSS, CSS custom properties)
  [ ] assets/js/main.js in place (flat vanilla JS)
  [ ] Both files enqueued in inc/enqueue.php

TESTING
  [ ] PHPUnit tests for helper functions
  [ ] Playwright E2E tests
  [ ] Theme Check plugin passes

CI/CD
  [ ] GitHub Actions: lint → test → build → deploy
  [ ] Secrets configured in GitHub repo
  [ ] SSH deploy key set up
```

---

_Agency Theme Guide — All 16 Pillars, Production-Ready_
_Version 1.0 | Generated for agency-grade WordPress development_

Using AI to Develop a WordPress Theme — What You Must Know
This changes everything. AI development has a completely different set of rules, risks, and advantages. Here's the honest, complete picture.

🧠 First — Understand What AI Actually Is in This Context

AI is a very fast, very knowledgeable junior developer who never gets tired — but has no memory, no judgment, and no accountability.

It will write code confidently even when it's wrong. It will never tell you "I'm not sure about this." It will produce 200 lines of perfect-looking code with one subtle bug that breaks everything.

⚠️ The 7 Biggest Risks of AI-Built WordPress Themes

1. 🔒 Security Holes — The #1 Risk
   AI frequently forgets or skips security best practices:
   php// AI will often generate this ❌
   function get_posts_ajax() {
   $category = $_POST['category']; // No sanitization!
    $query = new WP_Query(['tax_query' => [['terms' => $category]]]);
    echo json_encode($query->posts); // No nonce check!
   die();
   }

// What it SHOULD generate ✅
function get_posts_ajax() {
check_ajax_referer('agency_nonce', 'nonce'); // Always first
$category = sanitize_text_field(wp_unslash($\_POST['category'] ?? ''));
$query = new WP_Query([...]);
wp_send_json_success([...]);
}
Rule: Always ask AI to explicitly add nonce verification and input sanitization. Never assume it did.

2. 🗄️ Outdated Code — AI Knows Old WordPress
   AI training data includes old WordPress patterns that are now deprecated:
   php// AI may generate OLD patterns ❌
   add_action('wp_head', 'my_scripts');
   function my_scripts() {
   echo '<script src="...">'; // Never echo scripts directly
   }

// Modern correct approach ✅
add_action('wp_enqueue_scripts', function() {
wp_enqueue_script('my-script', get_template_directory_uri() . '/js/main.js', [], '1.0', ['strategy' => 'defer', 'in_footer' => true]);
});
php// AI may use deprecated query style ❌
query_posts('post_type=portfolio'); // NEVER use this

// Always use ✅
$query = new WP_Query(['post_type' => 'portfolio']);

3. 🔁 Consistency Breaks Across Sessions
   AI has no memory between conversations. If you build your header in one session and your footer in another:

Variable names may differ
CSS class naming conventions may change
PHP function naming may be inconsistent
The same problem may be solved two different ways

This is the #1 practical problem with AI-built themes.

4. 📦 Dependency Confusion
   AI may suggest installing packages that conflict, are abandoned, or are overkill:
   bash# AI might suggest installing ALL of these for one task
   npm install jquery slick-slider aos wow.js gsap scrollreveal

# When vanilla JS + CSS transitions would work fine

5. ♿ Accessibility Always Gets Dropped
   AI will build functional code but accessibility is almost always the first thing missing:

No aria-label on navigation
No alt text logic on dynamic images
No skip link
No focus-visible styles
Interactive elements not keyboard navigable

6. 📱 Mobile is an Afterthought
   AI writes desktop-first CSS by default unless you specifically instruct it otherwise every single time.

7. 🧪 AI Never Tests Its Own Code
   AI cannot run a browser. It cannot check if the code it wrote actually works. It does not know about:

Your specific WordPress version
Your installed plugins
Your server environment
PHP version conflicts

✅ The Rules for Using AI Effectively
Rule 1 — Create a Master Context Document
Before every AI session, paste this at the top:
PROJECT CONTEXT — Always follow these rules:

THEME NAME: golden-globe
TEXT DOMAIN: golden-globe
PHP VERSION: 8.2
WORDPRESS VERSION: 6.5+
DESIGN SYSTEM: Plain CSS custom properties in assets/css/main.css — no Sass, no build step
NAMING: CSS BEM (block\_\_element--modifier)
JS: Vanilla JS IIFEs in assets/js/main.js — NO ES modules, NO jQuery, NO bundler
FUNCTIONS PREFIX: agency\_ (all functions must use this prefix)
SECURITY: Every AJAX handler must have check_ajax_referer() as first line
SANITIZATION: All $_POST/$\_GET inputs must be sanitized before use
ESCAPING: All outputs must be escaped (esc_html, esc_url, esc_attr, wp_kses_post)
ACCESSIBILITY: All interactive elements need ARIA attributes
IMAGES: Always include width, height, loading="lazy", alt attributes
MOBILE: Always write CSS mobile-first (min-width breakpoints only)
NO JQUERY: Use fetch() not $.ajax, use querySelector not $()
Paste this every single session. AI forgets everything.

Rule 2 — Give AI One Task at a Time
❌ Bad prompt:
"Build me a portfolio page with filtering,
pagination, AJAX, and a lightbox"

✅ Good prompt:
"Build only the WP_Query function for portfolio posts.
Use the PROJECT CONTEXT above. Post type: portfolio.
Taxonomy: portfolio_cat. Posts per page: 9. Include pagination data."

Rule 3 — Always Ask for Security Review
After every piece of code AI generates, ask:
"Review the code you just wrote for:

1. Missing nonce verification
2. Unsanitized inputs
3. Unescaped outputs
4. Any deprecated WordPress functions
   List every issue found."

Rule 4 — Maintain a Style Guide File
Keep a CODESTYLE.md in your theme root. After each AI session, update it:
markdown# Code Style Decisions

## CSS Class Names

- Cards: .card, .card**image, .card**body, .card\_\_title
- Buttons: .btn, .btn--primary, .btn--ghost, .btn--lg
- Sections: .section, .section--dark, .section--sm

## PHP Functions

- agency_render() — render template parts
- agency_option() — get ACF global option
- agency_get_first_term() — get first taxonomy term

## JS (IIFEs in `assets/js/main.js`)

- `agencyFilter` IIFE — handles all AJAX filtering
- `agencyNav` IIFE — handles mobile menu

## Color Variables (always use tokens, never hardcode)

- Primary: `var(--color-primary)`
- Never use: `#2563eb` directly in CSS

Rule 5 — Version Control Every AI Output
bash# Commit after EVERY working AI-generated feature
git add .
git commit -m "feat: AI-generated portfolio AJAX filter"

# If AI breaks something, you can always rollback

git checkout -- inc/ajax-handlers.php

Rule 6 — Always Test in a Real Browser
AI cannot test. You must:
After every AI-generated component:
[ ] Open it in Chrome
[ ] Open it in Firefox
[ ] Resize to mobile (375px)
[ ] Tab through with keyboard only
[ ] Check browser console for errors
[ ] Check PHP error log

Rule 7 — Use AI for the Right Things
AI is EXCELLENT at: AI is BAD at:
─────────────────────────────────── ─────────────────────────────────
Writing boilerplate PHP/CSS/JS Judging if your design looks good
Registering CPTs and taxonomies Knowing your client's requirements
Writing WP_Query arguments Understanding your server setup
Generating ACF field arrays Catching its own logic bugs
Writing repetitive template parts Long-term code consistency
Explaining WordPress hooks Knowing what NOT to build
Security checklist review Testing in a real environment

📋 AI Development Workflow — Step by Step
PHASE 1 — SETUP (Do this manually, not with AI)
[ ] Create folder structure yourself
[ ] Set up Git repository
[ ] Set up local WordPress (LocalWP)
[ ] Create CODESTYLE.md and PROJECT CONTEXT

PHASE 2 — FOR EACH FEATURE (AI-assisted)
[ ] Paste PROJECT CONTEXT at top of prompt
[ ] Give AI ONE specific task
[ ] Review code before copy-pasting
[ ] Ask AI to do a security review
[ ] Test in browser immediately
[ ] Commit to Git

PHASE 3 — AFTER EVERY 3-4 FEATURES
[ ] Read through all new code manually
[ ] Check for naming inconsistencies
[ ] Check for duplicated logic
[ ] Update CODESTYLE.md
[ ] Run Theme Check plugin

PHASE 4 — BEFORE LAUNCH
[ ] Full manual security audit
[ ] Run Theme Check plugin
[ ] Test all AJAX with browser DevTools open
[ ] Test keyboard navigation
[ ] Check all images have alt text
[ ] Validate HTML (validator.w3.org)
[ ] Check WP_DEBUG log is clean

💬 The Best Prompts to Use
For new components:
"Using my PROJECT CONTEXT, create a [component name] template part.
File: template-parts/components/[name].php
Props passed via $args: [list props].
Include: BEM CSS classes, accessibility attributes, escaped outputs.
Do NOT include jQuery."

For PHP functions:
"Write a WordPress function with prefix agency\_ that [task].
Must include: input sanitization, nonce check (if AJAX), escaped output.
Check for deprecated functions before writing."

For CSS:
"Write mobile-first plain CSS for the [component] component.
Use only CSS custom properties from :root in assets/css/main.css.
BEM naming. Start with mobile styles, use min-width breakpoints.
No hardcoded colors or spacing values."

For debugging:
"Here is my WordPress PHP code: [paste code]
It should [expected behavior] but instead [actual behavior].
Identify the bug. Do not rewrite the whole function — just fix the issue."

🎯 Realistic Expectation with AI
ScenarioWithout AIWith AITime to build full theme6–8 weeks2–3 weeksCode quality (if you review)9/108.5/10Code quality (blind trust)—5/10Security (if you audit)9/108/10Security (blind trust)—4/10ConsistencyHighMedium (needs management)

🔑 The One-Line Summary

AI writes the code. You are the architect, reviewer, and quality gate. The moment you stop reviewing, the theme drops from 10/10 to 5/10.

---

## 17. ❌ What NOT To Do In This Workspace

This theme is intentionally zero-dependency. The rules below are **hard constraints** — not preferences.

---

### ❌ No Build Tools — Ever

| Forbidden                            | Why it's forbidden            | Correct approach |
| ------------------------------------ | ----------------------------- | ---------------- |
| `npm install`                        | No Node/npm in this project   | —                |
| `npm run build` / `npm run dev`      | No build pipeline             | —                |
| `vite` / `vite.config.js`            | No bundler                    | —                |
| `webpack` / `rollup` / `parcel`      | No bundler                    | —                |
| `package.json`                       | No Node dependencies          | —                |
| `composer install` / `composer.json` | No Composer autoloader needed | —                |

> If an AI session or tool suggests running `npm install` or `composer install`, **stop and do not run it**.

---

### ❌ No CSS Pre-processors

| Forbidden                    | Why it's forbidden          | Correct approach                    |
| ---------------------------- | --------------------------- | ----------------------------------- |
| `.scss` / `.sass` files      | No Sass compiler            | Edit `assets/css/main.css` directly |
| `@use` / `@import` (Sass)    | SCSS syntax, needs compiler | Use CSS `var(--token)`              |
| `$variable` (Sass variables) | SCSS syntax                 | Use `--custom-property` in `:root`  |
| `&--modifier` nesting (Sass) | SCSS nesting                | Write flat BEM CSS                  |
| `stylelint --config scss`    | For SCSS only               | Not needed                          |

---

### ❌ No JavaScript Modules / Bundling

| Forbidden                              | Why it's forbidden               | Correct approach                        |
| -------------------------------------- | -------------------------------- | --------------------------------------- |
| `import { x } from './module'`         | ES module syntax — needs bundler | Write plain IIFE in `assets/js/main.js` |
| `export default class X {}`            | ES module syntax                 | Wrap logic in `(function(){ ... })()`   |
| `import React from 'react'`            | No React                         | Plain PHP templates                     |
| `const x = require('x')`               | Node.js CommonJS                 | —                                       |
| Creating files in `assets/js/modules/` | Old pattern — deleted            | All JS in one flat `assets/js/main.js`  |
| jQuery (`$()`, `$.ajax()`)             | Not loaded in this theme         | Use `fetch()`, `querySelector()`        |

---

### ❌ No Templating Engines

| Forbidden                 | Why it's forbidden       | Correct approach                        |
| ------------------------- | ------------------------ | --------------------------------------- |
| `Timber\Timber::render()` | Timber not installed     | `get_template_part()`                   |
| `.twig` files             | No Twig templating       | PHP template parts in `template-parts/` |
| `views/` folder           | Deleted — Timber pattern | `template-parts/`                       |
| Blade templates           | No Laravel               | PHP template parts                      |
| Smarty                    | No Smarty                | PHP template parts                      |

---

### ❌ No Hardcoded Values in CSS or Wrong Text Domain

```css
/* ❌ Never */
color: #2563eb;
padding: 16px;
margin: 32px auto;

/* ✅ Always */
color: var(--color-primary);
padding: var(--spacing-4);
margin: var(--spacing-8) auto;
```

```php
// ❌ Never — wrong text domain
__('Hello', 'my-theme');
esc_html_e('Read more', 'golden-globe');

// ✅ Always — this theme's text domain is golden-globe
__('Hello', 'golden-globe');
esc_html_e('Read more', 'golden-globe');
```

---

### ❌ No Skipping Security in AJAX Handlers

```php
// ❌ Never skip the nonce check
function agency_ajax_my_action(): void {
    $data = sanitize_text_field($_POST['data'] ?? '');
    // dangerous: no auth check
}

// ✅ Always — nonce check is the FIRST line
function agency_ajax_my_action(): void {
    check_ajax_referer('agency_nonce', 'nonce');
    $data = sanitize_text_field(wp_unslash($_POST['data'] ?? ''));
    // ...
}
```

---

### ❌ Do Not Create These Files

| File                           | Why not                                 |
| ------------------------------ | --------------------------------------- |
| `package.json`                 | No Node in this project                 |
| `vite.config.js`               | No Vite                                 |
| `webpack.config.js`            | No Webpack                              |
| `composer.json`                | No Composer autoloader needed           |
| `.babelrc` / `babel.config.js` | No transpiling                          |
| `tsconfig.json`                | No TypeScript                           |
| `tailwind.config.js`           | No Tailwind                             |
| `assets/scss/**`               | No SCSS — use `assets/css/main.css`     |
| `assets/js/modules/**`         | Deleted pattern — all JS in `main.js`   |
| `views/**`                     | Deleted pattern — use `template-parts/` |

---

### ✅ The Only Files You Need To Edit

| File                      | Purpose                                    |
| ------------------------- | ------------------------------------------ |
| `assets/css/main.css`     | All styles — edit directly, no compiler    |
| `assets/js/main.js`       | All JavaScript — flat IIFEs, no bundler    |
| `template-parts/**/*.php` | All HTML templates                         |
| `inc/**/*.php`            | All PHP logic (hooks, CPTs, AJAX, etc.)    |
| `page-templates/**/*.php` | Page template files                        |
| `style.css`               | Theme header only — do not add styles here |
