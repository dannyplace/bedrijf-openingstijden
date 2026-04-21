<?php
/*
Plugin Name: Bedrijf Openingstijden
Description: Beheer de openingstijden van jouw bedrijf.
Version: 1.1
Author: Danny's Place
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Text Domain: bedrijf-openingstijden
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

function bedrijf_openingstijden_load() {
    include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
    include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
}
add_action('plugins_loaded', 'bedrijf_openingstijden_load');

function bedrijf_openingstijden_validate_color($color) {
    return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color) ? $color : '#000';
}

function bedrijf_openingstijden_validate_fontsize($size) {
    $size = intval($size);
    return ($size >= 8 && $size <= 72) ? $size : 16;
}

function bedrijf_openingstijden_validate_align($align) {
    return in_array($align, array('left', 'center', 'right'), true) ? $align : 'left';
}

function openingstijden_enqueue_frontend_styles() {
    $opties = get_option('openingstijden_data');

    $kleur = bedrijf_openingstijden_validate_color($opties['kleur_volgende'] ?? '#000');
    $fontsize_mob = bedrijf_openingstijden_validate_fontsize($opties['fontsize_volgende_mobile'] ?? '14');
    $fontsize_tab = bedrijf_openingstijden_validate_fontsize($opties['fontsize_volgende_tablet'] ?? '15');
    $fontsize_desktop = bedrijf_openingstijden_validate_fontsize($opties['fontsize_volgende_desktop'] ?? '16');
    $align_mobile = bedrijf_openingstijden_validate_align($opties['align_volgende_mobile'] ?? 'left');
    $align_tablet = bedrijf_openingstijden_validate_align($opties['align_volgende_tablet'] ?? 'left');
    $align_desktop = bedrijf_openingstijden_validate_align($opties['align_volgende_desktop'] ?? 'left');

    $border = ($opties['border_volgende'] ?? 'ja') === 'ja' ? '1px solid #ccc' : 'none';

    $custom_css = "
        .shortcode-openingstijden-volgende {
            color: " . $kleur . ";
        }
        .shortcode-openingstijden-volgende td {
            padding-top: 4px;
            padding-bottom: 4px;
            border: " . $border . ";
        }
        @media (max-width: 767px) {
            .shortcode-openingstijden-volgende {
                font-size: " . $fontsize_mob . "px;
                text-align: " . $align_mobile . ";
            }
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            .shortcode-openingstijden-volgende {
                font-size: " . $fontsize_tab . "px;
                text-align: " . $align_tablet . ";
            }
        }
        @media (min-width: 1025px) {
            .shortcode-openingstijden-volgende {
                font-size: " . $fontsize_desktop . "px;
                text-align: " . $align_desktop . ";
            }
        }
    ";
    wp_register_style('openingstijden-dynamic', false);
    wp_enqueue_style('openingstijden-dynamic');
    wp_add_inline_style('openingstijden-dynamic', $custom_css);
}
add_action('wp_enqueue_scripts', 'openingstijden_enqueue_frontend_styles');
