# Golden Globe Theme — Code Style Guide

> Paste this into every AI session to maintain consistency across conversations.

---

## PROJECT CONTEXT

```
THEME NAME:     Golden Globe
TEXT DOMAIN:    golden-globe
PHP PREFIX:     agency_  (functions)  |  AGENCY_  (constants)
THEME VERSION:  1.0.0
PHP VERSION:    8.2+
WORDPRESS:      6.5+
JS:             Vanilla JS — NO jQuery, NO npm, NO build step
CSS:            Plain CSS with CSS custom properties — NO Sass, NO build step
TEMPLATING:     PHP template parts only (template-parts/) — NO Timber/Twig
```

---

## PHP RULES

### Function Naming

All functions must be prefixed `agency_`:

```php
agency_render()          // render template parts
agency_option()          // get ACF global option
agency_excerpt()         // trim text to word count
agency_get_first_term()  // get first taxonomy term
agency_acf_date()        // format ACF date field
agency_hex_to_rgba()     // convert hex color to rgba
agency_icon()            // output inline SVG icon
agency_is_template()     // check current page template
agency_prefetch_post_meta()  // batch fetch post meta
agency_get_cached_posts()    // transient-cached WP_Query

```

### Constants

```php
AGENCY_THEME_VERSION  // '1.0.0'
AGENCY_THEME_DIR      // get_template_directory()
AGENCY_THEME_URI      // get_template_directory_uri()
```

### Security — Mandatory on Every AJAX Handler

```php
// ✅ ALWAYS first line in every AJAX function
check_ajax_referer('agency_nonce', 'nonce');

// ✅ ALWAYS sanitize inputs
$text  = sanitize_text_field(wp_unslash($_POST['field'] ?? ''));
$email = sanitize_email($_POST['email'] ?? '');
$url   = esc_url_raw($_POST['url'] ?? '');
$int   = absint($_POST['number'] ?? 0);
$html  = wp_kses_post($_POST['content'] ?? '');
$slug  = sanitize_title($_POST['slug'] ?? '');

// ✅ ALWAYS escape outputs
echo esc_html($text);
echo esc_url($url);
echo esc_attr($attribute);
echo wp_kses_post($html_content);
```

### Hooks and Actions

```php
// Theme setup hooks used
add_action('after_setup_theme', 'agency_theme_setup');
add_action('wp_enqueue_scripts', 'agency_enqueue_scripts');
add_action('admin_enqueue_scripts', 'agency_admin_scripts');
add_action('widgets_init', 'agency_register_sidebars');
add_action('init', 'agency_register_post_types');
add_action('init', 'agency_register_taxonomies');
add_action('rest_api_init', ...);
add_action('acf/init', ...);
add_action('wp_body_open', 'agency_skip_link');
add_action('send_headers', ...);
add_action('save_post', ...);

// AJAX hooks — always register both
add_action('wp_ajax_filter_portfolio',        'agency_ajax_filter_portfolio');
add_action('wp_ajax_nopriv_filter_portfolio', 'agency_ajax_filter_portfolio');
```

### File Guards

Every PHP file must start with:

```php
<?php
defined('ABSPATH') || exit;
```

---

## CUSTOM POST TYPES

| CPT slug      | Label        | Archive slug | Has Archive |
| ------------- | ------------ | ------------ | ----------- |
| `portfolio`   | Portfolio    | `/portfolio` | Yes         |
| `service`     | Services     | `/services`  | Yes         |
| `testimonial` | Testimonials | —            | No          |
| `team_member` | Team         | `/team`      | Yes         |

## TAXONOMIES

| Taxonomy slug   | Attached to         | Type         |
| --------------- | ------------------- | ------------ |
| `portfolio_cat` | portfolio           | Hierarchical |
| `service_type`  | service             | Hierarchical |
| `industry`      | portfolio + service | Flat         |

---

## CSS RULES

**Plain CSS only. No Sass, no build step. Edit `assets/css/main.css` directly.**

### Never hardcode values — always use CSS custom properties

