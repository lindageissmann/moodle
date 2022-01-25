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

class get_pix_data extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_pix_data_parameters() {
        // FUNCTIONNAME_parameters() always return an external_function_parameters(). 
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
        // a external_description can be: external_value, external_single_structure or external_multiple structure
                //array('PARAM1' => new external_value(PARAM_TYPE, 'human description of PARAM1'))
                array('courseid' => new external_value(PARAM_INT, 'id fo course'))
        );
    }

    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_pix_data($PARAM1) {

        //Parameters validation
        $params = self::validate_parameters(self::FUNCTIONNAME_parameters(),
                array('PARAM1' => $PARAM1));

        //Note: don't forget to validate the context and check capabilities

        return $returnedvalue;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_pix_data_returns() {
        return new external_value(PARAM_TYPE, 'human description of the returned value');
    }

}