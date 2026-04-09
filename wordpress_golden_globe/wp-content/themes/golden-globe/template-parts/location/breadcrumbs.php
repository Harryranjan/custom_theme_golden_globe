<?php
/**
 * Location — Breadcrumbs
 * Home › City › Neighbourhood › Street
 *
 * @package GoldenGlobe
 */
defined('ABSPATH') || exit;

// Skip on front page
if (is_front_page()) return;

// Build ancestor chain (oldest first)
$ancestors  = array_reverse(get_post_ancestors(get_the_ID()));
$crumbs     = [];

// Home
$crumbs[] = ['url' => home_url('/'), 'label' => esc_html__('Home', 'golden-globe')];

// Ancestors (parent, grandparent…)
foreach ($ancestors as $ancestor_id) {
    $crumbs[] = ['url' => get_permalink($ancestor_id), 'label' => get_the_title($ancestor_id)];
}

// Current page (no URL — it's the active crumb)
$crumbs[] = ['url' => '', 'label' => get_the_title()];

if (count($crumbs) < 2) return;
?>
<nav class="loc-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb navigation', 'golden-globe'); ?>">
    <div class="container">
        <ol class="loc-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($crumbs as $i => $crumb) :
                $is_last = ($i === count($crumbs) - 1);
                ?>
                <li class="loc-breadcrumbs__item<?php echo $is_last ? ' loc-breadcrumbs__item--current' : ''; ?>"
                    itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if (!$is_last) : ?>
                        <a class="loc-breadcrumbs__link"
                           itemprop="item"
                           href="<?php echo esc_url($crumb['url']); ?>">
                            <span itemprop="name"><?php echo esc_html($crumb['label']); ?></span>
                        </a>
                        <span class="loc-breadcrumbs__sep" aria-hidden="true">›</span>
                    <?php else : ?>
                        <span class="loc-breadcrumbs__current" itemprop="name" aria-current="page">
                            <?php echo esc_html($crumb['label']); ?>
                        </span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?php echo esc_attr($i + 1); ?>">
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
