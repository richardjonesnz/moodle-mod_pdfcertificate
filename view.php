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
 * Prints a particular instance of pdfcertificate
 *
 * @package    mod_pdfcertificate
 * @copyright  2022 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_pdfcertificate\output\view;
require_once('../../config.php');

// We need the course module id (id) or the pdfcertificate instance id (n).
$id = optional_param('id', 0, PARAM_INT);
$n  = optional_param('n', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('pdfcertificate', $id, 0, false,
            MUST_EXIST);
    $course = $DB->get_record('course',
            array('id' => $cm->course), '*', MUST_EXIST);
    $pdfcertificate = $DB->get_record('pdfcertificate',
            array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $pdfcertificate = $DB->get_record('pdfcertificate', array('id' => $n), '*',
            MUST_EXIST);
    $course = $DB->get_record('course',
            array('id' => $pdfcertificate->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificate->id,
            $course->id, false, MUST_EXIST);
}

// Print the page header.
$PAGE->set_url('/mod/pdfcertificate/view.php', array('id' => $cm->id));
$context = context_course::instance($course->id);

require_login($course, true, $cm);

// Set the page information.
$PAGE->set_title(format_string($pdfcertificate->name));
$PAGE->set_heading(format_string($course->fullname));

// Check for intro page content.
if (!$pdfcertificate->intro) {
    $pdfcertificate->intro = get_string('intro', 'mod_pdfcertificate');
}

if ($pdfcertificate->basepdf) {
    file_save_draft_area_files($pdfcertificate->basepdf, $context->id, 'mod_pdfcertificate', 'basepdf', 0,
            ['subdirs' => 0, 'maxbytes' => 104857600, 'maxfiles' => 1]);
}

// A few surpising hoops to jump through to get the filename.
$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'mod_pdfcertificate', 'basepdf', 0);
// We will only ever store one file in the basepdf area.
$file = end($files);
if ($file) {
    $url = moodle_url::make_pluginfile_url($context->id, 'mod_pdfcertificate', 'basepdf', 0,
            '/', $file->get_filename());
}

// Start output to browser.
echo $OUTPUT->header();

// Call classes/output/view and view.mustache to create output.
echo $OUTPUT->render(new view($pdfcertificate, $cm->id, $url));

// End output to browser.
echo $OUTPUT->footer();
