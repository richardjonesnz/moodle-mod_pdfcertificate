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
use \mod_pdfcertificate\output\manage_templates;
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$pdfcertificateid = required_param('pdfcertificateid', PARAM_INT);
$templateid = optional_param('templateid', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);
$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $cm->instance], '*', MUST_EXIST);

global $DB;

$PAGE->set_course($course);
$PAGE->set_url('/mod/pdfcertificate/manage_templates.php',
        ['courseid' => $courseid,
         'pdfcertificateid' => $pdfcertificateid,
         'templateid' => $templateid]);

require_login();

$context = context_course::instance($courseid);
$PAGE->set_heading(format_string($course->fullname));
$PAGE->activityheader->set_description('');

// Check the users permissions to see the edit template page.
require_capability('mod/pdfcertificate:manage', $context);

//Read template records from the database.
$templates = $templates = $DB->get_records('pdftemplates', null, null, '*');

// Output to browser.
echo $OUTPUT->header();
echo $OUTPUT->render(new manage_templates($pdfcertificate, $course, $templates));
echo $OUTPUT->footer();
