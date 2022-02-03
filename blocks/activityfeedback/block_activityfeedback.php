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
        //$this->title = get_string('opt1nameadmin', 'block_activityfeedback');
        //$num = 1;
        //$this->title = get_string('opt'.$num.'nameadmin', 'block_activityfeedback');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        //default, time-saver because it's called several times, content should only be set once
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = 'The content of our activityfeedback block!';
        $this->content->footer = 'Footer here...';
		
		/*if (empty($this->instance)) {
            return $this->content;
        }*/

        //global $COURSE;
        //// The other code.
        //$url = new moodle_url('/blocks/activityfeedback/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
        //$this->content->footer = html_writer::link($url, get_string('addpage', 'block_activityfeedback'));

        // siehe auch: https://docs.moodle.org/dev/Blocks#Lists
        // falls Liste anzeigen statt Text und Footer
		//function returns the content object
        return $this->content;
    }
    
    // übernimmt Config (aus edit_form)
    // it's guaranteed to be automatically called by Moodle as soon as our instance configuration is loaded
    // and available (that is, immediately after init() is called)
    // Providing a specialization() method is the natural choice for any configuration data that needs to be
    // acted upon or made available "as soon as possible".
    public function specialization() {
        global $DB, $COURSE, $PAGE, $OUTPUT, $CFG, $USER;
        if (isset($this->config)) {
            //if (empty($this->config->title)) {
            //    $this->title = get_string('defaulttitle', 'block_activityfeedback');
            //} else {
            //    $this->title = $this->config->title;
            //}
    
            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_activityfeedback');
            } else {
                $this->content->text = $this->config->text;
            }

            if (!empty($this->config->optionactive)) {
                $this->content->text .= $this->config->optionactive;
            }

            //$fs = get_file_storage();
            //
            //$optarray = array();
            //$opt1active = get_config('block_activityfeedback', 'opt1activeadmin');
            //if($opt1active) {
            //    $file = get_config('block_activityfeedback', 'opt1pictureadmin');
            //    if ($fs->file_exists(1, 'block_activityfeedback', 'activityfeedback_pix_admin', 0, '/', $file)) {
            //        //$pixparam[$file]
            //        $pixurl = block_activityfeedback_pix_url(1, 'activityfeedback_pix_admin', $file);
            //        //$this->content->text = html_writer::img($pixurl,$alt="alternativ");
            //        
            //        //todolig, unklar
            //        //You normally use an API function to generate these URL automatically, most often the file_rewrite_pluginfile_urls function. 
            //        $bla2 = file_rewrite_pluginfile_urls($pixurl, 'pluginfile.php',
            //                1, 'block_activityfeedback', 'activityfeedback_pix_admin', 0);
            //        
            //        $opt1name = get_config('block_activityfeedback', 'opt1nameadmin');
            //        $optarray[0]['key'] = $opt1name;
            //        $optarray[0]['value'] = $pixurl;
            //    }
            //}
            
            //evtl. auch unter /pix speichern, und dann von da holen, falls zwar aktiv, aber kein Bild in Settings

            // todolig: auf $arguments umbenennen, $rootpath auch darin
            // od. jede Variable einzeln
            $args = array(
                    'rootpath' => $CFG->wwwroot,
                    'userid' => $USER->id,
                    'contextid' => $this->context->id,
                    'courseid' => $COURSE->id
            );
            
            // vermutlich in get_content verschieben
            // Setting up AMD module.
            //$arguments = [$toggled, $whattoshow, $COURSE->id];
           
            //todolig: $optarray unschön wegen Kommentar: https://docs.moodle.org/dev/Javascript_Modules#Embedding_AMD_code_in_a_page
            // if the size of the params array is too large (> 1Kb), this will produce a developer warning.
            // Do not attempt to pass large amounts of data through this function, it will pollute the page size.
            // A preferred approach is to pass css selectors for DOM elements that contain data-attributes for any required data,
            // or fetch data via ajax in the background.
            //$rootpath = $CFG->wwwroot;
            
            //todolig: nur anzeigen, wenn nicht im configmodus
            //// Check to see if we are in editing mode
            //$canmanage = $PAGE->user_is_editing($this->instance->id);
            
            $PAGE->requires->js_call_amd('block_activityfeedback/module', 'init', array($args));
            //$allowHTML = get_config('activityfeedback', 'Allow_HTML');
        }

        //if ($allmods = get_records('modules') ) {
        //    foreach ($allmods as $mod) {
    
        //retrieve the relevant rows we will call $DB->get_records()
        // 2 Param: tablename u. array with the name of the column or field to be queried and the value of the queried field we want to match
        /*if ($activityfeedbackpages = $DB->get_records('block_activityfeedback', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($activityfeedbackpages as $activityfeedbackpage) {
                $pageurl = new moodle_url('/blocks/activityfeedback/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $activityfeedbackpage->id, 'viewpage' => '1'));
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($pageurl, $activityfeedbackpage->pagetitle);
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }*/
    
        ////-----Add editing capability----->
        //// Check to see if we are in editing mode
        //$canmanage = $PAGE->user_is_editing($this->instance->id);
        //
        //if ($activityfeedbackpages = $DB->get_records('block_activityfeedback', array('blockid' => $this->instance->id))) {
        //    $this->content->text .= html_writer::start_tag('ul');
        //    foreach ($activityfeedbackpages as $activityfeedbackpage) {
        //        if ($canmanage) {
        //            $pageparam = array('blockid' => $this->instance->id,
        //                    'courseid' => $COURSE->id,
        //                    'id' => $activityfeedbackpage->id);
        //            $editurl = new moodle_url('/blocks/activityfeedback/view.php', $pageparam);
        //            $editpicurl = new moodle_url('/pix/t/edit.gif');
        //            $edit = html_writer::link($editurl,
        //                    html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));
        //        } else {
        //            $edit = '';
        //        }
        //        $pageurl = new moodle_url('/blocks/activityfeedback/view.php',
        //                array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $activityfeedbackpage->id,
        //                        'viewpage' => true));
        //        $this->content->text .= html_writer::start_tag('li');
        //        $this->content->text .= html_writer::link($pageurl, $activityfeedbackpage->pagetitle);
        //        $this->content->text .= $edit;
        //        $this->content->text .= html_writer::end_tag('li');
        //    }
        //}
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
     * It shouldn't be possible to add multiple blocks of this type in a singe course.
     * Controls whether multiple block instances in a single course are allowed.
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
    
    ////The default behavior of this feature in our case will modify our block's "class" HTML attribute by appending
    //// the value "block_activityfeedback" (the prefix "block_" followed by the name of our block, lowercased).
    //// We can then use that class to make CSS selectors in our theme to alter this block's visual style
    //// (for example, ".block_activityfeedback { border: 1px black solid}").
    //// To change the default behavior, we will need to define a method which returns an associative array of
    //// attribute names and values. For example: 
    //public function html_attributes() {
    //    $attributes = parent::html_attributes(); // Get default values
    //    $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
    //    return $attributes;
    //}

    // to have our block appear only in the site front page, we would use:
    // Since all is missing, the block is disallowed from appearing in any course format; but then site is set to
    // TRUE, so it's explicitly allowed to appear in the site front page (remember that site matches site-index
    // because it's a prefix). 
    // Bsp.: https://docs.moodle.org/dev/Blocks#Authorized_Personnel_Only
    //public function applicable_formats() {
    //    return array('site-index' => true);
    //}

    //nur auf Kursen, ausser social course format
    // This time, we first allow the block to appear in all courses and then
    // we explicitly disallow the social format
    //public function applicable_formats() {
    //    return array(
    //            'course-view' => true,
    //            'course-view-social' => false);
    //}
}

