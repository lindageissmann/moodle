<?php
/**
 * Message block caps.
 *
 * @package    block_message
 * @copyright  2021 linda.geissmann@students.ffhs.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

	// für Sicherheits: security entitise
	// siehe für Berechtigungen: Site administration / Users / Permissions / Capability overview
	// archetypes: Sicherheitsrollen, CAP_ALLOW dass erlaubt für diese Rolle
    'block/message:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

		//Sicherheitseinstellungen von anderen Einstellungen kopieren, hier von my:manageblocks
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/message:addinstance' => array(
		//Risiko andere User zu spamen mit unnötigen Meldungen, XSS mit unvalidiertem HTML
		// Users / Permissions / Define roles
		// bei Anpassungen version.php anpassen
        'riskbitmask' => RISK_SPAM | RISK_XSS, //RISK_PERSONAL, RISK_DATALOSS

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
