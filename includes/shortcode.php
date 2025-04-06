<?php
function openingstijden_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur'] ?? '#000000';
    $output = "<div class='openingstijden' style='text-align: {$opties['align']}; font-size: {$opties['fontsize']}px; color: {$kleur};'>";
    $output .= "<table class='openingstijden-tabel'>";

    $dagen = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];
    foreach ($dagen as $dag) {
        $tijd = $opties[$dag] ?? 'Gesloten';
        $output .= "<tr><td><strong>" . ucfirst($dag) . "</strong></td><td>" . esc_html($tijd) . "</td></tr>";
    }

    if (!empty($opties['uitzonderingen'])) {
        $regels = explode("\n", $opties['uitzonderingen']);
        $output .= "<tr><td colspan='2' style='padding-top:10px;'><strong>Uitzonderingen</strong></td></tr>";
        foreach ($regels as $regel) {
            if (strpos($regel, ':') !== false) {
                list($datum, $tijd) = explode(':', $regel, 2);
                $output .= "<tr><td>" . esc_html(trim($datum)) . "</td><td>" . esc_html(trim($tijd)) . "</td></tr>";
            }
        }
    }

    $output .= "</table></div>";
    return $output;
}
add_shortcode('openingstijden', 'openingstijden_shortcode');

function openingstijden_uitzonderingen_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur'] ?? '#000000';

    if (empty($opties['uitzonderingen'])) {
        return "<div class='openingstijden' style='color: {$kleur};'>Geen uitzonderingen gevonden.</div>";
    }

    $output = "<div class='openingstijden' style='text-align: {$opties['align']}; font-size: {$opties['fontsize']}px; color: {$kleur};'>";
    $output .= "<table class='openingstijden-tabel'>";
    $output .= "<tr><td colspan='2'><strong>Uitzonderingen</strong></td></tr>";

    $regels = explode("\n", $opties['uitzonderingen']);
    foreach ($regels as $regel) {
        if (strpos($regel, ':') !== false) {
            list($datum, $tijd) = explode(':', $regel, 2);
            $output .= "<tr><td>" . esc_html(trim($datum)) . "</td><td>" . esc_html(trim($tijd)) . "</td></tr>";
        }
    }

    $output .= "</table></div>";
    return $output;
}
add_shortcode('openingstijden_uitzonderingen', 'openingstijden_uitzonderingen_shortcode');
