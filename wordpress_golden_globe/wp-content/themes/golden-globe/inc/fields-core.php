<?php
/**
 * Golden Globe — Native Meta Box Core
 * A lightweight replacement for ACF field management.
 */

defined('ABSPATH') || exit;

/**
 * Register a native meta box.
 */
function agency_add_meta_box(string $id, string $title, array $fields, $screen = 'page', string $context = 'normal'): void {
    add_action('add_meta_boxes', function () use ($id, $title, $fields, $screen, $context) {
        add_meta_box(
            $id,
            $title,
            function ($post) use ($fields) {
                agency_render_meta_box($post, $fields);
            },
            $screen,
            $context
        );
    });

    add_action('save_post', function ($post_id) use ($id, $fields) {
        agency_save_meta_box($post_id, $fields);
    });
}

/**
 * Render the meta box fields.
 */
function agency_render_meta_box(WP_Post $post, array $fields): void {
    wp_nonce_field('agency_meta_box_nonce', 'agency_meta_box_nonce_field');

    echo '<table class="form-table agency-meta-table">';
    foreach ($fields as $field) {
        $value = get_post_meta($post->ID, $field['name'], true);
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($field['name']) . '">' . esc_html($field['label']) . '</label></th>';
        echo '<td>';
        agency_render_field($field, $value);
        if (!empty($field['instructions'])) {
            echo '<p class="description">' . esc_html($field['instructions']) . '</p>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

/**
 * Render a single field based on type.
 */
function agency_render_field(array $field, $value): void {
    $name = esc_attr($field['name']);
    $type = $field['type'] ?? 'text';

    switch ($type) {
        case 'text':
        case 'url':
        case 'email':
        case 'number':
            echo '<input type="' . esc_attr($type) . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr($value) . '" class="regular-text">';
            break;

        case 'textarea':
            echo '<textarea name="' . $name . '" id="' . $name . '" rows="5" class="large-text">' . esc_textarea($value) . '</textarea>';
            break;

        case 'select':
            echo '<select name="' . $name . '" id="' . $name . '">';
            foreach ($field['choices'] as $val => $label) {
                echo '<option value="' . esc_attr($val) . '" ' . selected($value, $val, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
            break;

        case 'image':
            $img_url = '';
            if ($value) {
                if (is_array($value)) {
                    $img_url = $value['url'] ?? '';
                    $value = $value['id'] ?? '';
                } elseif (is_numeric($value)) {
                    $img_url = wp_get_attachment_image_url($value, 'thumbnail');
                }
            }
            echo '<div class="agency-image-field-container">';
            echo '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . esc_attr($value) . '" class="agency-image-id">';
            echo '<div class="agency-image-preview" style="margin-bottom: 10px;">';
            if ($img_url) {
                echo '<img src="' . esc_url($img_url) . '" style="max-width: 150px; height: auto; border: 1px solid #ddd;">';
            }
            echo '</div>';
            echo '<button type="button" class="button agency-upload-button">' . __('Select Image', 'golden-globe') . '</button>';
            echo ' <button type="button" class="button agency-remove-button" ' . ($img_url ? '' : 'style="display:none;"') . '>' . __('Remove', 'golden-globe') . '</button>';
            echo '</div>';
            break;

        case 'repeater':
        case 'flexible_content':
            // Stored as JSON for simplicity in this plugin-free version
            echo '<textarea name="' . $name . '" id="' . $name . '" class="large-text agency-json-field" style="font-family: monospace; font-size: 12px;" rows="8">' . esc_textarea(is_string($value) ? $value : wp_json_encode($value, JSON_PRETTY_PRINT)) . '</textarea>';
            echo '<p class="description">' . __('Note: Repeaters are currently managed via JSON in this version.', 'golden-globe') . '</p>';
            break;
    }
}

/**
 * Save meta box data.
 */
function agency_save_meta_box(int $post_id, array $fields): void {
    if (!isset($_POST['agency_meta_box_nonce_field']) || !wp_verify_nonce($_POST['agency_meta_box_nonce_field'], 'agency_meta_box_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    foreach ($fields as $field) {
        $name = $field['name'];
        if (isset($_POST[$name])) {
            $value = $_POST[$name];
            
            // Handle JSON fields (Repeaters/Flexible)
            if (($field['type'] ?? '') === 'repeater' || ($field['type'] ?? '') === 'flexible_content') {
               $decoded = json_decode(wp_unslash($value), true);
               if (json_last_error() === JSON_ERROR_NONE) {
                   update_post_meta($post_id, $name, $decoded);
                   continue;
               }
            }

            update_post_meta($post_id, $name, sanitize_text_field(wp_unslash($value)));
        }
    }
}

/**
 * Enqueue Media Scripts for Image Uploads.
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_media();
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.agency-upload-button').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.agency-image-field-container');
            var input = container.find('.agency-image-id');
            var preview = container.find('.agency-image-preview');
            var removeBtn = container.find('.agency-remove-button');

            var frame = wp.media({
                title: '<?php _e('Select Image', 'golden-globe'); ?>',
                button: { text: '<?php _e('Use Image', 'golden-globe'); ?>' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                input.val(attachment.id);
                preview.html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto; border: 1px solid #ddd;">');
                removeBtn.show();
            });

            frame.open();
        });

        $('.agency-remove-button').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var container = button.closest('.agency-image-field-container');
            container.find('.agency-image-id').val('');
            container.find('.agency-image-preview').empty();
            button.hide();
        });
    });
    </script>
    <?php
});