```css
/* ❌ Never */
color: #2563eb;
padding: 16px;
font-size: 1rem;

/* ✅ Always */
color: var(--color-primary);
padding: var(--spacing-4);
font-size: var(--font-size-base);
```

### Token reference (from `:root` in `assets/css/main.css`)

```css
/* Colors */
--color-primary        #2563eb
--color-primary-dark   #1d4ed8
--color-primary-light  #dbeafe
--color-secondary      #64748b
--color-accent         #f59e0b
--color-white / --color-black

/* Spacing (8pt grid) */
--spacing-1  = 4px      --spacing-6  = 24px
--spacing-2  = 8px      --spacing-8  = 32px
--spacing-3  = 12px     --spacing-10 = 40px
--spacing-4  = 16px     --spacing-12 = 48px
--spacing-5  = 20px     --spacing-16 = 64px
                        --spacing-20 = 80px
                        --spacing-24 = 96px
                        --spacing-32 = 128px

/* Typography */
--font-sans / --font-serif / --font-mono
--font-size-xs through --font-size-5xl
--font-weight-light (300) through --font-weight-black (900)

/* Border radius */
--radius-sm / --radius-md / --radius-lg / --radius-xl / --radius-full

/* Shadows */
--shadow-xs / --shadow-sm / --shadow-md / --shadow-lg / --shadow-xl

/* Transitions */
--transition-fast (150ms) / --transition-normal (300ms) / --transition-slow (500ms)

/* Z-index */
--z-dropdown (100) / --z-sticky (200) / --z-overlay (300) / --z-modal (400) / --z-toast (500)
```

### Mobile-first always

```css
/* ❌ Desktop-first (never do this) */
.card {
  width: 33%;
}
@media (max-width: 768px) {
  .card {
    width: 100%;
  }
}

/* ✅ Mobile-first (always) */
.card {
  width: 100%;
}
@media (min-width: 768px) {
  .card {
    width: 33%;
  }
}
```

### BEM Class Names — Established Components

```scss
// Buttons
.btn
.btn--primary / .btn--ghost / .btn--outline / .btn--white
.btn--sm / .btn--lg

// Cards
.card
.card__image
.card__body
.card__title
.card__excerpt
.card__cats
.card__link
.card--default / .card--[variant]

// Hero
.hero
.hero--centered / .hero--left / .hero--split
.hero__bg / .hero__overlay / .hero__content
.hero__heading / .hero__subheading

// Site Header
.site-header
.site-header--sticky / .site-header--transparent
.site-header__inner / .site-header__logo
.site-header__nav / .site-header__cta / .site-header__toggle

// Navigation
.nav-primary / .nav-primary__list
.nav-toggle / .nav-toggle__bar
.nav-overlay

// Layout
.container / .container--wide / .container--narrow
.grid / .grid--2 / .grid--3 / .grid--4 / .grid--auto
.section / .section--sm / .section--lg / .section--none

// Breadcrumbs
.breadcrumbs / .breadcrumbs__list
.breadcrumbs__item / .breadcrumbs__item--current
.breadcrumbs__link / .breadcrumbs__current / .breadcrumbs__sep

// Blocks (ACF Gutenberg)
.block-hero / .block-hero__bg / .block-hero__overlay / .block-hero__content

// Accessibility
.skip-link / .sr-only

// State classes
.is-open / .is-visible / .nav-open

// Tags / pills
.tag
```

### CSS file

```
assets/css/main.css   ← single file, edit directly, no build needed
```

All tokens, reset, typography, layout, components, and utilities are in one file, organized by section comments.

---

## JAVASCRIPT RULES

### No jQuery — ever

```javascript
// ❌ Never
$('.card').on('click', handler);
$.ajax({ url: ..., success: ... });

// ✅ Always
document.querySelector('.card').addEventListener('click', handler);
fetch(AgencyTheme.ajaxUrl, { method: 'POST', body: formData });
```

### IIFE pattern — flat JS, no imports, no build

```javascript
// Each feature is wrapped in an IIFE to avoid global scope
(function () {
  var el = document.querySelector(".site-header");
  if (!el) return;
  // ...
})();
```

### All JS is in one file (`assets/js/main.js`)

