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
use \mod_pdfcertificate\forms\template_form;
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$pdfcertificateid = required_param('pdfcertificateid', PARAM_INT);
$templateid = optional_param('templateid', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);
$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $cm->instance], '*', MUST_EXIST);

global $SITE;

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

// Create new or edit existing template?
if ($templateid == 0) {
    $data = new stdClass();
    $data->id = null;
} else {
    $data = $DB->get_record('pdftemplates', ['id' => $templateid], '*', MUST_EXIST);
    $data = file_prepare_standard_filemanager($data, 'baseimage', ['subdirs' => 0, 'maxbytes' => 104857600, 'maxfiles' => 1],
            $context, 'mod_pdfcertificate', 'baseimage', $data->id);
}

$mform = new template_form(null, ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid,'templateid' => $templateid]);
$mform->set_data($data);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('manage_templates.php', ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid]),
            get_string('cancelled'), 2);
}

if ($data = $mform->get_data()) {

    $timenow = time();
    $data->timemodified = $timenow;

    if ($templateid == 0) {
        $data->timecreated = $timenow;
        $data->id = $DB->insert_record('pdftemplates', $data);
    } else {
        $data->id = $templateid;
    }

    // Get the uploaded file into storage.
    $data = file_postupdate_standard_filemanager($data, 'baseimage', ['subdirs' => 0, 'maxbytes' => 104857600, 'maxfiles' => 1],
            $context, 'mod_pdfcertificate', 'baseimage', $templateid);

    // Get the file name.
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_pdfcertificate', 'baseimage', $templateid);
    $file = end($files);  // There's only 1 base file allowed per template.
    if ($file) {
        $data->baseimageurl = $file->get_filename();
    }
    // Update this record.
    $DB->update_record('pdftemplates', $data);

    redirect(new moodle_url('manage_templates.php', ['courseid' => $courseid, 'pdfcertificateid' => $pdfcertificateid]), '', 1);
}

// Output the page.
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();