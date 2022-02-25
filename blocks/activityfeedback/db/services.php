<?php
/**
 * Exposing plugin's functions as Web service functions
 * This file contains one or two arrays. The first array declares your web service functions.
 * Each of these declarations reference a function in your module (usually an external function).
 * The second, optional array declares the pre-built services.
 * https://docs.moodle.org/dev/Web_services_API
 * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 *
 * Update version.php to see changes.
 *
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Declaration of web service functions
 */
$functions = array(
        'block_activityfeedback_get_feedback_data' => array(
                'classname'   => 'get_feedback_data',
                'methodname'  => 'execute',
                'classpath'   => 'blocks/activityfeedback/classes/external/get_feedback_data.php',
                'description' => 'Get existing feedbacks for course and current user for displaying',
                'type'        => 'read',
                'ajax'        => true,
                'capabilities'  => '',
        ),
        'block_activityfeedback_get_feedback_activity' => array(
                'classname'   => 'get_feedback_activity',
                'methodname'  => 'execute',
                'classpath'   => 'blocks/activityfeedback/classes/external/get_feedback_activity.php',
                'description' => 'Get feedback for certain activity for current user for faster display after update',
                'type'        => 'read',
                'ajax'        => true,
                'capabilities'  => '',
        ),
        'block_activityfeedback_set_feedback_data' => array( // web service function name, callable from client
                'classname'   => 'set_feedback_data', // class containing the external function
                // (or with path, then you don't need classpath, but namespace and usings in class, see e.g. block 'accessreview')
                'methodname'  => 'execute', // external function named, implemented in the above class
                // file containing the class/external function (optional, dependent of namespace of class)
                'classpath'   => 'blocks/activityfeedback/classes/external/set_feedback_data.php',
                // will be displayed in the generated API documentation
                'description' => 'Update database table block_activityfeedback if feedback option was selected.',
                'type'        => 'write', // database rights of the web service function (read, write)
                'ajax'        => true, // if service is available to 'internal' ajax calls
                // 'services' => array(xxx) // optional, list of built-in services where the function will be included
                'capabilities'  => '',  // list of capabilities required by the function (those in a require_capability() call)
        ),
        'block_activityfeedback_get_pix_data' => array(
                'classname'   => 'get_pix_data',
                'methodname'  => 'execute',
                'classpath'   => 'blocks/activityfeedback/classes/external/get_pix_data.php',
                'description' => 'Get data about selectable feedback options/pictures for displaying',
                'type'        => 'read',
                'ajax'        => true,
                'capabilities'  => '',
        ),
);

/**
 * Declaration of the pre-built services.
 * During the plugin installation/upgrade, Moodle installs these services as pre-build services.
 * A pre-build service is not editable by administrator.
 */
$services = array(
        'Activity feedback service' => array( // name of the web service
                'functions' => array(
                        'block_activityfeedback_get_feedback_data',
                        'block_activityfeedback_get_feedback_activity',
                        'block_activityfeedback_set_feedback_data',
                        'block_activityfeedback_get_pix_data'
                ), // web service functions of this service
                // if set, the web service user need this capability to access any function of this service
                'requiredcapability' => 'moodle/block:view',
                // if 1, the administrator must manually select which user can use this service (> Web services > Manage services > Authorised users)
                'restrictedusers' => 0,
                // if 0, then token linked to this service won't work, if enabled, the service can be reachable on a default installation
                'enabled' => 1,
                // short name used to refer to this service from elsewhere including when fetching a token,
                // optional – but needed if restrictedusers is set so as to allow logins
                'shortname' => 'activityfeedbackservice',
                'downloadfiles' => 0, // don't allow file downloads
                'uploadfiles' => 0 // don't allow file uploads
        )
);
