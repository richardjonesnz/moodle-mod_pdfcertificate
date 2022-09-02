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
 * Create or edit a base pdf certificate element.
 *
 * @package mod_pdfcertificate
 * @copyright 2022 Richard F Jones <richardnz@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use \mod_pdfcertificate\forms\element_form;
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$pdfcertificateid = required_param('pdfcertificateid', PARAM_INT);
$elementid = optional_param('elementid', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);
$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $cm->instance], '*', MUST_EXIST);

$PAGE->set_course($course);
$PAGE->set_url('/mod/pdfcertificate/manage_elements.php',
        ['courseid' => $courseid,
         'pdfcertificateid' => $pdfcertificateid,
         'elementid' => $elementid]);

require_login();

$context = context_course::instance($courseid);
$PAGE->set_heading(format_string($course->fullname));
$PAGE->activityheader->set_description('');

// Check the users permissions to see the edit element page.
require_capability('mod/pdfcertificate:manage', $context);

// Create new or edit existing element?
if ($elementid == 0) {
    $data = new stdClass();
    $data->id = null;
} else {
    $data = $DB->get_record('pdfelements', ['id' => $elementid], '*', MUST_EXIST);
}

$mform = new element_form(null, ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid,'elementid' => $elementid]);
$mform->set_data($data);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('manage_elements.php', ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid]),
            get_string('cancelled'), 2);
}

if ($data = $mform->get_data()) {

    $timenow = time();
    $data->timemodified = $timenow;

    if ($elementid == 0) {
        $data->timecreated = $timenow;
        $data->id = $DB->insert_record('pdfelements', $data);
    } else {
        $data->id = $elementid;
        $DB->update_record('pdfelements', $data);
    }

    // Post process if type dynamic not selected.
    if ($data->type !== 'dynamic') {
        $data->mtable = '';
        $data->mfield = '';
        $DB->update_record('pdfelements', $data);
    }

    redirect(new moodle_url('manage_elements.php', ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid]), '', 1);
}

// Output the page.
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();