<?php
// This is where you define what capabilities your plugin will create. Note, if you add new
// capabilities to this file after your plugin has been installed you will need to increase
// the version number in your version.php file (discussed later) in order for them to be installed.

// https://docs.moodle.org/dev/NEWMODULE_Adding_capabilities

// offenbar ein assoziatives Array (mit Key und Values, deshalb =>, bestehend aus assoziativen Arrays)
$capabilities = array(
    //für 'My Moodle Page'?
    'block/simplehtml:myaddinstance' => array(
            'captype' => 'write',
            'contextlevel' => CONTEXT_SYSTEM,
            'archetypes' => array(
                    'user' => CAP_ALLOW
            ),

            'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/simplehtml:addinstance' => array(
            'riskbitmask' => RISK_SPAM | RISK_XSS,

            'captype' => 'write',
            'contextlevel' => CONTEXT_BLOCK,
            'archetypes' => array(
                    'editingteacher' => CAP_ALLOW,
                    'manager' => CAP_ALLOW
            ),

            'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
//The capabilities added above need descriptions for pages that allow setting of capabilities.
// These should also be added to the language file.