<?php
// View für form, die beim Klick auf Link in Block angezeigt wird, brauche ich dann nicht
//
//require_once('../../config.php');
//require_once('activityfeedback_form.php');
//
//global $DB, $OUTPUT, $PAGE;
//
//// Check for all required variables.
//$courseid = required_param('courseid', PARAM_INT);
//
//// for breadcrumbs:
//$blockid = required_param('blockid', PARAM_INT);
//// Next look for optional variables.
//$id = optional_param('id', 0, PARAM_INT);
//
//$viewpage = optional_param('viewpage', false, PARAM_BOOL);
//
//
//if (!$course = $DB->get_record('course', array('id' => $courseid))) {
//    print_error('invalidcourse', 'block_activityfeedback', $courseid);
//}
//
//require_login($course);
//
//$PAGE->set_url('/blocks/activityfeedback/view.php', array('id' => $courseid));
//$PAGE->set_pagelayout('standard');
//$PAGE->set_heading(get_string('edithtml', 'block_activityfeedback'));
//
////breadcrumbs:
//$settingsnode = $PAGE->settingsnav->add(get_string('activityfeedbacksettings', 'block_activityfeedback'));
//$editurl = new moodle_url('/blocks/activityfeedback/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
//$editnode = $settingsnode->add(get_string('editpage', 'block_activityfeedback'), $editurl);
//$editnode->make_active();
//
//$activityfeedback = new activityfeedback_form();
//
//$toform['blockid'] = $blockid;
//$toform['courseid'] = $courseid;
//$toform['id'] = $id;
//$activityfeedback->set_data($toform);
//
//if($activityfeedback->is_cancelled()) {
//    // Cancelled forms redirect to the course main page.
//    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
//    redirect($courseurl);
//} else if ($fromform = $activityfeedback->get_data()) { //else if statement has been modified to create the variable $fromform. 
//    // We need to add code to appropriately act on and store the submitted data
//    // but for now we will just redirect back to the course main page.
//    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
//
//    //für debuggen:
//    //print_object() is a useful moodle function which prints out data from mixed data types showing
//    // the keys and data for arrays and objects.
//    //print_object($fromform);
//
//    // We need to add code to appropriately act on and store the submitted data
//    if (!$DB->insert_record('block_activityfeedback', $fromform)) {
//        print_error('inserterror', 'block_activityfeedback');
//    }
//
//    // We need to add code to appropriately act on and store the submitted data
//    //todolig
//    if ($fromform->id != 0) {
//        if (!$DB->update_record('block_activityfeedback', $fromform)) {
//            print_error('updateerror', 'block_activityfeedback');
//        }
//    } else {
//        if (!$DB->insert_record('block_activityfeedback', $fromform)) {
//            print_error('inserterror', 'block_activityfeedback');
//        }
//    }
//    
//    //redirect($courseurl);
//} else {
//    // form didn't validate or this is the first display
//    $site = get_site();
//    echo $OUTPUT->header();
//
//    if ($id) {
//        $activityfeedbackpage = $DB->get_record('block_activityfeedback', array('id' => $id));
//        if ($viewpage) {
//            $activityfeedbackpage = $DB->get_record('block_activityfeedback', array('id' => $id));
//            block_activityfeedback_print_page($activityfeedbackpage);
//        } else {
//            $activityfeedback->display();
//        }
//    } else {
//        $activityfeedback->display();
//    }
//    echo $OUTPUT->footer();
//}
//?>
