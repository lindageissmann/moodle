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
                    'func' => new external_value(PARAM_TEXT, 'sql command', VALUE_REQUIRED),
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
     * @param string $func Function name of database update
     * @param int $userid User ID
     * @param int $courseid Course ID
     * @param int $cmid Course Module ID
     * @param int $vote Vote ID
     * @return string Log message
     * @throws invalid_parameter_exception
     * @throws dml_exception
     */
    public static function execute(string $func, int $cmid, int $fbid, string $fbname): bool {
        global $DB, $USER;

        $success = true;
        //todolig: fehlermeldung testen: parametertypen falsche definieren

        //Note: don't forget to validate the context and check capabilities
        //todolig siehe Bsp: https://docs.moodle.org/dev/Adding_a_web_service_to_a_plugin

        try {
            $params = self::validate_parameters(self::execute_parameters(), array(
                            'func' => $func,
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

            switch ($params['func']) {

                // insert row into block_activityfeedback table
                case 'insert':
                    $dataobject = new stdClass();
                    $dataobject->cmid = $params['cmid'];
                    $dataobject->userid = $params['userid'];
                    $dataobject->fbid = $params['fbid'];
                    $dataobject->fbname = $params['fbname'];

                    try {
                        $DB->insert_record($table, $dataobject, false); //todolig: u. false für bulk
                    } catch (dml_exception $e) {
                        $success = false;
                    }

                    break;

                // delete row from block_activityfeedback table
                case 'delete':
                    $conditions = array(
                            'cmid' => $params['cmid'],
                            'userid' => $params['userid'],
                            'fbid' => $params['fbid'],
                            'fbname' => $params['fbname']
                    );
                    try {
                        $DB->delete_records($table, $conditions);
                    } catch (dml_exception $e) {
                        $success = false;
                    }

                    break;

                // update row of block_activityfeedback table
                case 'update':
                    try {
                        // search for the correct row because 'update_record' needs row id
                        $target = $DB->get_record(
                                $table, array(
                                        'cmid' => $params['cmid'],
                                        'userid' => $params['userid']
                                )
                        );

                        // update feedback rate
                        $target->fbid = $fbid;
                        $target->fbname = $fbname;

                        // overwrite the selected row
                        $DB->update_record($table, $target);

                    } catch (dml_exception $e) {
                        $success = false;
                        // todolig: return 'Exception : ' . $e->getMessage() . '\n'; // error_log o.ä.
                        error_log("blablabla".$e->getMessage());
                    }
                    break;
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
