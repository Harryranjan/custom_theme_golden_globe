<?php
defined('ABSPATH') || exit;

add_action('acf/init', function (): void {
    if (!function_exists('acf_add_local_field_group')) return;

    // ─── HERO SECTION ───
    acf_add_local_field_group([
        'key'    => 'group_hero',
        'title'  => 'Hero Section',
        'fields' => [
            ['key' => 'field_hero_heading',    'label' => 'Heading',          'name' => 'hero_heading',    'type' => 'text'],
            ['key' => 'field_hero_subheading', 'label' => 'Sub Heading',      'name' => 'hero_subheading', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_hero_image',      'label' => 'Background Image', 'name' => 'hero_image',      'type' => 'image', 'return_format' => 'array', 'preview_size' => 'hero-medium'],
            ['key' => 'field_hero_btn_label',  'label' => 'Button Label',     'name' => 'hero_btn_label',  'type' => 'text'],
            ['key' => 'field_hero_btn_url',    'label' => 'Button URL',       'name' => 'hero_btn_url',    'type' => 'url'],
            ['key' => 'field_hero_layout',     'label' => 'Layout',           'name' => 'hero_layout',     'type' => 'select',
                'choices'       => ['centered' => 'Centered', 'left' => 'Left Aligned', 'split' => 'Split (Text + Image)'],
                'default_value' => 'centered',
            ],
        ],
        'location'   => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-landing.php']],
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-home.php']],
        ],
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
                    'layout_features' => [
                        'key'        => 'layout_features',
                        'name'       => 'features_grid',
                        'label'      => 'Features Grid',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_feat_title', 'label' => 'Section Title', 'name' => 'title', 'type' => 'text'],
                            ['key' => 'field_feat_items', 'label' => 'Features',      'name' => 'items', 'type' => 'repeater',
                                'sub_fields' => [
                                    ['key' => 'field_feat_icon', 'label' => 'Icon',        'name' => 'icon', 'type' => 'image'],
                                    ['key' => 'field_feat_name', 'label' => 'Name',        'name' => 'name', 'type' => 'text'],
                                    ['key' => 'field_feat_desc', 'label' => 'Description', 'name' => 'desc', 'type' => 'textarea'],
                                ],
                            ],
                        ],
                    ],
                    'layout_cta' => [
                        'key'        => 'layout_cta',
                        'name'       => 'cta_banner',
                        'label'      => 'CTA Banner',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_cta_title', 'label' => 'Title',            'name' => 'title',    'type' => 'text'],
                            ['key' => 'field_cta_text',  'label' => 'Text',             'name' => 'text',     'type' => 'textarea'],
                            ['key' => 'field_cta_btn',   'label' => 'Button',           'name' => 'button',   'type' => 'link'],
                            ['key' => 'field_cta_bg',    'label' => 'Background Color', 'name' => 'bg_color', 'type' => 'color_picker'],
                        ],
                    ],

                    // ── Image + Text ─────────────────────────────────────
                    'layout_image_text' => [
                        'key'        => 'layout_image_text',
                        'name'       => 'image_text',
                        'label'      => 'Image + Text',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_it_eyebrow',  'label' => 'Eyebrow Label', 'name' => 'eyebrow',         'type' => 'text'],
                            ['key' => 'field_it_title',    'label' => 'Heading',       'name' => 'title',           'type' => 'text'],
                            ['key' => 'field_it_text',     'label' => 'Body Text',     'name' => 'text',            'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0],
                            ['key' => 'field_it_btn',      'label' => 'Button',        'name' => 'button',          'type' => 'link'],
                            ['key' => 'field_it_image',    'label' => 'Image',         'name' => 'image',           'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
                            ['key' => 'field_it_position', 'label' => 'Image Side',    'name' => 'image_position',  'type' => 'select',
                                'choices'       => ['left' => 'Left', 'right' => 'Right'],
                                'default_value' => 'right',
                            ],
                            ['key' => 'field_it_bg', 'label' => 'Background', 'name' => 'bg', 'type' => 'select',
                                'choices'       => ['white' => 'White', 'light' => 'Light grey', 'dark' => 'Dark'],
                                'default_value' => 'white',
                            ],
                        ],
                    ],

                    // ── Stats Bar ────────────────────────────────────────
                    'layout_stats' => [
                        'key'        => 'layout_stats',
                        'name'       => 'stats_bar',
                        'label'      => 'Stats Bar',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_sb_title', 'label' => 'Section Title', 'name' => 'title', 'type' => 'text'],
                            ['key' => 'field_sb_bg',    'label' => 'Background',    'name' => 'bg',    'type' => 'select',
                                'choices'       => ['primary' => 'Brand blue', 'dark' => 'Dark', 'light' => 'Light grey', 'white' => 'White'],
                                'default_value' => 'primary',
                            ],
                            ['key' => 'field_sb_items', 'label' => 'Stats', 'name' => 'items', 'type' => 'repeater', 'min' => 2, 'max' => 6,
                                'sub_fields' => [
                                    ['key' => 'field_sb_prefix', 'label' => 'Prefix (e.g. $)', 'name' => 'prefix', 'type' => 'text'],
                                    ['key' => 'field_sb_number', 'label' => 'Number',          'name' => 'number', 'type' => 'text'],
                                    ['key' => 'field_sb_suffix', 'label' => 'Suffix (e.g. +)', 'name' => 'suffix', 'type' => 'text'],
                                    ['key' => 'field_sb_label',  'label' => 'Label',           'name' => 'label',  'type' => 'text'],
                                ],
                            ],
                        ],
                    ],

                    // ── FAQ Accordion ────────────────────────────────────
                    'layout_faq' => [
                        'key'        => 'layout_faq',
                        'name'       => 'faq',
                        'label'      => 'FAQ Accordion',
                        'display'    => 'block',
                        'sub_fields' => [
                            ['key' => 'field_faq_title', 'label' => 'Section Title', 'name' => 'title', 'type' => 'text'],
                            ['key' => 'field_faq_intro', 'label' => 'Intro Text',    'name' => 'intro', 'type' => 'textarea', 'rows' => 2],
                            ['key' => 'field_faq_items', 'label' => 'Questions',     'name' => 'items', 'type' => 'repeater',
                                'sub_fields' => [
                                    ['key' => 'field_faq_q', 'label' => 'Question', 'name' => 'question', 'type' => 'text'],
                                    ['key' => 'field_faq_a', 'label' => 'Answer',   'name' => 'answer',   'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ─── TESTIMONIALS LAYOUT ───
            'layout_testimonials' => [
                'key'        => 'layout_testimonials',
                'name'       => 'testimonials',
                'label'      => 'Testimonials',
                'display'    => 'block',
                'sub_fields' => [
                    ['key' => 'field_testi_title',  'label' => 'Section Title',   'name' => 'title',   'type' => 'text'],
                    ['key' => 'field_testi_intro',  'label' => 'Intro Text',      'name' => 'intro',   'type' => 'textarea', 'rows' => 2],
                    ['key' => 'field_testi_layout', 'label' => 'Display Style',   'name' => 'layout',  'type' => 'radio',
                        'choices'       => ['grid' => 'Grid (3 columns)', 'featured' => 'Featured (large quote)'],
                        'default_value' => 'grid',
                        'layout'        => 'horizontal',
                    ],
                    ['key' => 'field_testi_count',  'label' => 'Number to show',  'name' => 'count',   'type' => 'number', 'default_value' => 3, 'min' => 1, 'max' => 12],
                    ['key' => 'field_testi_bg',     'label' => 'Background',      'name' => 'bg',      'type' => 'select',
                        'choices'       => ['white' => 'White', 'light' => 'Light Gray', 'dark' => 'Dark', 'primary' => 'Brand Primary'],
                        'default_value' => 'light',
                    ],
                ],
            ],
        ],
        'location'   => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-landing.php']],
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-home.php']],
        ],
        'menu_order' => 10,
    ]);

    // ─── TEAM MEMBER FIELDS ───
    acf_add_local_field_group([
        'key'    => 'group_team_member',
        'title'  => 'Team Member Details',
        'fields' => [

            // ── Identity tab ────────────────────────────────────────────────
            ['key' => 'field_tm_tab_identity', 'label' => 'Identity', 'name' => '', 'type' => 'tab'],

            [
                'key'          => 'field_tm_role',
                'label'        => 'Job Title / Role',
                'name'         => 'member_role',
                'type'         => 'text',
                'placeholder'  => 'e.g. Lead Designer',
                'instructions' => 'Displayed below the name in the hero.',
            ],
            [
                'key'          => 'field_tm_speciality',
                'label'        => 'Speciality',
                'name'         => 'member_speciality',
                'type'         => 'text',
                'placeholder'  => 'e.g. UI/UX & Brand Identity',
                'instructions' => 'Short area of expertise shown under the role.',
            ],

            // ── Contact tab ─────────────────────────────────────────────────
            ['key' => 'field_tm_tab_contact', 'label' => 'Contact', 'name' => '', 'type' => 'tab'],

            [
                'key'   => 'field_tm_email',
                'label' => 'Email Address',
                'name'  => 'member_email',
                'type'  => 'email',
            ],
            [
                'key'         => 'field_tm_phone',
                'label'       => 'Phone Number',
                'name'        => 'member_phone',
                'type'        => 'text',
                'placeholder' => '+1 (555) 000-0000',
            ],

            // ── Social tab ──────────────────────────────────────────────────
            ['key' => 'field_tm_tab_social', 'label' => 'Social', 'name' => '', 'type' => 'tab'],

            [
                'key'         => 'field_tm_linkedin',
                'label'       => 'LinkedIn URL',
                'name'        => 'member_linkedin',
                'type'        => 'url',
                'placeholder' => 'https://linkedin.com/in/username',
            ],
            [
                'key'         => 'field_tm_twitter',
                'label'       => 'Twitter / X URL',
                'name'        => 'member_twitter',
                'type'        => 'url',
                'placeholder' => 'https://x.com/username',
            ],
            [
                'key'         => 'field_tm_github',
                'label'       => 'GitHub URL',
                'name'        => 'member_github',
                'type'        => 'url',
                'placeholder' => 'https://github.com/username',
            ],

            // ── Skills tab ──────────────────────────────────────────────────
            ['key' => 'field_tm_tab_skills', 'label' => 'Skills', 'name' => '', 'type' => 'tab'],

            [
                'key'          => 'field_tm_skills',
                'label'        => 'Skills & Expertise',
                'name'         => 'member_skills',
                'type'         => 'repeater',
                'button_label' => 'Add Skill',
                'layout'       => 'table',
                'min'          => 0,
                'max'          => 20,
                'sub_fields'   => [
                    [
                        'key'         => 'field_tm_skill_name',
                        'label'       => 'Skill',
                        'name'        => 'skill_name',
                        'type'        => 'text',
                        'placeholder' => 'e.g. Web Design',
                        'column_width' => '70',
                    ],
                    [
                        'key'          => 'field_tm_skill_level',
                        'label'        => 'Level (%)',
                        'name'         => 'skill_level',
                        'type'         => 'number',
                        'min'          => 0,
                        'max'          => 100,
                        'default_value'=> 80,
                        'placeholder'  => '0–100',
                        'column_width' => '30',
                    ],
                ],
            ],
        ],
        'location'   => [[['param' => 'post_type', 'operator' => '==', 'value' => 'team_member']]],
        'menu_order' => 0,
        'style'      => 'seamless',
    ]);

    // ─── LOCATION CPT ───────────────────────────────────────────────────────────
    acf_add_local_field_group([
        'key'    => 'group_location',
        'title'  => 'Location Page',
        'fields' => [

            // ── Tab: Identity ────────────────────────────────────────────────
            ['key' => 'field_loc_tab_identity', 'label' => 'Identity', 'name' => '', 'type' => 'tab', 'placement' => 'top'],

            [
                'key'           => 'field_loc_layout_style',
                'label'         => 'Layout Style',
                'name'          => 'layout_style',
                'type'          => 'radio',
                'choices'       => [
                    'layout-1' => 'Layout 1 — Dark Hero / Sidebar Form / Services First',
                    'layout-2' => 'Layout 2 — Light Hero / Centered / Neighbourhoods First',
                    'layout-3' => 'Layout 3 — Gradient Hero / Full-Width Split Rows',
                ],
                'default_value' => 'layout-1',
                'layout'        => 'vertical',
                'instructions'  => 'Controls the visual arrangement of all sections on this page.',
            ],
            [
                'key'           => 'field_loc_type',
                'label'         => 'Location Type',
                'name'          => 'location_type',
                'type'          => 'radio',
                'choices'       => [
                    'city'         => 'City — full 13-section layout',
                    'neighbourhood'=> 'Neighbourhood — focused 8-section layout',
                    'street'       => 'Street — lean 5-section layout (min 300 words)',
                ],
                'default_value' => 'city',
                'layout'        => 'vertical',
                'instructions'  => 'Controls which sections are rendered. Street pages require at least 300 words in Local Intro.',
            ],
            ['key' => 'field_loc_city_name',    'label' => 'City / Area Name', 'name' => 'city_name',    'type' => 'text',   'instructions' => 'e.g. Amherst — used in headings and alt tags across the page.'],
            ['key' => 'field_loc_state_abbr',   'label' => 'State Abbreviation','name' => 'state_abbr', 'type' => 'text',   'default_value' => 'NY', 'maxlength' => 5],
            ['key' => 'field_loc_hero_tagline',  'label' => 'Hero Tagline',    'name' => 'hero_tagline', 'type' => 'text',   'instructions' => 'Short line under the H1, e.g. "HOA-Compliant Work & Mature Canopy Management"'],
            ['key' => 'field_loc_hero_bg',       'label' => 'Hero Background Image', 'name' => 'hero_bg_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
            ['key' => 'field_loc_phone',         'label' => 'Phone (override)', 'name' => 'location_phone', 'type' => 'text', 'instructions' => 'Leave blank to inherit global phone number.'],
            ['key' => 'field_loc_review_count',  'label' => 'Google Review Count', 'name' => 'review_count', 'type' => 'number', 'default_value' => 0],
            ['key' => 'field_loc_review_score',  'label' => 'Google Review Score', 'name' => 'review_score', 'type' => 'number', 'default_value' => 4.9, 'step' => 0.1, 'min' => 1, 'max' => 5],
            ['key' => 'field_loc_map_embed',     'label' => 'Google Map Embed URL', 'name' => 'map_embed_url', 'type' => 'url', 'instructions' => 'Paste the src URL from Google Maps embed code (not the full iframe).'],
            ['key' => 'field_loc_coverage_map',  'label' => 'Coverage Map Image', 'name' => 'coverage_map_img', 'type' => 'image', 'return_format' => 'array'],

            // ── Tab: Content ─────────────────────────────────────────────────
            ['key' => 'field_loc_tab_content', 'label' => 'Content', 'name' => '', 'type' => 'tab', 'placement' => 'top'],

            [
                'key'          => 'field_loc_local_intro',
                'label'        => 'Local Intro Text',
                'name'         => 'local_intro',
                'type'         => 'wysiwyg',
                'tabs'         => 'all',
                'toolbar'      => 'full',
                'media_upload' => 0,
                'instructions' => 'Minimum 300 words. Mention specific streets, landmarks, tree species, HOA names, and local ordinances. NO placeholder text from other cities.',
            ],
            [
                'key'          => 'field_loc_process_steps',
                'label'        => 'Process Steps',
                'name'         => 'process_steps',
                'type'         => 'repeater',
                'min'          => 4,
                'max'          => 6,
                'layout'       => 'table',
                'button_label' => 'Add Step',
                'sub_fields'   => [
                    ['key' => 'field_loc_step_title', 'label' => 'Step Title', 'name' => 'step_title', 'type' => 'text'],
                    ['key' => 'field_loc_step_body',  'label' => 'Step Body',  'name' => 'step_body',  'type' => 'textarea', 'rows' => 3],
                    ['key' => 'field_loc_step_icon',  'label' => 'Icon (emoji or SVG class)', 'name' => 'step_icon', 'type' => 'text', 'default_value' => '📞'],
                ],
            ],
            [
                'key'          => 'field_loc_neighbourhoods',
                'label'        => 'Neighbourhoods / Sub-Areas Served',
                'name'         => 'neighbourhoods',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 20,
                'layout'       => 'table',
                'button_label' => 'Add Neighbourhood',
                'sub_fields'   => [
                    ['key' => 'field_loc_nb_name',  'label' => 'Name',  'name' => 'nb_name',  'type' => 'text'],
                    ['key' => 'field_loc_nb_icon',  'label' => 'Icon',  'name' => 'nb_icon',  'type' => 'image', 'return_format' => 'array'],
                    ['key' => 'field_loc_nb_link',  'label' => 'Link (optional)', 'name' => 'nb_link',  'type' => 'url'],
                ],
            ],
            [
                'key'          => 'field_loc_pricing',
                'label'        => 'Pricing Table Rows',
                'name'         => 'pricing_rows',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 15,
                'layout'       => 'table',
                'button_label' => 'Add Row',
                'sub_fields'   => [
                    ['key' => 'field_loc_price_icon',  'label' => 'Icon', 'name' => 'price_icon',  'type' => 'text', 'default_value' => '🌲'],
                    ['key' => 'field_loc_price_label', 'label' => 'Label','name' => 'price_label', 'type' => 'text'],
                    ['key' => 'field_loc_price_range', 'label' => 'Range','name' => 'price_range', 'type' => 'text', 'placeholder' => 'e.g. $500 – $1,100'],
                ],
            ],
            [
                'key'          => 'field_loc_deep_dive_title',
                'label'        => 'Educational Section Heading',
                'name'         => 'deep_dive_title',
                'type'         => 'text',
                'instructions' => 'e.g. "THE AMHERST CANOPY CHALLENGE"',
            ],
            [
                'key'          => 'field_loc_deep_dive_content',
                'label'        => 'Educational Section Content',
                'name'         => 'deep_dive_content',
                'type'         => 'wysiwyg',
                'tabs'         => 'all',
                'toolbar'      => 'full',
                'instructions' => 'Long-form local SEO content. Mention local ordinances, species, landmarks, street names.',
            ],
            [
                'key'          => 'field_loc_faq',
                'label'        => 'FAQ',
                'name'         => 'faq',
                'type'         => 'repeater',
                'min'          => 0,
                'max'          => 15,
                'layout'       => 'block',
                'button_label' => 'Add FAQ',
                'sub_fields'   => [
                    ['key' => 'field_loc_faq_q', 'label' => 'Question', 'name' => 'faq_question', 'type' => 'text',     'instructions' => 'Mention streets, neighbourhoods, or landmarks for local SEO.'],
                    ['key' => 'field_loc_faq_a', 'label' => 'Answer',   'name' => 'faq_answer',   'type' => 'textarea', 'rows' => 4, 'instructions' => 'Link back to relevant city or service pages to create topical bridges.'],
                ],
            ],

            // ── Tab: Relations ───────────────────────────────────────────────
            ['key' => 'field_loc_tab_relations', 'label' => 'Relations', 'name' => '', 'type' => 'tab', 'placement' => 'top'],

            [
                'key'           => 'field_loc_related_services',
                'label'         => 'Related Services',
                'name'          => 'related_services',
                'type'          => 'relationship',
                'post_type'     => ['service'],
                'filters'       => ['search'],
                'return_format' => 'object',
                'min'           => 0,
                'max'           => 8,
                'instructions'  => 'Services offered in this location — shown in the Services Grid section.',
            ],
            [
                'key'           => 'field_loc_nearby_locations',
                'label'         => 'Nearby Locations',
                'name'          => 'nearby_locations',
                'type'          => 'relationship',
                'post_type'     => ['location'],
                'filters'       => ['search'],
                'return_format' => 'object',
                'min'           => 0,
                'max'           => 15,
                'instructions'  => 'Shown in the Coverage / Related Areas section. Creates internal links between spoke pages.',
            ],
            [
                'key'           => 'field_loc_testimonials',
                'label'         => 'Testimonials',
                'name'          => 'location_testimonials',
                'type'          => 'relationship',
                'post_type'     => ['testimonial'],
                'filters'       => ['search'],
                'return_format' => 'object',
                'min'           => 0,
                'max'           => 6,
                'instructions'  => 'Pin specific testimonials to this location. Leave empty to show latest global testimonials.',
            ],

            // ── Tab: SEO ─────────────────────────────────────────────────────
            ['key' => 'field_loc_tab_seo', 'label' => 'SEO', 'name' => '', 'type' => 'tab', 'placement' => 'top'],

            ['key' => 'field_loc_meta_title', 'label' => 'Meta Title',       'name' => 'loc_meta_title', 'type' => 'text',     'instructions' => 'Leave blank to auto-generate from city name + service name.'],
            ['key' => 'field_loc_meta_desc',  'label' => 'Meta Description', 'name' => 'loc_meta_desc',  'type' => 'textarea', 'rows' => 3, 'maxlength' => 160, 'instructions' => 'Max 160 characters.'],
            ['key' => 'field_loc_schema_nap', 'label' => 'NAP Address (schema)', 'name' => 'loc_nap_address', 'type' => 'textarea', 'rows' => 2, 'instructions' => 'Full address for LocalBusiness schema. e.g. 123 Main St, Amherst, NY 14221'],
        ],
        'location'   => [[['param' => 'post_type', 'operator' => '==', 'value' => 'location']]],
        'menu_order' => 0,
        'style'      => 'seamless',
    ]);
});
