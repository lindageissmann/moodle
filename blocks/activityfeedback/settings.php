<?php
/**
 * Admin Settings, global configuration for block type "activityfeedback".
 * https://docs.moodle.org/dev/Blocks#The_Effects_of_Globalization
 * To generate a global configuration form, we simply create admin setting objects and add them to an array.
 * This array is then stepped over to create the settings form, and the inputted data is automatically saved to the database.
 * https://docs.moodle.org/dev/Admin_settings
 * Preferred method of storing your block's configuration data is to prefix each config variable name
 * in your settings.php file with your block's name, followed by a slash and the name of the configuration variable.
 * Your config data will still be available via a get_config() call, and name collision will be impossible between plugins.
 *
 * Update version.php to see changes.
 *
 * @package   block_activityfeedback
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * 7 feedback options can be configured.
 * Eeach option has
 * - a checkbox (if feedback option is enabled/activated or disabled)
 * - a textfield for a description of the feedback option
 * - an upload field for the images (if not filled, the default images are taken for option 1 - 4 (saved in plugin directory))
 */
if ($hassiteconfig) { // as a quick way to check for the moodle/site:config permission
    if ($ADMIN->fulltree) { // improves performance when the caller does not need the actual settings

        // option 1 (default active)
        $settings->add(new admin_setting_configcheckbox(
                // stored in config_plugins table
                'block_activityfeedback/opt1activeadmin',
                // label, is put in front of setting on the admin screen
                // 4th param is for better performance, see https://docs.moodle.org/dev/String_API#lang_string_class
                get_string('opt1activeadmin', 'block_activityfeedback', null, true),
                // description, short bit of text displayed underneath the setting to explain it further
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                // default value for this setting
                '1'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt1nameadmin',
                get_string('opt1nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                // default is like visible name
                get_string('opt1namedefaultadmin', 'block_activityfeedback', null, true)
        ));

        /**
         * For uploading of one file into file storage, the file name is stored in config table.
         * '_pluginfile' callback function is implemented in lib.php.
         * https://docs.moodle.org/dev/File_API
         */
        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt1pictureadmin',
                get_string('opt1pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                1,
                // itemid is needed if the file area in question is not already uniquely identified
                // by the contextid + component + filearea (https://moodle.org/mod/forum/discuss.php?d=233083#p1012585)
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
                // default image is not supported, admin_setting_configstoredfile has no 'default' parameter
        ));

        // doing the same for the other 6 possible feedback options
        // by default the first 4 options are active
        // -----------------------------------------------------------
        // option 2 (default active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt2activeadmin',
                get_string('opt2activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '1'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt2nameadmin',
                get_string('opt2nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt2namedefaultadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt2pictureadmin',
                get_string('opt2pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                2,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));

        // -----------------------------------------------------------
        // option 3 (default active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt3activeadmin',
                get_string('opt3activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '1'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt3nameadmin',
                get_string('opt3nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt3namedefaultadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt3pictureadmin',
                get_string('opt3pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                3,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));

        // -----------------------------------------------------------
        // option 4 (default active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt4activeadmin',
                get_string('opt4activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '1'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt4nameadmin',
                get_string('opt4nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt4namedefaultadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt4pictureadmin',
                get_string('opt4pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                4,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));

        // -----------------------------------------------------------
        // option 5 (default not active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt5activeadmin',
                get_string('opt5activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '0'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt5nameadmin',
                get_string('opt5nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt5nameadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt5pictureadmin',
                get_string('opt5pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                5,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));

        // -----------------------------------------------------------
        // option 6 (default not active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt6activeadmin',
                get_string('opt6activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '0'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt6nameadmin',
                get_string('opt6nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt6nameadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt6pictureadmin',
                get_string('opt6pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                6,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));

        // -----------------------------------------------------------
        // option 7 (default not active)
        $settings->add(new admin_setting_configcheckbox(
                'block_activityfeedback/opt7activeadmin',
                get_string('opt7activeadmin', 'block_activityfeedback', null, true),
                get_string('optactiveadmin_desc', 'block_activityfeedback', null, true),
                '0'
        ));

        $settings->add(new admin_setting_configtext(
                'block_activityfeedback/opt7nameadmin',
                get_string('opt7nameadmin', 'block_activityfeedback', null, true),
                get_string('optnameadmin_desc', 'block_activityfeedback', null, true),
                get_string('opt7nameadmin', 'block_activityfeedback', null, true)
        ));

        $settings->add(new admin_setting_configstoredfile(
                'block_activityfeedback/opt7pictureadmin',
                get_string('opt7pictureadmin', 'block_activityfeedback', null, true),
                get_string('optpictureadmin_desc', 'block_activityfeedback', null, true),
                'activityfeedback_pix_admin',
                7,
                ['maxfiles' => 1, 'accepted_types' => array('.png')]
        ));
    }
}
