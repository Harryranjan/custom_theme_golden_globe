<?php
/**
 * Golden Globe — Native Contact Form Component
 */

defined('ABSPATH') || exit;
?>

<div class="agency-contact-form-wrapper">
    <form id="agency-contact-form" class="agency-form" method="POST">
        <?php wp_nonce_field('agency_contact_form_submit', 'agency_contact_nonce'); ?>
        
        <!-- Honeypot field (invisible to users) -->
        <div style="display:none;">
            <label for="agency_hp_field">Do not fill this field</label>
            <input type="text" name="agency_hp_field" id="agency_hp_field">
        </div>

        <div class="agency-form__grid">
            <div class="agency-form__group">
                <label for="agency_name" class="agency-form__label"><?php _e('Full Name', 'golden-globe'); ?> <span class="required">*</span></label>
                <input type="text" name="agency_name" id="agency_name" class="agency-form__input" placeholder="<?php _e('John Doe', 'golden-globe'); ?>" required>
            </div>

            <div class="agency-form__group">
                <label for="agency_email" class="agency-form__label"><?php _e('Email Address', 'golden-globe'); ?> <span class="required">*</span></label>
                <input type="email" name="agency_email" id="agency_email" class="agency-form__input" placeholder="<?php _e('john@example.com', 'golden-globe'); ?>" required>
            </div>
        </div>

        <div class="agency-form__group">
            <label for="agency_subject" class="agency-form__label"><?php _e('Subject', 'golden-globe'); ?></label>
            <input type="text" name="agency_subject" id="agency_subject" class="agency-form__input" placeholder="<?php _e('How can we help?', 'golden-globe'); ?>">
        </div>

        <div class="agency-form__group">
            <label for="agency_message" class="agency-form__label"><?php _e('Your Message', 'golden-globe'); ?> <span class="required">*</span></label>
            <textarea name="agency_message" id="agency_message" class="agency-form__textarea" rows="6" placeholder="<?php _e('Tell us about your project...', 'golden-globe'); ?>" required></textarea>
        </div>

        <div class="agency-form__actions">
            <button type="submit" class="agency-btn agency-btn--primary">
                <span class="btn-text"><?php _e('Send Message', 'golden-globe'); ?></span>
                <span class="btn-loader" style="display:none;"><?php _e('Sending...', 'golden-globe'); ?></span>
            </button>
        </div>

        <div id="agency-form-response" class="agency-form__response" style="display:none; margin-top: 20px;"></div>
    </form>
</div>

<style>
.agency-form__group { margin-bottom: 1.5rem; }
.agency-form__label { display: block; font-weight: 600; font-size: 0.875rem; color: var(--color-gray-700); margin-bottom: 0.5rem; }
.agency-form__input, .agency-form__textarea { 
    width: 100%; border: 1px solid var(--color-gray-300); border-radius: var(--radius-md); 
    padding: 0.75rem 1rem; font-size: 1rem; transition: border-color var(--transition-fast);
}
.agency-form__input:focus, .agency-form__textarea:focus { outline: none; border-color: var(--color-primary); }
.agency-form__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media (max-width: 768px) { .agency-form__grid { grid-template-columns: 1fr; } }
.agency-form__response { padding: 1rem; border-radius: var(--radius-md); font-weight: 500; }
.agency-form__response--success { background: var(--color-primary-light); color: var(--color-primary-dark); }
.agency-form__response--error { background: #fee2e2; color: #b91c1c; }
.agency-form__label .required { color: var(--color-danger); }
</style>
