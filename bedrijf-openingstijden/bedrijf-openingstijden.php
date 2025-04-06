<?php
/*
Plugin Name: Bedrijf Openingstijden
Description: Beheer de openingstijden van jouw bedrijf.
Version: 1.0
Author: Danny's Place
*/

if (!defined('ABSPATH')) exit;

function bedrijf_openingstijden_load() {
    include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
    include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
}
add_action('plugins_loaded', 'bedrijf_openingstijden_load');

function openingstijden_enqueue_frontend_styles() {
    $opties = get_option('openingstijden_data');

    $kleur = $opties['kleur_volgende'] ?? '#000';
    $fontsize_mob = $opties['fontsize_volgende_mobile'] ?? '14';
    $fontsize_tab = $opties['fontsize_volgende_tablet'] ?? '15';
    $fontsize_desktop = $opties['fontsize_volgende_desktop'] ?? '16';
    $align_mobile = $opties['align_volgende_mobile'] ?? 'left';
    $align_tablet = $opties['align_volgende_tablet'] ?? 'left';
    $align_desktop = $opties['align_volgende_desktop'] ?? 'left';

    $border = ($opties['border_volgende'] ?? 'ja') === 'ja' ? '1px solid #ccc' : 'none';

    $custom_css = "
        .shortcode-openingstijden-volgende {
            color: {$kleur};
        }
        .shortcode-openingstijden-volgende td {
            padding-top: 4px;
            padding-bottom: 4px;
            border: {$border};
        }
        @media (max-width: 767px) {
            .shortcode-openingstijden-volgende {
                font-size: {$fontsize_mob}px;
                text-align: {$align_mobile};
            }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .shortcode-openingstijden-volgende {
                font-size: {$fontsize_tab}px;
                text-align: {$align_tablet};
            }
        }
        @media (min-width: 1025px) {
            .shortcode-openingstijden-volgende {
                font-size: {$fontsize_desktop}px;
                text-align: {$align_desktop};
            }
        }
    ";
    wp_register_style('openingstijden-dynamic', false);
    wp_enqueue_style('openingstijden-dynamic');
    wp_add_inline_style('openingstijden-dynamic', $custom_css);
}
add_action('wp_enqueue_scripts', 'openingstijden_enqueue_frontend_styles');
