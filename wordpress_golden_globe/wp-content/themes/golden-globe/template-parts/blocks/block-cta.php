<?php
defined('ABSPATH') || exit;

$title    = agency_get_field('cta_title');
$text     = agency_get_field('cta_text');
$button   = agency_get_field('cta_button');
$bg_color = agency_get_field('cta_bg_color') ?: '#2563eb';

agency_render('components/cta', compact('title', 'text', 'button', 'bg_color'));
