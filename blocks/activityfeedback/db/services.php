<?php
//https://docs.moodle.org/dev/Web_services_API
//https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
//Exposing plugin's functions as Web service functions
//This file contains one or two arrays. The first array declares your web service functions.
//Each of these declarations reference a function in your module (usually an external function).

// update version.php to see changes

$functions = array(
        'local_activityfeedback_get_pix_data' => array( // local_PLUGINNAME_FUNCTIONNAME is the name of the web service function that the client will call.                                                                                
                'classname'   => 'get_pix_data', // create this class in componentdir/classes/external, class containing the external function OR namespaced class in classes/external/XXXX.php
                'methodname'  => 'get_pix_data', // external function name, implement this function into the above class
                //'classpath'   => 'local/myplugin/externallib.php',  //file containing the class/external function - not required if using namespaced auto-loading classes.
                'description' => 'get picture data', //human readable description of the web service function, will be displayed in the generated API documentation
                'type'        => 'read', // database rights of the web service function (read, write), the value is 'write' if your function does any database change, otherwise it is 'read'.
                'ajax'        => true, // true/false if you allow this web service function to be callable via ajax 
                'capabilities'  => '',  // List the capabilities required by the function (those in a require_capability() call) (missing capabilities are displayed for authorised users and also for manually created tokens in the web interface, this is just informative).
                'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'local_mobile')    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included. Services created manually via the Moodle interface are not supported.
        )
);

// OPTIONAL
// During the plugin installation/upgrade, Moodle installs these services as pre-build services. 
// A pre-build service is not editable by administrator.
$services = array(
        'Activity feedback service' => array(      // the name of the web service
                'functions' => array ('local_activityfeedback_get_pix_data'), // web service functions of this service
                'requiredcapability' => '',   // if set, the web service user need this capability to access 
                                              // any function of this service. For example: 'some/capability:specified'
                'restrictedusers' => 0, // if 1, the administrator must manually select which user can use this service. (> Web services > Manage services > Authorised users)
                'enabled'=>1, // if 0, then token linked to this service won't work, if enabled, the service can be reachable on a default installation
                'shortname'=>'activityfeedback', //the short name used to refer to this service from elsewhere including when fetching a token, optional – but needed if restrictedusers is set so as to allow logins
                'downloadfiles' => 0,    // allow file downloads.
                'uploadfiles'  => 0      // allow file uploads.
        )
);





