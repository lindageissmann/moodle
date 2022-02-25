<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

/**
 * External function to get the feedback for a certain activity for the current user
 * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 * https://docs.moodle.org/dev/External_functions_API
 */
class get_feedback_activity extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'id of course_module', VALUE_REQUIRED)
            )
        );
    }

    /**
     * The function itself, get feedback from db table 'block_activityfeedback' for given activity and current user
     * @param $cmid id of certain activity (id from table course_modules)
     * @return array array of the related feedback with id, cmid, userid, fbid
     */
    public static function execute($cmid) {
        global $DB, $USER;

        // parameter validation
        $params = self::validate_parameters(self::execute_parameters(),
                    array(
                        'cmid' => $cmid
                    )
        );

        $userid = $USER->id;
        $paramsql = array('userid' => $userid, 'cmid' => $params['cmid']);

        $sql = 'SELECT id,cmid,userid,fbid FROM {block_activityfeedback}
               WHERE userid = :userid
               AND cmid = :cmid';

        $result = array();

        try {
            $result = $DB->get_records_sql($sql, $paramsql);
        } catch (dml_exception $e) {
            // ignore any sql errors here, the connection might be broken (found this line in core)
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
