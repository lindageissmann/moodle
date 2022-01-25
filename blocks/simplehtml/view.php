<?php
//// View für form, die beim Klick auf Link in Block angezeigt wird, brauche ich dann nicht
require_once('../../config.php');
require_once('simplehtml_form.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);

// for breadcrumbs:
$blockid = required_param('blockid', PARAM_INT);
// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);

$viewpage = optional_param('viewpage', false, PARAM_BOOL);


if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_simplehtml', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/simplehtml/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_simplehtml'));

//breadcrumbs:
$settingsnode = $PAGE->settingsnav->add(get_string('simplehtmlsettings', 'block_simplehtml'));
$editurl = new moodle_url('/blocks/simplehtml/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_simplehtml'), $editurl);
$editnode->make_active();

$simplehtml = new simplehtml_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['id'] = $id;
$simplehtml->set_data($toform);

if($simplehtml->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $simplehtml->get_data()) { //else if statement has been modified to create the variable $fromform. 
    // We need to add code to appropriately act on and store the submitted data
    // but for now we will just redirect back to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));

    //für debuggen:
    //print_object() is a useful moodle function which prints out data from mixed data types showing
    // the keys and data for arrays and objects.
    //print_object($fromform);

    // We need to add code to appropriately act on and store the submitted data
    if (!$DB->insert_record('block_simplehtml', $fromform)) {
        print_error('inserterror', 'block_simplehtml');
    }

    // We need to add code to appropriately act on and store the submitted data
    //todolig
    if ($fromform->id != 0) {
        if (!$DB->update_record('block_simplehtml', $fromform)) {
            print_error('updateerror', 'block_simplehtml');
        }
    } else {
        if (!$DB->insert_record('block_simplehtml', $fromform)) {
            print_error('inserterror', 'block_simplehtml');
        }
    }
    
    //redirect($courseurl);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();

    if ($id) {
        $simplehtmlpage = $DB->get_record('block_simplehtml', array('id' => $id));
        if ($viewpage) {
            $simplehtmlpage = $DB->get_record('block_simplehtml', array('id' => $id));
            block_simplehtml_print_page($simplehtmlpage);
        } else {
            $simplehtml->display();
        }
    } else {
        $simplehtml->display();
    }
    echo $OUTPUT->footer();
}
?>
