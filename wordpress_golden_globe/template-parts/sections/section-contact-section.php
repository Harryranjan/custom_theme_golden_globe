<?php
/**
 * Section: Contact Section
 */
$title = agency_get_sub_field('title') ?: __('Get in Touch', 'golden-globe');
$desc  = agency_get_sub_field('desc')  ?: __('Have a project in mind? We\'d love to hear from you.', 'golden-globe');
?>

<section class="section section-contact-home">
    <div class="container">
        <div class="contact-home-grid">
            <div class="contact-home-content reveal-fade">
                <span class="eyebrow"><?php _e('Contact Us', 'golden-globe'); ?></span>
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <p class="section-desc"><?php echo esc_html($desc); ?></p>
                
                <div class="contact-methods">
                    <?php if ($phone = agency_get_option('header_phone')) : ?>
                        <div class="contact-method">
                            <div class="contact-method__icon"><?php agency_icon('phone'); ?></div>
                            <div class="contact-method__info">
                                <strong><?php _e('Call Us', 'golden-globe'); ?></strong>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($email = agency_get_option('header_email')) : ?>
                        <div class="contact-method">
                            <div class="contact-method__icon"><?php agency_icon('mail'); ?></div>
                            <div class="contact-method__info">
                                <strong><?php _e('Email Us', 'golden-globe'); ?></strong>
                                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="contact-home-form-wrapper reveal-fade" style="transition-delay: 0.2s;">
                <?php agency_render('components/form-contact'); ?>
            </div>
        </div>
    </div>
</section>

<style>
.section-contact-home { padding: 80px 0; background-color: var(--color-white); }
.contact-home-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: flex-start; }
@media (max-width: 991px) { .contact-home-grid { grid-template-columns: 1fr; gap: 3rem; } }

.contact-home-content .section-desc { color: var(--color-gray-600); font-size: 1.125rem; margin-bottom: 3rem; max-width: 500px; }
.contact-methods { display: flex; flex-direction: column; gap: 2rem; }
.contact-method { display: flex; align-items: center; gap: 1.5rem; }
.contact-method__icon { 
    width: 56px; height: 56px; background: var(--color-primary-light); color: var(--color-primary); 
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.contact-method__icon svg { width: 24px; height: 24px; }
.contact-method__info strong { display: block; font-size: 0.875rem; color: var(--color-gray-500); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
.contact-method__info a { font-size: 1.25rem; font-weight: 700; color: var(--color-black); text-decoration: none; }
.contact-method__info a:hover { color: var(--color-primary); }

.contact-home-form-wrapper { background: var(--color-gray-50); padding: 3rem; border-radius: var(--radius-xl); border: 1px solid var(--color-gray-200); }
@media (max-width: 576px) { .contact-home-form-wrapper { padding: 2rem 1.5rem; } }
</style>
