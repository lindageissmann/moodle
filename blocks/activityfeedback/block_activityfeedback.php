<?php
/**
 * Class block_activityfeedback
 *
 * @package   block_activityfeedback
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 * todolig
 * install.xml im verzeichni u. im db
 * 
 * //für debuggen:
    //    //print_object() is a useful moodle function which prints out data from mixed data types showing
    //    // the keys and data for arrays and objects.
    //    //print_object($fromform);
 * 
 * $CFG: configuration
 * 
 * habe ich getestet:
 * // init, get_content, specialization is not executed if block is manually set to hidden in a course
 * 
 * auf site home nicht automatisch erscheinen
 * auf dashboard nicht aktivierbar
 * wie "Acitivites" -> activity_modules
*/
//https://docs.moodle.org/dev/Roles_and_modules#Context

//todolig: am Ende Stil automatisch prüfen:
//https://docs.moodle.org/dev/Coding_style

require_once($CFG->dirroot . '/blocks/activityfeedback/lib.php');

class block_activityfeedback extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_activityfeedback');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        global $CFG, $COURSE, $PAGE;
        //default, time-saver because it's called several times, content should only be set once
        if ($this->content !== null) {
            return $this->content;
        }

        //do not display block, no content
        //$this->content = new stdClass;
        //$this->content->text = 'The content of our activityfeedback block!';
        //$this->content->footer = 'Footer here...';

        //$args = array(
        //        'rootpath' => $CFG->wwwroot,
        //        'courseid' => $COURSE->id
        //);
        //
        //$configModeIsActive = $PAGE->user_is_editing($this->instance->id);
        ////don't show feedback options if we are in config/editing mode
        ////advantage: feedback buttons do not disturb config mode (e.g. by overlaying edit buttons)
        ////disadvantage: we see no difference in config mode when switching from invisible to visible
        //if(!$configModeIsActive) {
        //    $PAGE->requires->js_call_amd('block_activityfeedback/module', 'init', array($args));
        //}

        // siehe auch: https://docs.moodle.org/dev/Blocks#Lists
        // falls Liste anzeigen statt Text und Footer
		//function returns the content object
        return $this->content;
    }
    
    // übernimmt Config (aus edit_form)
    //It's guaranteed to be automatically called by Moodle as soon as our instance configuration is loaded
    //and available (that is, immediately after init() is called)
    //Providing a specialization() method is the natural choice for any configuration data that needs to be
    //acted upon or made available "as soon as possible".
    public function specialization() {
        global $DB, $COURSE, $PAGE, $OUTPUT, $CFG, $USER;
        if (isset($this->config)) {
            //if (empty($this->config->title)) {
            //    $this->title = get_string('defaulttitle', 'block_activityfeedback');
            //} else {
            //    $this->title = $this->config->title;
            //}
    
            //if (empty($this->config->text)) {
            //    $this->config->text = get_string('defaulttext', 'block_activityfeedback');
            //} else {
            //    $this->content->text = $this->config->text;
            //}
            //
            //if (!empty($this->config->optionactive)) {
            //    $this->content->text .= $this->config->optionactive;
            //}

            //falls ich es oben bei getcontent mache, reagiert bild emoji anders
            $args = array(
                    'rootpath' => $CFG->wwwroot,
                    'courseid' => $COURSE->id
            );

            $configModeIsActive = $PAGE->user_is_editing($this->instance->id);
            //don't show feedback options if we are in config/editing mode
            //advantage: feedback buttons do not disturb config mode (e.g. by overlaying edit buttons)
            //disadvantage: we see no difference in config mode when switching from invisible to visible
            if(!$configModeIsActive) {
                $PAGE->requires->js_call_amd('block_activityfeedback/module', 'init', array($args));
            }
        }
    }

    /**
     * Enables global configurability of the block.
     * This line tells Moodle that the block has a settings.php file.
     * @return bool
     */
    public function has_config(): bool {
        return true;
    }

    /**
     * It shouldn't be possible to add multiple blocks of this type in a single course.
     * The administrator still has the option of disallowing such behavior.
     * This setting can be set separately for each block from the Administration / Configuration / Blocks page.
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return false;
    }
    
    // falls Titel nicht angezeigt werden soll
    // im init() müssen wir unabhängig davon immer! zwingend einen eindeutigen Titel definieren
    //public function hide_header() {
    //    return true;
    //}

    //block is allowed to appear only in any course format
    //not in dashboard, front page, activity modules, etc.
    //https://docs.moodle.org/dev/Blocks/Appendix_A#applicable_formats.28.29
    //https://docs.moodle.org/dev/Blocks#Authorized_Personnel_Only
    public function applicable_formats() {
        return array(
                'all' => false,
                'course-view' => true
        );
    }
}

