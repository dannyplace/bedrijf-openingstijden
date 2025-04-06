<?php
function openingstijden_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur'] ?? '#000';
    $output = "<div class='openingstijden' style='text-align: {$opties['align']}; font-size: {$opties['fontsize']}px; color: {$kleur};'>";
    $output .= "<table class='openingstijden-tabel'>";
    $dagen = ['maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag','zondag'];
    foreach ($dagen as $dag) {
        $tijd = $opties[$dag] ?? 'Gesloten';
        $output .= "<tr><td><strong>" . ucfirst($dag) . "</strong></td><td>" . esc_html($tijd) . "</td></tr>";
    }
    if (!empty($opties['uitzonderingen'])) {
        $regels = explode("\n", $opties['uitzonderingen']);
        $output .= "<tr><td colspan='2' style='padding-top:10px;'><strong>Uitzonderingen</strong></td></tr>";
        foreach ($regels as $regel) {
            $delen = explode(':', $regel);
            if (count($delen) >= 3) {
                $omschrijving = trim($delen[1]);
                $status = trim(implode(':', array_slice($delen, 2)));
                $output .= "<tr><td colspan='2'>" . esc_html($omschrijving . ' - ' . $status) . "</td></tr>";
            }
        }
    }
    $output .= "</table></div>";
    return $output;
}
add_shortcode('openingstijden', 'openingstijden_shortcode');

function openingstijden_volgende_uitzonderingen_shortcode() {
    $opties = get_option('openingstijden_data');
    $kleur = $opties['kleur'] ?? '#000';
    if (empty($opties['uitzonderingen'])) return "<div style='color: {$kleur};'>Geen toekomstige uitzonderingen.</div>";
    $regels = explode("\n", $opties['uitzonderingen']);
    $items = [];
    foreach ($regels as $regel) {
        $delen = explode(':', $regel);
        if (count($delen) >= 3) {
            $datum = trim($delen[0]);
            if (strtotime($datum) !== false && strtotime($datum) >= strtotime(date('Y-m-d'))) {
                $items[] = [
                    'datum' => $datum,
                    'omschrijving' => trim($delen[1]),
                    'status' => trim(implode(':', array_slice($delen, 2)))
                ];
            }
        }
    }
    usort($items, function($a, $b) {
        return strtotime($a['datum']) - strtotime($b['datum']);
    });
    $output = "<div class='openingstijden' style='color: {$kleur};'><table class='openingstijden-tabel'>";
    $output .= "<tr><td><strong>Datum</strong></td><td><strong>Omschrijving</strong></td><td><strong>Status</strong></td></tr>";
    foreach (array_slice($items, 0, 2) as $item) {
        $output .= "<tr><td>{$item['datum']}</td><td>{$item['omschrijving']}</td><td>{$item['status']}</td></tr>";
    }
    $output .= "</table></div>";
    return $output;
}
add_shortcode('openingstijden_volgende_uitzonderingen', 'openingstijden_volgende_uitzonderingen_shortcode');
