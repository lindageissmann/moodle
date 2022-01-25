<?php
//https://docs.moodle.org/dev/Blocks#The_Effects_of_Globalization
// "global configuration" applies only to a specific block type (all instances of that block type are affected,
// however). Implementing such configuration for our block is quite similar to implementing
// the instance configuration

// to generate a global configuration form, we simply create admin setting objects and add them to an array.
// This array is then stepped over to create the settings form, and the inputted data is automatically saved
// to the database. siehe https://docs.moodle.org/dev/Admin_settings#Individual_settings

// https://github.com/moodle/moodle/blob/master/mod/lesson/settings.php

// globale Konfig., das heisst jene, welche via Admin einstellbar

defined('MOODLE_INTERNAL') || die();

//todolig: unklar, wofür
// slash is important here. It indicates that this setting is owned by the mod_lesson plugin and should be stored in the config_plugins table
// Plugin-scope settings can be obtained only via get_config() call.
$settings->add(new admin_setting_configcheckbox(
        // preferred method of storing your block's configuration data is to prefix each config variable name
        // in your settings.php file with your block's name, followed by a slash ( / ), and the name of the
        // configuration variable,
        // Your config data will still be available via a get_config() call, and name collision will be
        // impossible between plugins.
        'block_activityfeedback/opt1isactiveadmin', //Präfix wichtig für Speicherort in DB, am besten / stored in config_plugins table mit block name
        get_string('opt1isactiveadmin', 'block_activityfeedback'), // label, is put in front of setting on the admin screen
        get_string('opt1isactiveadmin_desc', 'block_activityfeedback'), // description, short bit of text displayed underneath the setting to explain it further
        '1' // default value for this setting, todolig: 1 oder true?
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt1nameadmin',
        get_string('opt1nameadmin', 'block_activityfeedback'),
        get_string('opt1nameadmin_desc', 'block_activityfeedback'),
        get_string('opt1nameadmin', 'block_activityfeedback')
));

// https://docs.moodle.org/dev/Using_the_File_API_in_Moodle_forms
// https://docs.moodle.org/dev/File_API#Table:_files

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt1pictureadmin',
        get_string('opt1pictureadmin', 'block_activityfeedback'),
        get_string('opt1pictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pics_admin',
        1,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// todolig: falls active, muss pic vorhanden sein

// If, as recommended, you saved your config variables in a custom namespace for your block,
// then you can access them via a call to get_config(): 
// $allowHTML = get_config('activityfeedback', 'Allow_HTML');
