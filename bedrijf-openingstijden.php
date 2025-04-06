<?php
/*
Plugin Name: Bedrijf Openingstijden
Description: Beheer en toon de openingstijden van je bedrijf met uitzonderingen en styling.
Version: 1.0
Author: Jouw Naam
*/

if (!defined('ABSPATH')) exit;

include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

function openingstijden_enqueue_styles() {
    wp_enqueue_style('openingstijden-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'openingstijden_enqueue_styles');
