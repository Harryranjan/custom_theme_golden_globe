<?php
/**
 * Golden Globe — Native Fields Registration
 * Replaces acf-fields.php with native WordPress Meta Boxes.
 */

defined('ABSPATH') || exit;

// ─── HERO SECTION ────────────────────────────────────────────────────────────
agency_add_meta_box(
    'agency_hero_section',
    __('Hero Section', 'golden-globe'),
    [
        ['name' => 'hero_heading',    'label' => __('Heading', 'golden-globe'),          'type' => 'text'],
        ['name' => 'hero_subheading', 'label' => __('Sub Heading', 'golden-globe'),      'type' => 'textarea'],
        ['name' => 'hero_image',      'label' => __('Background Image', 'golden-globe'), 'type' => 'image'],
        ['name' => 'hero_btn_label',  'label' => __('Button Label', 'golden-globe'),     'type' => 'text'],
        ['name' => 'hero_btn_url',    'label' => __('Button URL', 'golden-globe'),       'type' => 'url'],
        ['name' => 'hero_layout',     'label' => __('Layout', 'golden-globe'),           'type' => 'select',
            'choices' => [
                'centered' => __('Centered', 'golden-globe'),
                'left'     => __('Left Aligned', 'golden-globe'),
                'split'    => __('Split (Text + Image)', 'golden-globe')
            ]
        ],
    ],
    ['page'] // Display on pages (filtered by template in future refinements if needed)
);

// ─── PAGE SECTIONS (FLEXIBLE CONTENT REPLACEMENT) ───────────────────────────
agency_add_meta_box(
    'agency_page_sections',
    __('Page Sections', 'golden-globe'),
    [
        [
            'name'  => 'page_sections',
            'label' => __('Sections Builder (JSON)', 'golden-globe'),
            'type'  => 'flexible_content',
            'instructions' => __('Available Slugs: features_grid, cta_banner, image_text, portfolio_showcase, services_list, contact_section, testimonials, faq, stats_bar.', 'golden-globe')
        ],
    ],
    ['page']
);

// ─── TEAM MEMBER DETAILS ─────────────────────────────────────────────────────
agency_add_meta_box(
    'agency_team_details',
    __('Team Member Details', 'golden-globe'),
    [
        ['name' => 'member_role',     'label' => __('Job Title / Role', 'golden-globe'), 'type' => 'text'],
        ['name' => 'member_speciality', 'label' => __('Speciality', 'golden-globe'),     'type' => 'text'],
        ['name' => 'member_email',    'label' => __('Email Address', 'golden-globe'),    'type' => 'email'],
        ['name' => 'member_phone',    'label' => __('Phone Number', 'golden-globe'),     'type' => 'text'],
        ['name' => 'member_linkedin', 'label' => __('LinkedIn URL', 'golden-globe'),    'type' => 'url'],
        ['name' => 'member_twitter',  'label' => __('Twitter / X URL', 'golden-globe'),   'type' => 'url'],
        ['name' => 'member_github',   'label' => __('GitHub URL', 'golden-globe'),       'type' => 'url'],
        ['name' => 'member_skills',   'label' => __('Skills & Expertise (JSON)', 'golden-globe'), 'type' => 'repeater'],
    ],
    'team_member'
);

// ─── LOCATION PAGE ───────────────────────────────────────────────────────────
agency_add_meta_box(
    'agency_location_settings',
    __('Location Settings', 'golden-globe'),
    [
        ['name' => 'layout_style',    'label' => __('Layout Style', 'golden-globe'), 'type' => 'select',
            'choices' => [
                'layout-1' => __('Layout 1 — Dark Hero / Sidebar Form', 'golden-globe'),
                'layout-2' => __('Layout 2 — Light Hero / Centered', 'golden-globe'),
                'layout-3' => __('Layout 3 — Gradient Hero / Split Rows', 'golden-globe')
            ]
        ],
        ['name' => 'location_type',   'label' => __('Location Type', 'golden-globe'), 'type' => 'select',
            'choices' => [
                'city'         => __('City', 'golden-globe'),
                'neighbourhood'=> __('Neighbourhood', 'golden-globe'),
                'street'       => __('Street', 'golden-globe')
            ]
        ],
        ['name' => 'city_name',       'label' => __('City / Area Name', 'golden-globe'), 'type' => 'text'],
        ['name' => 'state_abbr',      'label' => __('State Abbreviation', 'golden-globe'), 'type' => 'text'],
        ['name' => 'hero_tagline',    'label' => __('Hero Tagline', 'golden-globe'),     'type' => 'text'],
        ['name' => 'hero_bg_image',   'label' => __('Hero Background Image', 'golden-globe'), 'type' => 'image'],
        ['name' => 'location_phone',  'label' => __('Phone (override)', 'golden-globe'), 'type' => 'text'],
        ['name' => 'review_count',    'label' => __('Google Review Count', 'golden-globe'), 'type' => 'number'],
        ['name' => 'review_score',    'label' => __('Google Review Score', 'golden-globe'), 'type' => 'number'],
        ['name' => 'map_embed_url',   'label' => __('Google Map Embed URL', 'golden-globe'), 'type' => 'url'],
        ['name' => 'local_intro',     'label' => __('Local Intro Text', 'golden-globe'), 'type' => 'textarea'],
        ['name' => 'process_steps',    'label' => __('Process Steps (JSON)', 'golden-globe'), 'type' => 'repeater'],
        ['name' => 'neighbourhoods',   'label' => __('Neighbourhoods Served (JSON)', 'golden-globe'), 'type' => 'repeater'],
        ['name' => 'pricing_rows',     'label' => __('Pricing Table (JSON)', 'golden-globe'), 'type' => 'repeater'],
        ['name' => 'faq',              'label' => __('FAQ (JSON)', 'golden-globe'), 'type' => 'repeater'],
        ['name' => 'loc_meta_title',  'label' => __('Meta Title', 'golden-globe'),       'type' => 'text'],
        ['name' => 'loc_meta_desc',   'label' => __('Meta Description', 'golden-globe'), 'type' => 'textarea'],
        ['name' => 'loc_nap_address', 'label' => __('NAP Address', 'golden-globe'),      'type' => 'textarea'],
    ],
    'location'
);
