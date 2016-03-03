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

defined('MOODLE_INTERNAL') || die();

/**
 * Adds a Mystream instance.
 *
 * @param stdClass $data Form data
 * @param mod_assign_mod_form  $form The form
 * @return int The instance id of the new assignment
 */
function mystream_add_instance($mystream, $mform) {
    global $DB;

    $mystream->timemodified = time();

    $mystream->id = $DB->insert_record('mystream', $mystream);

    return $mystream->id;
}

/**
 * Updates a Mystream instance.
 *
 * @param stdClass $data Form data
 * @param mod_assign_mod_form  $form The form
 * @return bool If the update passed (true) or failed
 */
function mystream_update_instance($mystream, $mform) {
    global $DB;

    $mystream->timemodified = time();
    $mystream->id = $mystream->instance;

    return $DB->update_record('mystream', $mystream);
}

/**
 * Deletes a Mystream instance.
 *
 * @param int   $id   Record id to delete.
 * @return bool       If the update passed (true) or failed
 */
function mystream_delete_instance($id) {
    global $DB;

    return $DB->delete_records('mystream', array('id' => $id));
}
