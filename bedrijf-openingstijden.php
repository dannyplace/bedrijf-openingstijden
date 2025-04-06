<?php
/*
Plugin Name: Bedrijf Openingstijden
Description: Toon openingstijden en uitzonderingen met styling en een filterbare shortcode.
Version: 1.2
Author: Jouw Naam
*/

if (!defined('ABSPATH')) exit;

include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

function openingstijden_enqueue_styles() {
    wp_enqueue_style('openingstijden-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'openingstijden_enqueue_styles');
