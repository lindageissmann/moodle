<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

/**
 * External function to save a feedback for a certain activity for the current user
 * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
 * https://docs.moodle.org/dev/External_functions_API
 */
class set_feedback_data extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'id of course module', VALUE_REQUIRED),
                'fbid' => new external_value(PARAM_INT, 'id of chosen feedback option', VALUE_REQUIRED),
                'fbname' => new external_value(PARAM_TEXT, 'name of chosen feedback optioen', VALUE_REQUIRED)
            )
        );
    }

    /**
     * The function itself, save the given feedback in the database.
     * https://docs.moodle.org/dev/Data_manipulation_API
     * @param int $cmid id of course_modules (activity)
     * @param int $fbid key of feedback option (1-7)
     * @param string $fbname name of feedback option
     * @return bool true if successful
     */
    public static function execute(int $cmid, int $fbid, string $fbname): bool {
        global $DB, $USER;

        $success = true;

        $params = self::validate_parameters(self::execute_parameters(),
                array(
                        'cmid' => $cmid,
                        'fbid' => $fbid,
                        'fbname' => $fbname
                )
        );

        $cmobj = $DB->get_record('course_modules', array( 'id' => $params['cmid']), '*', IGNORE_MISSING);
        $courseid = $cmobj->course;
        if (!$cmobj) {
            $success = false; // invalid course
        }

        if ($success) {
            // do NOT use require_login() in an external function
            // https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin#Context_and_Capability_checks
            // check if user is logged in and is allowed to be in the course '$courseid' and view the activity '$cmobj'
            // require_login($courseid, true, $cmobj);

            $context = context_course::instance($courseid);
            // This function does sanity and security checks on the context that was passed to the external function
            // and sets up the global $PAGE and $OUTPUT for rendering return values
            self::validate_context($context);
            // check if user has capability to view a block on the course
            require_capability('moodle/block:view', $context);
        }

        $table = 'block_activityfeedback';
        // userid from global variable, not from parameter, better set in backend for security reasons
        // to test requests directly, the access token is given to a certain user, therefore we don't need it as parameter
        $userid = $USER->id;
        $params += array('userid' => $userid);

        try {
            // search for possibly existing row (same activity and user)
            $target = $DB->get_record(
                    $table, array(
                        'cmid' => $params['cmid'],
                        'userid' => $params['userid']
                    ), '*', IGNORE_MISSING
            );
            // INSERT if not found (false is returned if not found)
            if (!$target) {
                $dataobject = new stdClass();
                $dataobject->cmid = $params['cmid'];
                $dataobject->userid = $params['userid'];
                $dataobject->fbid = $params['fbid'];
                $dataobject->fbname = $params['fbname'];
                $dataobject->timemodified = time();

                $DB->insert_record($table, $dataobject, false, false);
            } else if ($target->fbid == $fbid) {
                // DELETE if selected feedback option is also the same
                $conditions = array(
                        'cmid' => $params['cmid'],
                        'userid' => $params['userid'],
                        'fbid' => $params['fbid']
                );
                $DB->delete_records($table, $conditions);
            } else {
                // UPDATE if found but feedback option is another
                // change feedback option
                $target->fbid = $fbid;
                $target->fbname = $fbname;
                $target->timemodified = time();
                // overwrite the selected row
                $DB->update_record($table, $target);
            }
        } catch (dml_exception $e) {
            // ignore any sql errors here, the connection might be broken (found this line in core)
            $success = false;
        }
        return $success;
    }

    /**
     * Returns description of method result value
     * Returns a log message
     *
     * @return external_value
     */
    public static function execute_returns() {
        return new external_value(PARAM_BOOL, 'success');
    }
}

