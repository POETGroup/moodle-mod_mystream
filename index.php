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

require(dirname(__FILE__).'/../../config.php');

$id = required_param('id', PARAM_INT); // Course ID.

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

unset($id);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

// Get all required strings.
$strmystreams = get_string('modulenameplural', 'mod_mystream');
$strmystream  = get_string('modulename', 'mod_mystream');
$strname = get_string('name');
$strintro = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_url('/mod/mystream/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$strmystreams);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strmystreams);
echo $OUTPUT->header();

// Get all the appropriate data.
if (!$mystreams = get_all_instances_in_course('mystream', $course)) {
    notice(get_string('thereareno', 'moodle', $strmystreams), "$CFG->wwwroot/course/view.php?id=$course->id");
    die;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_'.$course->format);
    $table->head  = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');
} else {
    $table->head  = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($mystreams as $mystream) {
    $cm = $modinfo->get_cm($mystream->coursemodule);
    if ($usesections) {
        $printsection = '';
        if ($mystream->section !== $currentsection) {
            if ($mystream->section) {
                $printsection = get_section_name($course, $mystream->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $mystream->section;
        }
    } else {
        $printsection = html_writer::tag('span', userdate($mystream->timemodified), array('class' => 'smallinfo'));
    }

    $class = $mystream->visible ? null : array('class' => 'dimmed'); // Hidden modules are dimmed.

    $table->data[] = array (
        $printsection,
        html_writer::link(new moodle_url('view.php', array('id' => $cm->id)), format_string($mystream->name), $class),
        format_module_intro('mystream', $mystream, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();
