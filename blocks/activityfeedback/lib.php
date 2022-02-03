<?php
use core\session\manager;

defined('MOODLE_INTERNAL') || die;

// https://docs.moodle.org/dev/File_AP
// https://docs.moodle.org/dev/File_API_internals
// (https://docs.moodle.org/dev/Using_the_File_API_in_Moodle_forms)

function block_activityfeedback_pix_url($contextid, $filearea, $itemid, $filename): string {
    $filename = str_replace('/','',$filename);
    $filename = str_replace('.png','',$filename);

    //$filename = clean_param($filename, PARAM_FILE);
    //
    //if ($filename === '') {
    //    $filename = '.';
    //}
    
    // https://docs.moodle.org/dev/File_API
    return strval(moodle_url::make_pluginfile_url(
            $contextid,
            'block_activityfeedback',
            $filearea,
            $itemid,
            '/',
            $filename)
    );
}

/**
 * //todolig: diese statt untere, aber prüfen, testen, verbessern
 * Serve the files from the block_activityfeedback file areas
 * https://docs.moodle.org/dev/File_API
 * When developing third party plugins, pluginfile.php looks for a callback function in the appropriate plugin.
 * These functions are stored in lib.php files and are named component_name_pluginfile()
 * $CFG->wwwroot/pluginfile.php -> lib/filelib.php -> if block, then pluginname_pluginfile()
 * @param stdClass $course the course object
 * @param stdClass $cm_or_bi the course module object or the block instance
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function block_activityfeedback_pluginfile($course, $cm_or_bi, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // block würde Sinn machen, ist aber System // todolig: evtl. Kontext von Anfang an anders definieren?! dann auch unten Zugriff prüfbar
    // vermutlich nicht anders möglich, da als admin_settings und das ist ja System-Kontext, nicht Kurs-spezifisch!
    //// Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    //if ($context->contextlevel != CONTEXT_MODULE) {
    //    return false;
    //}

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'activityfeedback_pix_admin') {
        return false;
    }

    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    //require_login($course, true, $cm_or_bi);

    //// Check the relevant capabilities - these may vary depending on the filearea being accessed.
    //if (!has_capability('mod/MYPLUGIN:view', $context)) {
    //    return false;
    //}

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename from the $args array.
    $filename = array_pop($args) . '.png'; // The last item in the $args array.
    $filepath = '/';

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_activityfeedback', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering. 
    send_stored_file($file, 86400, 0, $forcedownload, $options);
    
    //todolig: evtl. $forcedownload=true?
}

///**
// * @param stdClass $course Course object
// * @param stdClass $bi Block instance record
// * @param context_course|context_system $context Context object
// * @param string $filearea File area
// * @param array $args Extra arguments
// * @param bool $forcedownload Whether or not force download
// * @param array $options Additional options affecting the file serving
// *
// * @return bool
// *
// * @throws moodle_exception
// */
//function block_activityfeedback_XXpluginfileXX($course, $bi, $context, $filearea, $args, $forcedownload, array $options = array()) {
//    global $CFG, $USER;
//
//    $fs = get_file_storage();
//    $filename = array_pop($args);
//
//    if ($filearea === 'activityfeedback_pix_admin') {
//        $file = $fs->get_file(
//                $context->id,
//                'block_activityfeedback',
//                $filearea,
//                $args[0], // itemid
//                '/',
//                $filename . '.png'
//        );
//        if (!$file || $file->is_directory()) {
//            send_file_not_found();
//        }
//    } else {
//        send_file_not_found();
//    }
//
//    manager::write_close();
//    send_stored_file($file, null, 0, true, $options);
//
//    return true;
//}

//function block_activityfeedback_images() {
//    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_activityfeedback'), 'src' => "pix/1.png")),
//            html_writer::tag('img', '', array('alt' => get_string('blue', 'block_activityfeedback'), 'src' => "pix/2.png")),
//            html_writer::tag('img', '', array('alt' => get_string('green', 'block_activityfeedback'), 'src' => "pix/3.png")));
//}

//// To avoid any code duplication and make it easy to reuse this functionality create a function in lib.php
//// to handle the page display. A preloaded activityfeedback page is passed in as a single parameter,
//// and an optional parameter will control whether the data is returned or directly printed out. 
//// false = printed out
//function block_activityfeedback_print_page($activityfeedback, $return = false) {
//    // To display the page title use the $OUTPUT class
//    global $OUTPUT, $COURSE;
//    $display = $OUTPUT->heading($activityfeedback->pagetitle);
//
//    // After we have displayed the title, let's add a box to put around the rest of
//    // the elements that we will display. 
//    $display .= $OUTPUT->box_start();
//
//    if($activityfeedback->displaydate) {
//        // put a div tag around the date, and give it a class to target with CSS later
//        $display .= html_writer::start_tag('div', array('class' => 'activityfeedback displaydate'));
//        $display .= userdate($activityfeedback->displaydate);
//        $display .= html_writer::end_tag('div');
//    }
//
//    // display text
//    $display .= clean_text($activityfeedback->displaytext);
//
//    //close the box
//    $display .= $OUTPUT->box_end();
//
//    // display the picture
//    if ($activityfeedback->displaypicture) {
//        $display .= $OUTPUT->box_start();
//        $images = block_activityfeedback_images();
//        $display .= $images[$activityfeedback->picture];
//        $display .= html_writer::start_tag('p');
//        $display .= clean_text($activityfeedback->description);
//        $display .= html_writer::end_tag('p');
//        $display .= $OUTPUT->box_end();
//    }
//
//    if($return) {
//        return $display;
//    } else {
//        echo $display;
//    }
//
//    
//}
