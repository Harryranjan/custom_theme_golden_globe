<?php
/**
 * Section: Client Logos
 */
$title = agency_get_sub_field('title') ?: __('Trusted by Industry Leaders', 'golden-globe');
?>

<section class="section section-client-logos">
    <div class="container">
        <?php if ($title) : ?>
            <h2 class="section-title text-center reveal-fade" style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-gray-500); margin-bottom: 3rem;">
                <?php echo esc_html($title); ?>
            </h2>
        <?php endif; ?>

        <?php if (agency_have_rows('logos')) : ?>
            <div class="client-logos-grid reveal-fade">
                <?php while (agency_have_rows('logos')) : agency_the_row(); 
                    $logo = agency_get_sub_field('logo');
                    if ($logo) :
                    ?>
                    <div class="client-logo-item">
                        <img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" loading="lazy">
                    </div>
                <?php endif; endwhile; ?>
            </div>
        <?php else : ?>
            <!-- Placeholder for agency view if empty -->
            <div class="client-logos-grid reveal-fade" style="opacity: 0.3;">
                <div class="client-logo-item"><span style="font-weight:bold; font-size:1.5rem;">LOGO</span></div>
                <div class="client-logo-item"><span style="font-weight:bold; font-size:1.5rem;">LOGO</span></div>
                <div class="client-logo-item"><span style="font-weight:bold; font-size:1.5rem;">LOGO</span></div>
                <div class="client-logo-item"><span style="font-weight:bold; font-size:1.5rem;">LOGO</span></div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-client-logos { padding: 60px 0; border-top: 1px solid var(--color-gray-100); border-bottom: 1px solid var(--color-gray-100); }
.client-logos-grid { 
    display: flex; flex-wrap: wrap; justify-content: center; align-items: center; 
    gap: 4rem; opacity: 0.6; filter: grayscale(100%); transition: opacity var(--transition-normal);
}
.client-logos-grid:hover { opacity: 1; filter: grayscale(0); }
.client-logo-item img { max-width: 150px; height: auto; display: block; }

@media (max-width: 768px) {
    .client-logos-grid { gap: 2rem; }
    .client-logo-item img { max-width: 100px; }
}
</style>