```
assets/js/main.js   ← single flat file, no imports, no build needed
  - Navigation IIFE   — sticky header, mobile menu toggle
  - Accordion IIFE    — accessible accordion
  - AjaxFilter IIFE   — portfolio AJAX filtering + load more
```

### WordPress JS globals (available on frontend)

```javascript
AgencyTheme.ajaxUrl; // wp-admin/admin-ajax.php
AgencyTheme.restUrl; // /wp-json/mytheme/v1/
AgencyTheme.nonce; // for AJAX (agency_nonce action)
AgencyTheme.restNonce; // for REST API
AgencyTheme.isLoggedIn; // boolean
AgencyTheme.i18n.loading; // 'Loading...'
AgencyTheme.i18n.noMore; // 'No more posts'
AgencyTheme.i18n.error; // 'Something went wrong'
```

### AJAX pattern

```javascript
const formData = new FormData();
formData.append("action", "filter_portfolio");
formData.append("nonce", AgencyTheme.nonce);

const res = await fetch(AgencyTheme.ajaxUrl, {
  method: "POST",
  body: formData,
});
const data = await res.json();

if (data.success) {
  /* data.data */
}
```

### Data attributes (used by JS — do not change)

```html
data-nav-toggle
<!-- mobile menu button -->
data-nav-menu
<!-- nav element controlled by toggle -->
data-nav-overlay
<!-- dark overlay behind mobile menu -->
data-grid
<!-- AJAX filter injects posts here -->
data-filter
<!-- filter category button -->
data-load-more
<!-- load more button -->
```

---

## TEMPLATE PARTS

### PHP template part structure (`template-parts/`)

```
global/
  site-header.php   — full header with ACF options integration
  site-footer.php   — footer
  skip-link.php     — accessibility skip link
  breadcrumbs.php   — schema.org breadcrumb trail

components/
  card.php          — reusable post card
  hero.php          — hero section
  cta.php           — call to action banner
  testimonial.php   — testimonial item
  pagination.php    — post pagination

blocks/
  block-hero.php        — ACF Gutenberg hero block
  block-cards.php       — ACF Gutenberg cards grid block
  block-cta.php         — ACF Gutenberg CTA block
  block-testimonials.php — ACF Gutenberg testimonials block
```

### How to call template parts

```php
// Simple
get_template_part('template-parts/global/breadcrumbs');

// With args (preferred for components)
agency_render('components/card', [
    'post_id'   => get_the_ID(),
    'title'     => get_the_title(),
    'permalink' => get_permalink(),
    'excerpt'   => get_the_excerpt(),
    'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-thumb'),
]);

// Using the component class
Agency_Component::render('card', ['variant' => 'featured']);
```

### Inside template parts — always use $args

```php
$title     = $args['title']     ?? '';
$permalink = $args['permalink'] ?? '#';
```

---

---

## PAGE TEMPLATES (`page-templates/`)

| File                     | Template Name | Use case                           |
| ------------------------ | ------------- | ---------------------------------- |
| `template-home.php`      | Home Page     | Site homepage with blocks          |
| `template-landing.php`   | Landing Page  | ACF Hero + Flexible Content        |
| `template-fullwidth.php` | Full Width    | No sidebar, wide container         |
| `template-static.php`    | Static Page   | No header/footer (coming soon etc) |

---

## ACF FIELDS

### ACF Blocks registered (Gutenberg)

| Block name     | Category      | Render template               |
| -------------- | ------------- | ----------------------------- |
| `hero-banner`  | agency-blocks | blocks/block-hero.php         |
| `cards-grid`   | agency-blocks | blocks/block-cards.php        |
| `cta-banner`   | agency-blocks | blocks/block-cta.php          |
| `testimonials` | agency-blocks | blocks/block-testimonials.php |

### ACF Options pages

| Slug                      | Purpose                         |
| ------------------------- | ------------------------------- |
| `agency-header-settings`  | Sticky, transparent, CTA button |
| `agency-footer-settings`  | Footer content                  |
| `agency-social-settings`  | Social media links              |
| `agency-seo-settings`     | SEO & scripts                   |
| `agency-contact-settings` | Phone, email, address           |

