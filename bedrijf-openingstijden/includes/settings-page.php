<?php
function openingstijden_settings_menu() {
    add_options_page('Openingstijden', 'Openingstijden', 'manage_options', 'openingstijden', 'openingstijden_settings_page');
    add_action('admin_init', 'openingstijden_settings_init');
}
add_action('admin_menu', 'openingstijden_settings_menu');

function openingstijden_settings_init() {
    register_setting('openingstijden', 'openingstijden_data');

    add_settings_section('open_section', 'Openingstijden per dag', null, 'openingstijden');
    $dagen = array('maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag','zondag');
    foreach ($dagen as $dag) {
        add_settings_field($dag, ucfirst($dag), function() use ($dag) {
            $opties = get_option('openingstijden_data');
            echo "<input type='text' name='openingstijden_data[$dag]' value='" . esc_attr($opties[$dag] ?? '') . "' placeholder='09:00 - 17:00'>";
        }, 'openingstijden', 'open_section');
    }

    add_settings_section('uitzonderingen', 'Uitzonderingen (datum: omschrijving: status)', null, 'openingstijden');
    add_settings_field('uitzonderingen', '', function() {
        $opties = get_option('openingstijden_data');
        echo "<textarea name='openingstijden_data[uitzonderingen]' rows='5' cols='70'>" . esc_textarea($opties['uitzonderingen'] ?? '') . "</textarea>";
    }, 'openingstijden', 'uitzonderingen');

    // Styling voor [openingstijden]
    add_settings_section('stijl', 'Stijl voor [openingstijden]', null, 'openingstijden');

    add_settings_field('kleur', 'Tekstkleur', function() {
        $opties = get_option('openingstijden_data');
        echo "<input type='color' name='openingstijden_data[kleur]' value='" . esc_attr($opties['kleur'] ?? '#000000') . "'>";
    }, 'openingstijden', 'stijl');

    foreach (array('mobile', 'tablet', 'desktop') as $dev) {
        add_settings_field("fontsize_{$dev}", "Lettergrootte $dev (px)", function() use ($dev) {
            $opties = get_option('openingstijden_data');
            echo "<input type='number' name='openingstijden_data[fontsize_{$dev}]' value='" . esc_attr($opties["fontsize_{$dev}"] ?? '16') . "'>";
        }, 'openingstijden', 'stijl');

        add_settings_field("align_{$dev}", "Uitlijning $dev", function() use ($dev) {
            $opties = get_option('openingstijden_data');
            $value = $opties["align_{$dev}"] ?? 'left';
            echo "<select name='openingstijden_data[align_{$dev}]'>
                <option value='left' " . selected($value, 'left', false) . ">Links</option>
                <option value='center' " . selected($value, 'center', false) . ">Gecentreerd</option>
                <option value='right' " . selected($value, 'right', false) . ">Rechts</option>
              </select>";
        }, 'openingstijden', 'stijl');
    }

    add_settings_field('border', 'Toon rand om tabel?', function() {
        $opties = get_option('openingstijden_data');
        echo "<select name='openingstijden_data[border]'>
            <option value='ja' " . selected($opties['border'] ?? '', 'ja', false) . ">Ja</option>
            <option value='nee' " . selected($opties['border'] ?? '', 'nee', false) . ">Nee</option>
        </select>";
    }, 'openingstijden', 'stijl');

    // Styling voor [openingstijden_volgende_uitzonderingen]
    add_settings_section('stijl_volgende', 'Stijl voor [openingstijden_volgende_uitzonderingen]', null, 'openingstijden');

    add_settings_field('kleur_volgende', 'Tekstkleur', function() {
        $opties = get_option('openingstijden_data');
        echo "<input type='color' name='openingstijden_data[kleur_volgende]' value='" . esc_attr($opties['kleur_volgende'] ?? '#000000') . "'>";
    }, 'openingstijden', 'stijl_volgende');

    foreach (array('mobile', 'tablet', 'desktop') as $dev) {
        add_settings_field("fontsize_volgende_{$dev}", "Lettergrootte $dev (px)", function() use ($dev) {
            $opties = get_option('openingstijden_data');
            echo "<input type='number' name='openingstijden_data[fontsize_volgende_{$dev}]' value='" . esc_attr($opties["fontsize_volgende_{$dev}"] ?? '16') . "'>";
        }, 'openingstijden', 'stijl_volgende');

        add_settings_field("align_volgende_{$dev}", "Uitlijning $dev", function() use ($dev) {
            $opties = get_option('openingstijden_data');
            $value = $opties["align_volgende_{$dev}"] ?? 'left';
            echo "<select name='openingstijden_data[align_volgende_{$dev}]'>
                <option value='left' " . selected($value, 'left', false) . ">Links</option>
                <option value='center' " . selected($value, 'center', false) . ">Gecentreerd</option>
                <option value='right' " . selected($value, 'right', false) . ">Rechts</option>
              </select>";
        }, 'openingstijden', 'stijl_volgende');
    }

    add_settings_field('border_volgende', 'Toon rand om tabel?', function() {
        $opties = get_option('openingstijden_data');
        echo "<select name='openingstijden_data[border_volgende]'>
            <option value='ja' " . selected($opties['border_volgende'] ?? '', 'ja', false) . ">Ja</option>
            <option value='nee' " . selected($opties['border_volgende'] ?? '', 'nee', false) . ">Nee</option>
        </select>";
    }, 'openingstijden', 'stijl_volgende');
}

function openingstijden_settings_page() {
    echo "<div class='wrap'><h1>Openingstijden Instellingen</h1><form method='post' action='options.php'>";
    settings_fields('openingstijden');
    do_settings_sections('openingstijden');
    submit_button();
    echo '<hr><h2>📌 Beschikbare shortcodes</h2>';
    echo '<p><code>[openingstijden]</code> – Toont de volledige openingstijden en uitzonderingen.</p>';
    echo '<p><code>[openingstijden_volgende_uitzonderingen]</code> – Toont alleen de eerstvolgende 2 uitzonderingen vanaf vandaag.</p>';
    echo '<p><em>Gebruik het formaat: datum: omschrijving: status</em></p>';
    echo "</form></div>";
}
