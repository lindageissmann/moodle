<?php
//https://docs.moodle.org/dev/Blocks#The_Effects_of_Globalization
// "global configuration" applies only to a specific block type (all instances of that block type are affected,
// however). Implementing such configuration for our block is quite similar to implementing
// the instance configuration

// allg: https://docs.moodle.org/dev/Blocks#The_Effects_of_Globalization

// to generate a global configuration form, we simply create admin setting objects and add them to an array.
// This array is then stepped over to create the settings form, and the inputted data is automatically saved
// to the database. siehe https://docs.moodle.org/dev/Admin_settings#Individual_settings

// https://github.com/moodle/moodle/blob/master/mod/lesson/settings.php

// globale Konfig., das heisst jene, welche via Admin einstellbar

// noch prüfen: Berechtigung, andere ifs
// https://docs.moodle.org/dev/Admin_settings#Settings_file_example

// evtl. als Begründung, warum nicht selbst ganz neu geschrieben
// https://docs.moodle.org/dev/Admin_settings#When_to_use_an_admin_settings_vs_admin_externalpages

//todolig: als bei mir neue Opt dazu, war gespeichertes Bild 1 bei allen angezeigt als Default, unklar
//version.php updaten

defined('MOODLE_INTERNAL') || die();

// slash is important here. It indicates that this setting is owned by the mod_lesson plugin and should be stored in the config_plugins table
// Plugin-scope settings can be obtained only via get_config() call.
// preferred method of storing your block's configuration data is to prefix each config variable name
// in your settings.php file with your block's name, followed by a slash ( / ), and the name of the
// configuration variable,
// Your config data will still be available via a get_config() call, and name collision will be
// impossible between plugins.

$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt1activeadmin', // stored in config_plugins table
        get_string('opt1activeadmin', 'block_activityfeedback'), // label, is put in front of setting on the admin screen
        get_string('opt1activeadmin_desc', 'block_activityfeedback'), // description, short bit of text displayed underneath the setting to explain it further
        '1' // default value for this setting
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt1nameadmin',
        get_string('opt1nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt1namedefaultadmin', 'block_activityfeedback') // default is like visible name
));

// https://docs.moodle.org/dev/Using_the_File_API_in_Moodle_forms
// https://docs.moodle.org/dev/File_API#Table:_files
/* Class used for uploading of one file into file storage,
 * the file name is stored in config table.
 *
 * Please note you need to implement your own '_pluginfile' callback function,
 * this setting only stores the file, it does not deal with file serving.
*/
// separate filearea für jedes Bild?
// https://moodle.org/mod/forum/discuss.php?d=227249
// now answer: https://moodle.org/mod/forum/discuss.php?d=260950
// todolig: optional // file area oder id anpassen, damit eindeutig
////default bei active fkt. evtl. nicht, evtl. true/false nehmen / bei 0 ist auch active

////hidden/dependenton, o.ä., hier wird erst nach speichern aktualisiert
//if(get_config('block_activityfeedback','opt1activeadmin')) {
//    $pix1 = new admin_setting_configstoredfile(
//            'block_activityfeedback/opt1pictureadmin',
//            get_string('opt1pictureadmin', 'block_activityfeedback'),
//            get_string('optpictureadmin_desc', 'block_activityfeedback'),
//            'activityfeedback_pix_admin',
//            1, // not needed because other parameters are enough to uniquely identify
//            // the itemid is needed if the file area in question is not already uniquely identified by the contextid + component + filearea.
//            // https://moodle.org/mod/forum/discuss.php?d=233083
//            ['maxfiles' => 1, 'accepted_types' => array('.png')]);
//    $settings->add($pix1);
//}

// vermutlich doch untersch. filearea, dafür gleiche itemid, weil
//filelib.php: file_pluginfile: kein itemid
//ruf eigene lib.php auf
//warum landet man schon wieder im pluginfile.php?
//siehe auch https://docs.moodle.org/dev/File_API#Read_file
//ich meine URL stimmt, aber Aufruf dann nicht, Aufruf automatisch von php, wenn src gegeben
// doch itemid sollte in args sein, ich muss extrahieren in lib.php
//suche nach itemid in https://docs.moodle.org/dev/File_API
//$args extra arguments (itemid, path)
$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt1pictureadmin',
        get_string('opt1pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        1,
        // itemid is needed if the file area in question is not already uniquely identified by the contextid + component + filearea.
        // https://moodle.org/mod/forum/discuss.php?d=233083
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));
//default setting für file (img) nicht möglich / admin_setting_configstoredfile hat kien default param

// doing the same for the other 6 possible feedback options
// by default the first 4 options are active 
// -----------------------------------------------------------
// option 2 (default active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt2activeadmin',
        get_string('opt2activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '1'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt2nameadmin',
        get_string('opt2nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt2namedefaultadmin', 'block_activityfeedback')
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt2pictureadmin',
        get_string('opt2pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        2,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// -----------------------------------------------------------
// option 3 (default active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt3activeadmin',
        get_string('opt3activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '1'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt3nameadmin',
        get_string('opt3nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt3namedefaultadmin', 'block_activityfeedback')
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt3pictureadmin',
        get_string('opt3pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        3,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// -----------------------------------------------------------
// option 4 (default active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt4activeadmin',
        get_string('opt4activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '1'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt4nameadmin',
        get_string('opt4nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt4namedefaultadmin', 'block_activityfeedback')
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt4pictureadmin',
        get_string('opt4pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        4,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// -----------------------------------------------------------
// option 5 (default not active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt5activeadmin',
        get_string('opt5activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '0'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt5nameadmin',
        get_string('opt5nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt5nameadmin', 'block_activityfeedback') //default is like visible name
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt5pictureadmin',
        get_string('opt5pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        5,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// -----------------------------------------------------------
// option 6 (default not active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt6activeadmin',
        get_string('opt6activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '0'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt6nameadmin',
        get_string('opt6nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt6nameadmin', 'block_activityfeedback')
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt6pictureadmin',
        get_string('opt6pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        6,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));

// -----------------------------------------------------------
// option 7 (default not active)
$settings->add(new admin_setting_configcheckbox(
        'block_activityfeedback/opt7activeadmin',
        get_string('opt7activeadmin', 'block_activityfeedback'),
        get_string('optactiveadmin_desc', 'block_activityfeedback'),
        '0'
));

$settings->add(new admin_setting_configtext(
        'block_activityfeedback/opt7nameadmin',
        get_string('opt7nameadmin', 'block_activityfeedback'),
        get_string('optnameadmin_desc', 'block_activityfeedback'),
        get_string('opt7nameadmin', 'block_activityfeedback')
));

$settings->add(new admin_setting_configstoredfile(
        'block_activityfeedback/opt7pictureadmin',
        get_string('opt7pictureadmin', 'block_activityfeedback'),
        get_string('optpictureadmin_desc', 'block_activityfeedback'),
        'activityfeedback_pix_admin',
        7,
        ['maxfiles' => 1, 'accepted_types' => array('.png')]
));


// todolig: falls active, muss pic vorhanden sein

// If, as recommended, you saved your config variables in a custom namespace for your block,
// then you can access them via a call to get_config(): 
// $allowHTML = get_config('activityfeedback', 'Allow_HTML');
