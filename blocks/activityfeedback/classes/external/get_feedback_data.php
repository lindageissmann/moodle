<?php
/**
 * PLUGIN external file
 *
 * @package    component
 * @category   external
 * @copyright  20XX YOURSELF
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class get_feedback_data extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        // FUNCTIONNAME_parameters() always return an external_function_parameters(). 
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
        // a external_description can be: external_value, external_single_structure or external_multiple structure
                //array('PARAM1' => new external_value(PARAM_TYPE, 'human description of PARAM1'))
                array(
                        'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_REQUIRED)
                )                
        );
    }

    /**
     * The function itself
     * https://docs.moodle.org/dev/Data_manipulation_API
     * 
     * @return string welcome message
     */
    public static function execute($courseid) {
        global $DB, $USER;
        
        //Parameters validation
        $params = self::validate_parameters(self::execute_parameters(),
                    array(
                        'courseid' => $courseid
                    )
            );

        $userid = $USER->id;

        $table = 'block_activityfeedback';
        
        $sql = 'SELECT id,cmid,userid,fbid FROM {block_activityfeedback}
                   WHERE userid = :userid
                   AND cmid in (SELECT id FROM {course_modules} WHERE course = :courseid)';//todolig mit id fkt. nicht mehr, mit module schon
        
        $paramsql = array('userid' => $userid, 'courseid' => $params['courseid']);

        $result = $DB->get_records_sql($sql, $paramsql);

        /* Parameters for the Javascript */
        $pointviews = (!empty($result)) ? array_values($result) : array();
        
        // vermutlich od noch versuchen, todolig
        // use recordsets if number of records is high, but returned iterator is not usable on client side

        //$target = $DB->get_record(
        //        $table, 
        //        array('cmid' => $params['cmid'], 'userid' => $params['userid']),
        //        'cmid,fbid',
        //        IGNORE_MULTIPLE
        //);

        //Note: don't forget to validate the context and check capabilities

        return $result;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'activityfeedback_id'),
                    'cmid' => new external_value(PARAM_INT, 'course_module_id'),
                    'userid' => new external_value(PARAM_INT, 'userid'),
                    'fbid' => new external_value(PARAM_INT, 'feedback option id')
                )
            )
        );
    }
}