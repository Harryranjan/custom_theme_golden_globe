<?php
/**
 * Golden Globe — Native Theme Options
 * Replaces ACF options page with a native WordPress settings page.
 */

defined('ABSPATH') || exit;

/**
 * Register the Theme Options page.
 */
function agency_register_theme_options_page(): void {
    add_menu_page(
        __('Theme Options', 'golden-globe'),
        __('Theme Options', 'golden-globe'),
        'manage_options',
        'agency-theme-options',
        'agency_render_theme_options_page',
        'dashicons-admin-customizer',
        60
    );
}
add_action('admin_menu', 'agency_register_theme_options_page');

/**
 * Register settings.
 */
function agency_register_settings(): void {
    register_setting('agency_theme_options_group', 'agency_theme_options');
}
add_action('admin_init', 'agency_register_settings');

/**
 * Render the options page.
 */
function agency_render_theme_options_page(): void {
    $options = get_option('agency_theme_options');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('agency_theme_options_group');
            do_settings_sections('agency-theme-options');
            ?>
            
            <table class="form-table" role="presentation">
                <!-- Branding & Typography -->
                <tr>
                    <th scope="row" colspan="2" style="background:#eee; padding:10px;"><strong><?php _e('Branding & Typography', 'golden-globe'); ?></strong></th>
                </tr>
                <tr>
                    <th scope="row"><label for="brand_primary"><?php _e('Primary Color', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="color" name="agency_theme_options[brand_primary]" id="brand_primary" value="<?php echo esc_attr($options['brand_primary'] ?? '#2563eb'); ?>">
                        <p class="description"><?php _e('Used for buttons, links, and main highlights.', 'golden-globe'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brand_secondary"><?php _e('Secondary Color', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="color" name="agency_theme_options[brand_secondary]" id="brand_secondary" value="<?php echo esc_attr($options['brand_secondary'] ?? '#64748b'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="brand_accent"><?php _e('Accent Color', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="color" name="agency_theme_options[brand_accent]" id="brand_accent" value="<?php echo esc_attr($options['brand_accent'] ?? '#f59e0b'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="font_sans"><?php _e('Sans Serif Font (UI & Body)', 'golden-globe'); ?></label></th>
                    <td>
                        <select name="agency_theme_options[font_sans]" id="font_sans">
                            <option value="Inter" <?php selected($options['font_sans'] ?? 'Inter', 'Inter'); ?>>Inter (Modern)</option>
                            <option value="Roboto" <?php selected($options['font_sans'] ?? '', 'Roboto'); ?>>Roboto (Clean)</option>
                            <option value="Montserrat" <?php selected($options['font_sans'] ?? '', 'Montserrat'); ?>>Montserrat (Geometric)</option>
                            <option value="Poppins" <?php selected($options['font_sans'] ?? '', 'Poppins'); ?>>Poppins (Friendly)</option>
                            <option value="Open Sans" <?php selected($options['font_sans'] ?? '', 'Open Sans'); ?>>Open Sans (Classic)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="font_serif"><?php _e('Serif Font (Headings)', 'golden-globe'); ?></label></th>
                    <td>
                        <select name="agency_theme_options[font_serif]" id="font_serif">
                            <option value="Playfair Display" <?php selected($options['font_serif'] ?? 'Playfair Display', 'Playfair Display'); ?>>Playfair Display (Elegant)</option>
                            <option value="Lora" <?php selected($options['font_serif'] ?? '', 'Lora'); ?>>Lora (Editorial)</option>
                            <option value="Merriweather" <?php selected($options['font_serif'] ?? '', 'Merriweather'); ?>>Merriweather (Readability)</option>
                            <option value="Crimson Pro" <?php selected($options['font_serif'] ?? '', 'Crimson Pro'); ?>>Crimson (Classic)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="border_radius"><?php _e('Visual Style (Border Radius)', 'golden-globe'); ?></label></th>
                    <td>
                        <select name="agency_theme_options[border_radius]" id="border_radius">
                            <option value="0px" <?php selected($options['border_radius'] ?? '8px', '0px'); ?>>Sharp (0px)</option>
                            <option value="4px" <?php selected($options['border_radius'] ?? '', '4px'); ?>>Subtle (4px)</option>
                            <option value="8px" <?php selected($options['border_radius'] ?? '', '8px'); ?>>Rounded (8px)</option>
                            <option value="12px" <?php selected($options['border_radius'] ?? '', '12px'); ?>>Extra Rounded (12px)</option>
                            <option value="20px" <?php selected($options['border_radius'] ?? '', '20px'); ?>>Pill Style (20px)</option>
                        </select>
                    </td>
                </tr>

                <!-- Header & Contact -->
                <tr>
                    <th scope="row" colspan="2" style="background:#eee; padding:10px;"><strong><?php _e('Header & Contact', 'golden-globe'); ?></strong></th>
                </tr>
                <tr>
                    <th scope="row"><label for="header_phone"><?php _e('Phone Number', 'golden-globe'); ?></label></th>
                    <td><input type="text" name="agency_theme_options[header_phone]" id="header_phone" value="<?php echo esc_attr($options['header_phone'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="header_email"><?php _e('Email Address', 'golden-globe'); ?></label></th>
                    <td><input type="email" name="agency_theme_options[header_email]" id="header_email" value="<?php echo esc_attr($options['header_email'] ?? ''); ?>" class="regular-text"></td>
                </tr>

                <!-- Footer -->
                <tr>
                    <th scope="row" colspan="2" style="background:#eee; padding:10px;"><strong><?php _e('Footer', 'golden-globe'); ?></strong></th>
                </tr>
                <tr>
                    <th scope="row"><label for="footer_text"><?php _e('Footer Copyright Text', 'golden-globe'); ?></label></th>
                    <td><input type="text" name="agency_theme_options[footer_text]" id="footer_text" value="<?php echo esc_attr($options['footer_text'] ?? ''); ?>" class="large-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="footer_logo"><?php _e('Footer Logo ID', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="number" name="agency_theme_options[footer_logo]" id="footer_logo" value="<?php echo esc_attr($options['footer_logo'] ?? ''); ?>" class="small-text">
                        <p class="description"><?php _e('Enter the Attachment ID for the footer logo.', 'golden-globe'); ?></p>
                    </td>
                </tr>

                <!-- Social -->
                <tr>
                    <th scope="row" colspan="2" style="background:#eee; padding:10px;"><strong><?php _e('Social Links', 'golden-globe'); ?></strong></th>
                </tr>
                <tr>
                    <th scope="row"><label for="social_facebook"><?php _e('Facebook URL', 'golden-globe'); ?></label></th>
                    <td><input type="url" name="agency_theme_options[social_facebook]" id="social_facebook" value="<?php echo esc_attr($options['social_facebook'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="social_twitter"><?php _e('Twitter URL', 'golden-globe'); ?></label></th>
                    <td><input type="url" name="agency_theme_options[social_twitter]" id="social_twitter" value="<?php echo esc_attr($options['social_twitter'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="social_instagram"><?php _e('Instagram URL', 'golden-globe'); ?></label></th>
                    <td><input type="url" name="agency_theme_options[social_instagram]" id="social_instagram" value="<?php echo esc_attr($options['social_instagram'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="social_linkedin"><?php _e('LinkedIn URL', 'golden-globe'); ?></label></th>
                    <td><input type="url" name="agency_theme_options[social_linkedin]" id="social_linkedin" value="<?php echo esc_attr($options['social_linkedin'] ?? ''); ?>" class="regular-text"></td>
                </tr>

                <!-- Contact Form Settings -->
                <tr>
                    <th scope="row" colspan="2" style="background:#eee; padding:10px;"><strong><?php _e('Contact Form Settings', 'golden-globe'); ?></strong></th>
                </tr>
                <tr>
                    <th scope="row"><label for="form_recipient"><?php _e('Recipient Email', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="email" name="agency_theme_options[form_recipient]" id="form_recipient" value="<?php echo esc_attr($options['form_recipient'] ?? get_option('admin_email')); ?>" class="regular-text">
                        <p class="description"><?php _e('Where the form submissions will be sent.', 'golden-globe'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_success_msg"><?php _e('Success Message', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="text" name="agency_theme_options[form_success_msg]" id="form_success_msg" value="<?php echo esc_attr($options['form_success_msg'] ?? __('Thank you! Your message has been sent.', 'golden-globe')); ?>" class="large-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="form_error_msg"><?php _e('Error Message', 'golden-globe'); ?></label></th>
                    <td>
                        <input type="text" name="agency_theme_options[form_error_msg]" id="form_error_msg" value="<?php echo esc_attr($options['form_error_msg'] ?? __('Oops! Something went wrong. Please try again.', 'golden-globe')); ?>" class="large-text">
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
