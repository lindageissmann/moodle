<?php
// nicht nötig
// "global configuration" and applies only to a specific block type (all instances of that block type are affected,
// however). Implementing such configuration for our block is quite similar to implementing
// the instance configuration

// to generate a global configuration form, we simply create admin setting objects and add them to an array.
// This array is then stepped over to create the settings form, and the inputted data is automatically saved
// to the database. siehe https://docs.moodle.org/dev/Admin_settings#Individual_settings

$settings->add(new admin_setting_heading(
        'headerconfig',
        get_string('headerconfig', 'block_simplehtml'),
        get_string('descconfig', 'block_simplehtml')
));

$settings->add(new admin_setting_configcheckbox(
        // preferred method of storing your block's configuration data is to prefix each config variable name
        // in your settings.php file with your block's name, followed by a slash ( / ), and the name of the
        // configuration variable,
        // Your config data will still be available via a get_config() call, and name collision will be
        // impossible between plugins.
        'simplehtml/Allow_HTML', //Präfix wichtig für Speicherort in DB, am besten 
        get_string('labelallowhtml', 'block_simplehtml'), // label, primary text
        get_string('descallowhtml', 'block_simplehtml'), // description
        '0'
));

// If, as recommended, you saved your config variables in a custom namespace for your block,
// then you can access them via a call to get_config(): 
// $allowHTML = get_config('simplehtml', 'Allow_HTML');
