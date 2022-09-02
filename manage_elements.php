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
 * Create or edit a base pdf certificate template.
 *
 * @package mod_pdfcertificate
 * @copyright 2022 Richard F Jones <richardnz@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use \mod_pdfcertificate\output\manage_elements;
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$pdfcertificateid = required_param('pdfcertificateid', PARAM_INT);
$templateid = optional_param('templateid', 0, PARAM_INT);

$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $pdfcertificateid], '*', MUST_EXIST);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);

global $DB;

$PAGE->set_course($course);
$PAGE->set_url('/mod/pdfcertificate/manage_elements.php',
        ['courseid' => $courseid,
         'pdfcertificateid' => $pdfcertificateid,
         'templateid' => $templateid]);
$PAGE->set_pagelayout('course');

require_login();

// Set the page information.
$PAGE->set_title(format_string($pdfcertificate->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->activityheader->set_description('');
$PAGE->activityheader->set_hideoverflow(false);

//Read template records from the database.
$elements = $elements = $DB->get_records('pdfelements', null, null, '*');

// Output to browser.
echo $OUTPUT->header();
echo $OUTPUT->render(new manage_elements($pdfcertificateid, $courseid, $elements));
echo $OUTPUT->footer();
