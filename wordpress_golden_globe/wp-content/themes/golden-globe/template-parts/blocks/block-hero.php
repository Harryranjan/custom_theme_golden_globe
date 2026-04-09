<?php
defined('ABSPATH') || exit;

$heading    = agency_get_field('hero_heading');
$subheading = agency_get_field('hero_subheading');
$image      = agency_get_field('hero_image');
$btn_label  = agency_get_field('hero_btn_label');
$btn_url    = agency_get_field('hero_btn_url');
$layout     = agency_get_field('hero_layout') ?: 'centered';

agency_render('components/hero', compact('heading', 'subheading', 'image', 'btn_label', 'btn_url', 'layout'));
