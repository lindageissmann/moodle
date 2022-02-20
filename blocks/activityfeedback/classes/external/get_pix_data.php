<?php
/**
 * External function to get data feedback options / images
 * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 * https://docs.moodle.org/dev/External_functions_API
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . '/blocks/activityfeedback/lib.php');

class get_pix_data extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            array(
                // web service function without parameters
            )
        );
    }

    /**
     * The function itself, get information about feedback options from admin settings (config)
     * or take default images/descriptions.
     * @return array array with all enabled feedback options
     */
    public static function execute() {
        global $CFG;
        $fs = get_file_storage();
        $pixdata = array();

        // there are a maximum of 7 feedback options to choose from (to activate/configure in admin settings)
        for ($num = 1; $num <= 7; $num++) {
            $optactive = get_config('block_activityfeedback', 'opt'.$num.'activeadmin');

            if ($optactive) {
                //array with data about a feedback option, read from admin settings
                $optarray = array(); //option is added if it's active, contains: key (number 1-7), name, url of picture
                $optarray['key'] = $num;
                $optarray['name'] = get_config('block_activityfeedback', 'opt'.$num.'nameadmin');

                //url
                $file = get_config('block_activityfeedback', 'opt'.$num.'pictureadmin');
                //if an image is defined in admin setting
                if (strpos($file, '.png') !== false //because sometimes file_exists() returns true even for empty string (because of cache)
                        && $fs->file_exists(1, 'block_activityfeedback', 'activityfeedback_pix_admin', $num, '/', $file)) {
                    $pixurl = block_activityfeedback_pix_url(1, 'activityfeedback_pix_admin', $num, $file);
                }
                //otherwise use the default image from the local path (only for the first 4 options)
                else if ($num < 5) {
                    $rootpath = $CFG->wwwroot;
                    $pixurl = $rootpath . "/blocks/activityfeedback/pix/option" . $num . ".png";
                }

                $optarray['url'] = $pixurl;

                // add the array with information about one option to the array with all enabled options
                $pixdata[] = $optarray;
            }
        }

        return $pixdata;
    }

    /**
     * Returns description of method result value
     * @return external_multiple_structure
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_INT, 'feedback option key (1-7)'),
                    'name' => new external_value(PARAM_TEXT, 'name of feedback option'),
                    'url' => new external_value(PARAM_TEXT, 'url of the picture for this feedback option')
                )
            )
        );
    }
}