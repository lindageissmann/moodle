<?php
/**
 * This file is where you define what capabilities your plugin will create.
 * https://docs.moodle.org/dev/Blocks#db.2Faccess.php
 * https://docs.moodle.org/dev/NEWMODULE_Adding_capabilities
 * https://docs.moodle.org/dev/Hardening_new_Roles_system#Basic_risks
 * https://github.com/moodle/moodle/blob/master/lib/db/access.php
 * See Site administration / Users / Permissions / Capability overview.
 * See Site administration / Users / Define roles.
 *
 * Update version.php to see changes.
 *
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defines capabilities array (who is allowed to create/edit this block).
 *
 * If block is not used on 'My moodle page' then the myaddinstance capability does not need to be given to any user,
 * but it must still be defined here otherwise you will get errors on certain pages.
 * See also function applicable_formats() in block_activityfeedback.
 * Capabilities should also be added to the language file.
 * riskbitmask: list of associated risks to help admin to know issues.
 * captype: read or write.
 * contextlevel: only used to sort and group capabilities.
 * archetypes: defines for each role the default permissions.
 */
$capabilities = array(
    // add block on moodle/my not allowed
    'block/activityfeedback:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(), // nobody by default
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    // add block is allowed for certain roles
    'block/activityfeedback:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS, // default for blocks
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    // for viewing the block, the following default is used
    // moodle/block:view
    // is normally given to: teacher, non-editing teacher, student, guest, authenticated user
    // not to: manager, course creator, authenticated user on frontpage
    // the same capability is used to select a feedback option, we don't create a new capability
);
