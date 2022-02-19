<?php
/**
 * PLUGIN external file
 *
 * @package    component
 * @category   external
 * @copyright  20XX YOURSELF
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//https://docs.moodle.org/dev/Frankenstyle#Plugin_types

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class set_feedback_data extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        // FUNCTIONNAME_parameters() always returns an external_function_parameters(). 
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
        // an external_description can be: external_value, external_single_structure or external_multiple structure
                array(
                    'cmid' => new external_value(PARAM_INT, 'id of course module', VALUE_REQUIRED),
                    'fbid' => new external_value(PARAM_INT, 'id of chosen feedback option', VALUE_REQUIRED),
                    'fbname' => new external_value(PARAM_TEXT, 'name of chosen feedback optioen', VALUE_REQUIRED)
                )
        );
    }

    /**
     * 
     * The function itself
     * 
     * Update the database after added, removed or removed a vote or all votes of course.
     * todolig: anlehnung an Quentin Fombaron <q.fombaron@outlook.fr>
     * https://docs.moodle.org/dev/Data_manipulation_API
     * https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin
     * siehe da auch exceptions
     * vermutlich keine Transaktion nötig? oder doch bei delete/insert
     *
     * https://docs.moodle.org/dev/External_functions_API
     * userid not as parameter: 
     *
     * @param int $userid User ID
     * @param int $courseid Course ID
     * @param int $cmid Course Module ID
     * @param int $vote Vote ID
     * @return string Log message
     * @throws invalid_parameter_exception
     * @throws dml_exception
     */
    public static function execute(int $cmid, int $fbid, string $fbname): bool {
        global $DB, $USER;

        $success = true;
        //todolig: fehlermeldung testen: parametertypen falsche definieren

        //Note: don't forget to validate the context and check capabilities
        //todolig siehe Bsp: https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin

        //https://docs.moodle.org/dev/Security
        //With very few exceptions, every script should call require_login or re-quire_course_login as near the start as possible.

        //https://docs.moodle.org/dev/NEWMODULE_Adding_capabilities#3._Checking_the_capability_in_your_code

        ////
        //require_login();
        //confirm_sesskey();
        //require_capability('block/point_view:view', $blockcontext);
        //
        //
        //if (!$course = $DB->get_record('course', array('id' => $courseid))) {
        //    print_error('invalidcourse', 'block_simplehtml', $courseid);
        //}
        //require_login($course);
        //
        //$context = context_module::instance($cm->id);
        //require_login($course, true, $cm);
        //require_capability('mod/feedback:edititems', $context);
        //
        ////weaker version
        //require_course_login();



        try {
            $params = self::validate_parameters(self::execute_parameters(), array(
                            'cmid' => $cmid,
                            'fbid' => $fbid,
                            'fbname' => $fbname
                    )
            );
        }
        catch(invalid_parameter_exception $e)
        {
            // vermutlich eher nicht abfangen
            $success = false;
        }

        if ($success) {
            $table = 'block_activityfeedback';

            $userid = $USER->id;//ok, getestet
            // userid from global variable, not from parameter / für security besser, aus Frontend nicht manipulierbar
            // aber dadurch keine REST-Anfrage möglich? -> doch vermutlich schon, Accesstoken ist ja auch best. User?!
            $params += array('userid' => $userid);

            //vom update:
            try {
                // search for possibly existing row (same activity and user)
                $target = $DB->get_record(
                        $table, array(
                                'cmid' => $params['cmid'],
                                'userid' => $params['userid']
                        ),'*',IGNORE_MISSING
                );
                // INSERT if not found (false is returned if not found)
                if (!$target) {
                    $dataobject = new stdClass();
                    $dataobject->cmid = $params['cmid'];
                    $dataobject->userid = $params['userid'];
                    $dataobject->fbid = $params['fbid'];
                    $dataobject->fbname = $params['fbname'];
                    $dataobject->timemodified = time();

                    $DB->insert_record($table, $dataobject, false); //todolig: u. false für bulk

                }
                // DELETE if selected feedback option is also the same
                else if ($target->fbid == $fbid) {
                    $conditions = array(
                            'cmid' => $params['cmid'],
                            'userid' => $params['userid'],
                            'fbid' => $params['fbid']
                    );
                    $DB->delete_records($table, $conditions);
                }
                // UPDATE if found but feedback option is another
                else {
                    // update feedback rate
                    $target->fbid = $fbid;
                    $target->fbname = $fbname;
                    $target->timemodified = time();
                    // overwrite the selected row
                    $DB->update_record($table, $target);
                }

            } catch (dml_exception $e) {
                $success = false;
                // todolig: return 'Exception : ' . $e->getMessage() . '\n'; // error_log o.ä.
                // getestet mit ohne exist. Tabelle: ohne debuggin, trotzdem wird dml_exception angezeigt, 11.2.22
                error_log("blablabla".$e->getMessage());
            }
        }
        return $success;
    }

    /**
     * Returns description of method result value
     * Returns a log message
     *
     * @return external_description
     */
    public static function execute_returns() {
        //return new external_value(PARAM_TEXT, 'Log message');
        return new external_value(PARAM_BOOL, 'success');
    }
}
