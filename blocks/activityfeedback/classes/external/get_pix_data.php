<?php
/**
 * PLUGIN external file
 *
 * @package    component
 * @category   external
 * @copyright  20XX YOURSELF
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
// get data about pictures/ options for feedback

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
     * The function itself
     * https://docs.moodle.org/dev/Data_manipulation_API
     * 
     * @return string welcome message
     */
    public static function execute() {
        global $DB, $USER, $CONTEXT, $CFG;
        
        $contextid = $CONTEXT->id;//todolig unklar

        $fs = get_file_storage();
        
        $pixdata = array();
        
        //$this->title = get_string('opt'.$num.'nameadmin', 'block_activityfeedback');
        
        // there are a maximum of 7 feedback options to choose from (to activate/configure in admin settings)
        for ($num = 1; $num <= 7; $num++) {
            
            $optactive = get_config('block_activityfeedback', 'opt'.$num.'activeadmin');
            if ($optactive) {
                /**
                 * array with data about a feedback option
                 * option is added if it's active, with key (number 1-7), name, url of picture
                 * read from admin settings
                 */
                $optarray = array();
                
                $optarray['key'] = $num;
                $optname = get_config('block_activityfeedback', 'opt'.$num.'nameadmin');
                $optarray['name'] = $optname;
                $file = get_config('block_activityfeedback', 'opt'.$num.'pictureadmin');
                // if an image is defined in admin setting
                if (strpos($file, '.png') !== false // check is needed because file_exists() returns true for empty string but is not found really
                        && $fs->file_exists(1, 'block_activityfeedback', 'activityfeedback_pix_admin', $num, '/', $file)) {
                    $pixurl = block_activityfeedback_pix_url(1, 'activityfeedback_pix_admin', $num, $file);
                    // dann auch lib.php benutzt
                    
                    ////todolig, unklar
                    ////You normally use an API function to generate these URL automatically, most often the file_rewrite_pluginfile_urls function. 
                    //$bla2 = file_rewrite_pluginfile_urls($pixurl, 'pluginfile.php',
                    //        1, 'block_activityfeedback', 'activityfeedback_pix_admin', 0);
                }
                // otherwise use the default image from the local path
                else {
                    $rootpath = $CFG->wwwroot;
                    $pixurl = $rootpath . "/blocks/activityfeedback/pix/option" . $num . ".png";
                }
                $optarray['url'] = $pixurl;
                // add array with information about the options to the array with all options
                $pixdata[] = $optarray;
                // nur dazufügen falls URL vorhanden
                // todolig: evtl. sonst default aus filesystem
                
            }
        }

        //Note: don't forget to validate the context and check capabilities

        return $pixdata;
    }

    /**
     * Returns description of method result value
     * @return external_description
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