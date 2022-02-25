<?php
/**
 * Version information needed for installation and upgrade.
 * "Update version.php to see changes" in comments of other files means changing the number behind 'version' in this file.
 * See also https://docs.moodle.org/dev/version.php.
 * Exact Moodle version of a release can be found in: https://docs.moodle.org/dev/Releases.
 *
 * @package   block_activityfeedback
 * @copyright Fernfachhochschule Schweiz, 2022
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die(); // default, permit navigating to this file, no external access

$plugin->component = 'block_activityfeedback';  // full name of the plugin (used for diagnostics)
$plugin->version = 2011062900; // current plugin version (date: YYYYMMDDXX)
$plugin->requires = 2021051700; // requires this Moodle version (this is the release version for Moodle 3.11)
$plugin->maturity = MATURITY_STABLE; // defines maturity level / how stable it is (plugin is ready for installation)
$plugin->release = 'v3.11-r1.0'; // plugin release
