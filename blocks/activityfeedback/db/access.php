<?php
// This is where you define what capabilities your plugin will create. Note, if you add new
// capabilities to this file after your plugin has been installed you will need to increase
// the version number in your version.php file (discussed later) in order for them to be installed.

// defines, who is allowed to create/edit this block

// https://docs.moodle.org/dev/NEWMODULE_Adding_capabilities

// https://github.com/moodle/moodle/blob/master/lib/db/access.php
// * The system has four possible values for a capability:
// * CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).

// offenbar ein assoziatives Array (mit Key und Values, deshalb =>, bestehend aus assoziativen Arrays)
$capabilities = array(
	//für 'My Moodle Page'?
	// für Sicherheits: security entitise
	// siehe für Berechtigungen: Site administration / Users / Permissions / Capability overview
	// archetypes: Sicherheitsrollen, CAP_ALLOW dass erlaubt für diese Rolle
    
    // dashboard site nicht aktivieren (fkt. aber nicht, trotzdem zur Auswahl? evt. zuerst deinstallieren
    // aber korrekt nicht in DB tab mdl_capabilities)
    // Dashboard ist unter /moodle/my, wie bei FFHS
    // Site home habe ich noch nie gesehen, aber ist erreichbar, unter: https://moodle.ffhs.ch/?redirect=0
    
    // if not used on 'My moodle page'
    // then the myaddinstance capability does not need to be given to any user,
    // but it must still be defined here otherwise you will get errors on certain pages
    // egal, was ich mache (immer version.php anpass u. upgrade), bleibt möglich auf Dashboard hinzuzufügen
    // (aber dafür auf Site Home nicht mehr automat. übernommen? aber add block immer noch möglich)
    'block/activityfeedback:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM, 
        'archetypes' => array(
            'user' => CAP_PROHIBIT
        ),
        //'archetypes' => array(), //means any
        //'archetypes' => array(
        //    'user' => CAP_ALLOW
        //),

		//Sicherheitseinstellungen von anderen Einstellungen kopieren, hier von my:manageblocks
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
	
	'block/activityfeedback:addinstance' => array(
		//Risiko andere User zu spamen mit unnötigen Meldungen, XSS mit unvalidiertem HTML
		// Users / Permissions / Define roles
		// bei Anpassungen version.php anpassen
        'riskbitmask' => RISK_SPAM | RISK_XSS, //RISK_PERSONAL, RISK_DATALOSS

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    
    //configview
    
    //CONTEXT_COURSE: für aktivität
);
//The capabilities added above need descriptions for pages that allow setting of capabilities.
// These should also be added to the language file.