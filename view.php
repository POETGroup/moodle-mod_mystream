<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Mystream mod definition
 *
 * @package    contrib
 * @subpackage mod_mystream
 * @author     Mike Churchward <mike.churchward@poetgroup.org>
 * @copyright  2016 The POET Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$m = optional_param('m', 0, PARAM_INT);   // Mystream ID

if ($id) {
    $PAGE->set_url('/mod/mystream/view.php', array('id' => $id));
    if (! $cm = get_coursemodule_from_id('mystream', $id)) {
        print_error('invalidcoursemodule');
    }

    if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }

    if (! $mystream = $DB->get_record('mystream', array('id' => $cm->instance))) {
        print_error('invalidcoursemodule');
    }

} else {
    $PAGE->set_url('/mod/label/view.php', array('m' => $m));
    if (! $mystream = $DB->get_record('mystream', array('id' => $m))) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', array('id' => $mystream->course))) {
        print_error('coursemisconf');
    }
    if (! $cm = get_coursemodule_from_instance('mystream', $mystream->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
}

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);

$params = array(
    'objectid' => $mystream->id,
    'context' => $context
);
$event = \mod_mystream\event\course_module_viewed::create($params);
$event->trigger();

// The rest should be the activity-specific display code.
$PAGE->set_title($mystream->name);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($mystream->name), 2, null);
if ($mystream->intro) {
    echo $OUTPUT->box(format_module_intro('mystream', $mystream, $cm->id), 'generalbox', 'intro');
}
echo $OUTPUT->footer();
