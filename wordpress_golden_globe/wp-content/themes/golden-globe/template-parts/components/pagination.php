<?php
defined('ABSPATH') || exit;

the_posts_pagination([
    'mid_size'           => 2,
    'prev_text'          => __('&laquo; Previous', 'golden-globe'),
    'next_text'          => __('Next &raquo;', 'golden-globe'),
    'screen_reader_text' => __('Posts navigation', 'golden-globe'),
    'aria_label'         => __('Posts', 'golden-globe'),
]);
