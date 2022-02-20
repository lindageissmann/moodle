<?php
/**
 * External function to get all feedbacks for course and user
 * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 * https://docs.moodle.org/dev/External_functions_API
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
            array(
                'courseid' => new external_value(PARAM_INT, 'id of course', VALUE_REQUIRED)
            )
        );
    }

    /**
     * The function itself, get feedback from db table 'block_activityfeedback' for current course and user
     * @param $courseid id of the course
     * @return array array of existing feedbacks with id, cmid, userid, fbid
     */
    public static function execute($courseid) {
        global $DB, $USER;
        
        //parameter validation
        $params = self::validate_parameters(self::execute_parameters(),
                    array(
                        'courseid' => $courseid
                    )
        );

        $userid = $USER->id;
        $paramsql = array('userid' => $userid, 'courseid' => $params['courseid']);

        $sql = 'SELECT id,cmid,userid,fbid FROM {block_activityfeedback}
               WHERE userid = :userid
               AND cmid in (SELECT id FROM {course_modules} WHERE course = :courseid)';

        $result = array();

        try {
            $result = $DB->get_records_sql($sql, $paramsql);
        } catch (dml_exception $e) {
            //ignore any sql errors here, the connection might be broken (found this line in core)
        }

        return $result;
    }

    /**
     * Returns description of method result value
     * @return external_multiple_structure
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