<?php
/**
 * General utility functions, especially for file handling.
 */
defined('MOODLE_INTERNAL') || die;

/**
 * Get URL for picture (points to pluginfile),
 * e.g.: http://moodlevirtualhost/moodle/pluginfile.php/1/block_activityfeedback/activityfeedback_pix_admin/1/begeistert
 * see function block_activityfeedback_pluginfile() for further explanation
 * @param $contextid
 * @param $filearea
 * @param $itemid
 * @param $filename
 * @return string
 */
function block_activityfeedback_pix_url($contextid, $filearea, $itemid, $filename): string {
    $filename = str_replace('/','',$filename);
    $filename = str_replace('.png','',$filename);

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
 * Serve the image files from the block_activityfeedback file areas.
 * https://docs.moodle.org/dev/File_API
 * https://docs.moodle.org/dev/File_API_internals
 * https://docs.moodle.org/dev/Callbacks
 * When developing third party plugins, pluginfile.php looks for a callback function in the appropriate plugin.
 * These functions are stored in lib.php files and are named component_name_pluginfile().
 * call hierarchy: $CFG->wwwroot/pluginfile.php
 * -> lib/filelib.php: file_pluginfile(): if block, then
 * -> pluginname_pluginfile() (= this function)
 * @param stdClass $course the course object
 * @param stdClass $bi block instance, is empty because context is not a block instance, images are saved in system context
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, just send the file otherwise and do not return anything
 */
function block_activityfeedback_pluginfile($course, $bi, $context, $filearea, $args, $forcedownload, array $options=array()) {
    //check if contextleve is as expected
    //although it is a block plugin, contextlevel is not block, because images were saved in admin settings (system context)
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    //make sure the filearea is one of those used by the plugin
    if ($filearea !== 'activityfeedback_pix_admin') {
        return false;
    }

    //make sure the user is logged in and has access
    require_login($course);

    //check the relevant capabilities (we use only the default capability to view a block)
    if (!has_capability('moodle/block:view', $context)) {
        return false;
    }

    //(if you set itemid to null in make_pluginfile_url, set $itemid to 0 instead)
    $itemid = array_shift($args); //first item in the $args array

    //extract the filename from the $args array
    $filename = array_pop($args) . '.png'; //last item in the $args array
    $filepath = '/';

    //retrieve the file from the Files API
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_activityfeedback', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; //file does not exist
    }

    //send the file back to the browser
    //(with a cache lifetime of 6 months, longer period is recommended for static resources like images:
    //https://imagekit.io/blog/ultimate-guide-to-http-caching-for-static-assets/)
    send_stored_file($file, 15552000, 0, $forcedownload, $options);
}