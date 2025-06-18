<?php
function openingstijden_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur'] ?? '#000';

    $fontsize_mob = $opties['fontsize_mobile'] ?? '14';
    $fontsize_tab = $opties['fontsize_tablet'] ?? '15';
    $fontsize_desktop = $opties['fontsize_desktop'] ?? '16';

    $align_mobile = $opties['align_mobile'] ?? 'left';
    $align_tablet = $opties['align_tablet'] ?? 'left';
    $align_desktop = $opties['align_desktop'] ?? 'left';

    $border = ($opties['border'] ?? 'ja') === 'ja';

    $output = '<style>
    @media (max-width: 767px) {
        .shortcode-openingstijden {
            font-size: ' . $fontsize_mob . 'px;
            text-align: ' . $align_mobile . ';
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .shortcode-openingstijden {
            font-size: ' . $fontsize_tab . 'px;
            text-align: ' . $align_tablet . ';
        }
    }
    @media (min-width: 1025px) {
        .shortcode-openingstijden {
            font-size: ' . $fontsize_desktop . 'px;
            text-align: ' . $align_desktop . ';
        }
    }
    </style>';

    $output .= '<div class="openingstijden shortcode-openingstijden" style="color: ' . esc_attr($kleur) . ';">';
    $output .= '<table class="openingstijden-tabel" style="border-collapse: collapse; width: 100%; max-width: 600px;">';

    $dagen = array('maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag','zondag');
    foreach ($dagen as $dag) {
        $tijd = $opties[$dag] ?? 'Gesloten';
        $output .= '<tr>';
        $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;"><strong>' . ucfirst($dag) . '</strong></td>';
        $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($tijd) . '</td>';
        $output .= '</tr>';
    }

    if (!empty($opties['uitzonderingen'])) {
        $regels = explode("\n", $opties['uitzonderingen']);
        $output .= '<tr><td colspan="2" style="padding-top:10px; border: none;"><strong>Uitzonderingen</strong></td></tr>';
        foreach ($regels as $regel) {
            $delen = explode(':', $regel);
            if (count($delen) >= 3) {
                $omschrijving = trim($delen[1]);
                $status = trim(implode(':', array_slice($delen, 2)));
                $output .= '<tr>';
                $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($omschrijving) . '</td>';
                $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($status) . '</td>';
                $output .= '</tr>';
            }
        }
    }

    $output .= '</table></div>';
    return $output;
}
add_shortcode('openingstijden', 'openingstijden_shortcode');


function openingstijden_volgende_uitzonderingen_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur_volgende'] ?? '#000';

    $fontsize_mob = $opties['fontsize_volgende_mobile'] ?? '14';
    $fontsize_tab = $opties['fontsize_volgende_tablet'] ?? '15';
    $fontsize_desktop = $opties['fontsize_volgende_desktop'] ?? '16';

    $align_mobile = $opties['align_volgende_mobile'] ?? 'left';
    $align_tablet = $opties['align_volgende_tablet'] ?? 'left';
    $align_desktop = $opties['align_volgende_desktop'] ?? 'left';

    $border = ($opties['border_volgende'] ?? 'ja') === 'ja';

    $output = '<style>
    @media (max-width: 767px) {
        .shortcode-openingstijden-volgende {
            font-size: ' . $fontsize_mob . 'px;
            text-align: ' . $align_mobile . ';
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .shortcode-openingstijden-volgende {
            font-size: ' . $fontsize_tab . 'px;
            text-align: ' . $align_tablet . ';
        }
    }
    @media (min-width: 1025px) {
        .shortcode-openingstijden-volgende {
            font-size: ' . $fontsize_desktop . 'px;
            text-align: ' . $align_desktop . ';
        }
    }
    </style>';

    $regels = explode(PHP_EOL, $opties['uitzonderingen'] ?? '');
    $items = array();

    foreach ($regels as $regel) {
        $delen = explode(':', $regel);
        if (count($delen) >= 3) {
            $datum = trim($delen[0]);
            if (strtotime($datum) !== false && strtotime($datum) >= strtotime(date('Y-m-d'))) {
                $items[] = array(
                    'datum' => $datum,
                    'omschrijving' => trim($delen[1]),
                    'status' => trim(implode(':', array_slice($delen, 2)))
                );
            }
        }
    }

    usort($items, function($a, $b) {
        return strtotime($a['datum']) - strtotime($b['datum']);
    });

    $output .= '<div class="shortcode-openingstijden-volgende" style="color: ' . esc_attr($kleur) . ';">';
    $output .= '<table style="border-collapse: collapse; width: 100%; max-width: 600px;">';

    foreach (array_slice($items, 0, 2) as $item) {
        $output .= '<tr>';
        $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($item['omschrijving']) . '</td>';
        $output .= '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($item['status']) . '</td>';
        $output .= '</tr>';
    }

    $output .= '</table></div>';
    return $output;
}
add_shortcode('openingstijden_volgende_uitzonderingen', 'openingstijden_volgende_uitzonderingen_shortcode');


function openingstijden_volgende_uitzonderingen_ajax_shortcode() {
    wp_enqueue_script('openingstijden-ajax', plugins_url('../assets/ajax-loader.js', __FILE__), array(), null, true);
    wp_localize_script('openingstijden-ajax', 'openingstijden_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    return '<style>
    .openingstijden-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #555;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
    <div id="openingstijden-volgende-ajax" class="shortcode-openingstijden-volgende" style="text-align:center;">
        <div class="openingstijden-spinner"></div>
    </div>';
    
}
add_shortcode('openingstijden_volgende_uitzonderingen_ajax', 'openingstijden_volgende_uitzonderingen_ajax_shortcode');

function openingstijden_volgende_uitzonderingen_ajax_callback() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur_volgende'] ?? '#000';
    $border = ($opties['border_volgende'] ?? 'ja') === 'ja';

    $regels = explode(PHP_EOL, $opties['uitzonderingen'] ?? '');
    $items = array();

    foreach ($regels as $regel) {
        $delen = explode(':', $regel);
        if (count($delen) >= 3) {
            $datum = trim($delen[0]);
            if (strtotime($datum) !== false && strtotime($datum) >= strtotime(date('Y-m-d'))) {
                $items[] = array(
                    'datum' => $datum,
                    'omschrijving' => trim($delen[1]),
                    'status' => trim(implode(':', array_slice($delen, 2)))
                );
            }
        }
    }

    usort($items, function($a, $b) {
        return strtotime($a['datum']) - strtotime($b['datum']);
    });

    echo '<div class="shortcode-openingstijden-volgende" style="color: ' . esc_attr($kleur) . ';">';
    echo '<table style="border-collapse: collapse; width: 100%; max-width: 600px;">';

    foreach (array_slice($items, 0, 2) as $item) {
        echo '<tr>';
        echo '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($item['omschrijving']) . '</td>';
        echo '<td style="border: ' . ($border ? '1px solid #ccc' : 'none') . '; padding-top: 4px; padding-bottom: 4px;">' . esc_html($item['status']) . '</td>';
        echo '</tr>';
    }

    echo '</table></div>';
    wp_die();
}
add_action('wp_ajax_haal_volgende_uitzonderingen_op', 'openingstijden_volgende_uitzonderingen_ajax_callback');
add_action('wp_ajax_nopriv_haal_volgende_uitzonderingen_op', 'openingstijden_volgende_uitzonderingen_ajax_callback');