### ACF Option helper

```php
agency_option('global_phone')       // Phone number
agency_option('global_email')       // Email address
agency_option('global_address')     // Physical address
agency_option('social_facebook')    // Facebook URL
agency_option('social_instagram')   // Instagram URL
agency_option('social_linkedin')    // LinkedIn URL
agency_option('social_twitter')     // Twitter/X URL
agency_option('header_cta_label')   // Header CTA button label
agency_option('header_cta_url')     // Header CTA button URL
agency_option('header_sticky')      // Bool: sticky header
agency_option('header_transparent') // Bool: transparent on homepage
```

---

## REST API ENDPOINTS

```
GET /wp-json/mytheme/v1/portfolio   ?per_page=9&page=1&category=slug
GET /wp-json/mytheme/v1/options     returns all ACF options
GET /wp-json/mytheme/v1/site-info   returns name, menus, logo, description
```

All CPTs also have `acf` and `featured_image_url` fields exposed on the standard WP REST endpoints.

---

## REGISTERED IMAGE SIZES

| Name          | Width | Height | Crop | Use case              |
| ------------- | ----- | ------ | ---- | --------------------- |
| `card-thumb`  | 600   | 400    | Yes  | Card grid images      |
| `hero-large`  | 1920  | 800    | Yes  | Full-width hero BG    |
| `hero-medium` | 1280  | 600    | Yes  | Hero, single post     |
| `team-square` | 500   | 500    | Yes  | Team member portraits |
| `og-image`    | 1200  | 630    | Yes  | Social share images   |

---

## WIDGET AREAS (SIDEBARS)

| ID             | Name            |
| -------------- | --------------- |
| `sidebar-main` | Sidebar         |
| `footer-col-1` | Footer Column 1 |
| `footer-col-2` | Footer Column 2 |
| `footer-col-3` | Footer Column 3 |

---

## NAV MENU LOCATIONS

| Location  | Label              |
| --------- | ------------------ |
| `primary` | Primary Navigation |
| `footer`  | Footer Navigation  |
| `mobile`  | Mobile Navigation  |
| `social`  | Social Links       |

---

## NO BUILD SYSTEM

This theme has **no build step**. Edit files directly and refresh the browser.

| File                  | Edit directly? | Notes                                      |
| --------------------- | -------------- | ------------------------------------------ |
| `assets/css/main.css` | Yes            | Plain CSS, loaded by WordPress             |
| `assets/js/main.js`   | Yes            | Plain JS, loaded by WordPress              |
| `style.css`           | Yes            | Theme header only — do not add styles here |

---

## AI PROMPTING — PASTE THIS CONTEXT

When starting a new AI session on this theme, paste the block below:

```
PROJECT CONTEXT — Golden Globe WordPress Theme

THEME NAME:   Golden Globe
TEXT DOMAIN:  golden-globe
PHP PREFIX:   agency_ (functions) | AGENCY_ (constants)
PHP VERSION:  8.2+  |  WORDPRESS: 6.5+
JS:  Vanilla JS IIFEs — NO jQuery, NO npm, NO imports, NO build step
CSS: Plain CSS with CSS custom properties (var(--token)) — NO Sass, NO build step
TEMPLATING: PHP template parts only — NO Timber, NO Twig

SECURITY RULES:
- Every AJAX handler: check_ajax_referer('agency_nonce', 'nonce') as FIRST line
- All $_POST/$_GET inputs: sanitize before use (sanitize_text_field, absint, esc_url_raw, etc.)
- All outputs: escape (esc_html, esc_url, esc_attr, wp_kses_post)

NAMING:
- PHP functions:   agency_render(), agency_option(), agency_excerpt(), etc.
- CSS classes:     BEM — .card, .card__body, .card--featured
- JS:              IIFE functions, no modules, all in assets/js/main.js

CSS: Never hardcode values — always use var(--token) from :root in assets/css/main.css
JS:  Never use jQuery — use fetch(), querySelector(), addEventListener()
ARIA: All interactive and navigation elements need aria-label/aria-expanded/aria-controls
IMG:  Always include width, height, loading="lazy", alt=""
```
