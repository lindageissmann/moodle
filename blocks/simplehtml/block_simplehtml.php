<?php
class block_simplehtml extends block_base {
    
    public function init() {
        $this->title = get_string('simplehtml', 'block_simplehtml');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        //time-saver, weil Berechnung aufwändig u. intern in Moodle mehrfach aufgerufen
        if ($this->content !== null) {
            return $this->content;
        }

        //$this->content = new stdClass;
        //$this->content->text = 'The content of our SimpleHTML block!';
        //$this->content->footer = 'Footer here...';

        global $COURSE;
        // The other code.
        $url = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
        $this->content->footer = html_writer::link($url, get_string('addpage', 'block_simplehtml'));

        // siehe auch: https://docs.moodle.org/dev/Blocks#Lists
        // falls Liste anzeigen statt Text und Footer
        return $this->content;
    }

    // it's guaranteed to be automatically called by Moodle as soon as our instance configuration is loaded
    // and available (that is, immediately after init() is called)
    // providing a specialization() method is the natural choice for any configuration data that needs to be
    // acted upon or made available "as soon as possible"
    // übernimmt config
    // $this->config in allen Block-Methoden ausser init() verfügbar
    public function specialization() {
        global $DB, $COURSE, $PAGE;
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_simplehtml');
            } else {
                $this->title = $this->config->title;
            }

            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_simplehtml');
            } else {
                $this->content->text = $this->config->text;
            }
        }

        //retrieve the relevant rows we will call $DB->get_records()
        // 2 Param: tablename u. array with the name of the column or field to be queried and the value of the queried field we want to match
        /*if ($simplehtmlpages = $DB->get_records('block_simplehtml', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($simplehtmlpages as $simplehtmlpage) {
                $pageurl = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $simplehtmlpage->id, 'viewpage' => '1'));
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($pageurl, $simplehtmlpage->pagetitle);
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }*/

        //-----Add editing capability----->
        // Check to see if we are in editing mode
        $canmanage = $PAGE->user_is_editing($this->instance->id);

        // LInks auf view.php, wo konfigurierte Seite angezeigt wird
        if ($simplehtmlpages = $DB->get_records('block_simplehtml', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($simplehtmlpages as $simplehtmlpage) {
                if ($canmanage) {
                    $pageparam = array('blockid' => $this->instance->id,
                            'courseid' => $COURSE->id,
                            'id' => $simplehtmlpage->id);
                    $editurl = new moodle_url('/blocks/simplehtml/view.php', $pageparam);
                    $editpicurl = new moodle_url('/pix/t/edit.gif');
                    $edit = html_writer::link($editurl,
                            html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));
                } else {
                    $edit = '';
                }
                $pageurl = new moodle_url('/blocks/simplehtml/view.php',
                        array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $simplehtmlpage->id,
                                'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($pageurl, $simplehtmlpage->pagetitle);
                $this->content->text .= $edit;
                $this->content->text .= html_writer::end_tag('li');
            }
        }
    }

    // to be able to add multiple blocks of this type to a single course
    // Admin kann es trotzdem auf nicht erlaubt: for each block from the Administration / Configuration / Blocks page
    public function instance_allow_multiple() {
        return true;
    }

    // Since version 2.4, the following line must be added in order to enable global configuration:
    // This line tells Moodle that the block has a settings.php file.  
    function has_config() {return true;}

    // falls Titel nicht angezeigt werden soll
    // im init() müssen wir unabhängig davon immer! zwingend einen eindeutigen Titel definieren
    //public function hide_header() {
    //    return true;
    //}

    //The default behavior of this feature in our case will modify our block's "class" HTML attribute by appending
    // the value "block_simplehtml" (the prefix "block_" followed by the name of our block, lowercased).
    // We can then use that class to make CSS selectors in our theme to alter this block's visual style
    // (for example, ".block_simplehtml { border: 1px black solid}").
    // To change the default behavior, we will need to define a method which returns an associative array of
    // attribute names and values. For example: 
    public function html_attributes() {
        $attributes = parent::html_attributes(); // Get default values
        $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
        return $attributes;
    }

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