<?php
function openingstijden_settings_menu() {
    add_options_page('Openingstijden', 'Openingstijden', 'manage_options', 'openingstijden', 'openingstijden_settings_page');
    add_action('admin_init', 'openingstijden_settings_init');
}
add_action('admin_menu', 'openingstijden_settings_menu');

function openingstijden_settings_init() {
    register_setting('openingstijden', 'openingstijden_data');

    add_settings_section('openingstijden_section', 'Standaard Openingstijden', null, 'openingstijden');

    $dagen = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];
    foreach ($dagen as $dag) {
        add_settings_field($dag, ucfirst($dag), function() use ($dag) {
            $opties = get_option('openingstijden_data');
            echo "<input type='text' name='openingstijden_data[$dag]' value='" . esc_attr($opties[$dag] ?? '') . "' placeholder='09:00 - 17:00'>";
        }, 'openingstijden', 'openingstijden_section');
    }

    add_settings_section('uitzonderingen_section', 'Uitzonderingsdagen', null, 'openingstijden');

    add_settings_field('uitzonderingen', 'Uitzonderingen (datum: omschrijving: status)', function() {
        $opties = get_option('openingstijden_data');
        echo "<textarea name='openingstijden_data[uitzonderingen]' rows='6' cols='70'>" . esc_textarea($opties['uitzonderingen'] ?? '') . "</textarea>";
        echo "<p>Bijv: 2025-12-25: Eerste Kerstdag: Gesloten</p>";
    }, 'openingstijden', 'uitzonderingen_section');

    add_settings_section('stijl_section', 'Stijl Instellingen', null, 'openingstijden');

    add_settings_field('align', 'Uitlijning', function() {
        $opties = get_option('openingstijden_data');
        echo "<select name='openingstijden_data[align]'>
                <option value='left' ".selected($opties['align'] ?? '', 'left', false).">Links</option>
                <option value='center' ".selected($opties['align'] ?? '', 'center', false).">Gecentreerd</option>
                <option value='right' ".selected($opties['align'] ?? '', 'right', false).">Rechts</option>
              </select>";
    }, 'openingstijden', 'stijl_section');

    add_settings_field('fontsize', 'Lettergrootte (px)', function() {
        $opties = get_option('openingstijden_data');
        echo "<input type='number' name='openingstijden_data[fontsize]' value='" . esc_attr($opties['fontsize'] ?? '16') . "'>";
    }, 'openingstijden', 'stijl_section');

    add_settings_field('kleur', 'Tekstkleur', function() {
        $opties = get_option('openingstijden_data');
        echo "<input type='color' name='openingstijden_data[kleur]' value='" . esc_attr($opties['kleur'] ?? '#000000') . "'>";
    }, 'openingstijden', 'stijl_section');
}

function openingstijden_settings_page() {
    echo "<div class='wrap'><h1>Openingstijden Instellingen</h1><form method='post' action='options.php'>";
    settings_fields('openingstijden');
    do_settings_sections('openingstijden');
    submit_button();
    echo "</form></div>";
}
