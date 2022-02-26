<?php
/**
 * This is the main file for the custom block "activityfeedback".
 * Needed to add and load the block.
 *
 * See tutorials:
 * https://docs.moodle.org/dev/Blocks
 * https://docs.moodle.org/dev/Blocks_Advanced
 * Functions init(), get_content(), specialization() are not executed if block is manually set to hidden in a course.
 *
 * @package   block_activityfeedback
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/blocks/activityfeedback/lib.php');

/**
 * Class block_activityfeedback
 */
class block_activityfeedback extends block_base {

    /**
     * Give values to any class member variables.
     * Is essential for all blocks. Title must be set even if you want to display no title.
     * Does not affect the behavior, that the block is hidden if it has no content.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_activityfeedback');
    }

    /**
     * Display the block content.
     * Is always needed, but we use specialization() to display the feedback options.
     * @return stdClass|stdObject|null
     */
    public function get_content() {
        // default, time-saver because it's called several times, content should only be set once
        // in our particular case, we normally have no content unless we are in editing mode
        // we create a pseudo content "new stdClass" to distinguish if we have already executed the function
        if ($this->content !== null) {
            return $this->content;
        }

        // necessary because normally we have no content,
        // but here we define an object, otherwise this part would be executed multiple times when reloading the page
        $this->content = new stdClass;

        // only set content of the block if we are in editing mode
        // (https://docs.moodle.org/dev/Blocks_Advanced#Add_editing_capability)
        $inconfigmode = $this->page->user_is_editing($this->instance->id);
        if ($inconfigmode) {
            $this->content->text = get_string('contenttext', 'block_activityfeedback');
        }
        // otherwise the block content is empty and therefore automatically not displayed

        return $this->content;
    }

    /**
     * Get the data to display our feedback options on every activity.
     * (function get_content() works also, but sooner is better)
     * It's guaranteed to be automatically called by Moodle as soon as our instance configuration is loaded and available
     * (that is, immediately after init() is called). Providing a specialization() method is the natural choice for any
     * configuration data that needs to be acted upon or made available "as soon as possible".
     * https://docs.moodle.org/dev/Blocks/Appendix_A#specialization.28.29
     * https://docs.moodle.org/dev/Blocks#The_Specialists
     * @throws dml_exception
     */
    public function specialization() {
        global $CFG, $COURSE; // $this->page instead of $PAGE in blocks

        // check if at least one feedback option is enabled in admin settings
        for ($num = 1; $num <= 7; $num++) {
            $enabled = get_config('block_activityfeedback', 'opt'.$num.'activeadmin');
            if ($enabled) {
                break;
            }
        }

        if ($enabled) {
            // don't show feedback options if we are in config/editing mode
            // advantage: feedback buttons do not disturb config mode (e.g. by overlaying edit buttons)
            // disadvantage: we see no differences in config mode when switching from invisible to visible
            $inconfigmode = $this->page->user_is_editing($this->instance->id);
            if (!$inconfigmode) {
                $args = array(
                        'rootpath' => $CFG->wwwroot,
                        'courseid' => $COURSE->id
                );
                // call to javascript module.js for displaying the correct feedback options
                $this->page->requires->js_call_amd('block_activityfeedback/module', 'init', array($args));
            }
        }
    }

    /**
     * Enables global configurability of the block (admin settings).
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
     * https://docs.moodle.org/dev/Blocks#We_Are_Legion
     * It doesn't seem to work, it's still possible to add multiple instances.
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return false;
    }

    /**
     * Block is allowed to appear only in any course format.
     * Not in dashboard, front page, activity modules, etc.
     * https://docs.moodle.org/dev/Blocks#Authorized_Personnel_Only
     * https://docs.moodle.org/dev/Blocks/Appendix_A#applicable_formats.28.29
     * @return array
     */
    public function applicable_formats(): array {
        return array(
                'all' => false,
                'course-view' => true
        );
    }

    /**
     * Delete related data (all feedbacks of current course) from table if block instance is deleted.
     * (instead of deleting the block, it's possible to only hide the block)
     * @return bool
     */
    public function instance_delete():bool {
        global $DB, $COURSE;
        $table = 'block_activityfeedback';
        $params = ['courseid' => $COURSE->id];

        try {
            $DB->delete_records_subquery($table, 'cmid', 'id',
                    'SELECT id FROM {course_modules} WHERE course = :courseid', $params);
        } catch (dml_exception $e) {
            return false;
        }
        return true;
    }

    // We have no need to overwrite the function instance_copy() to
    // copy any block-specific data when copying to a new block instance.

    // We don't need to suppress displaying the title of the block (init() is mandatory)
    // function hide_header(): bool
}
