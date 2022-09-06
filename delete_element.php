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
 * Delete current element.
 *
 * @package   mod_pdfcertificate
 * @copyright 2022 Richard Jones https://richardnz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \mod_pdfcertificate\output\delete_item;
use \mod_pdfcertificate\forms\confirm_delete_form;

require_once('../../config.php');
defined('MOODLE_INTERNAL') || die();

global $SITE, $DB;

$courseid = required_param('courseid', PARAM_INT);
$pdfcertificateid = required_param('pdfcertificateid', PARAM_INT);
$elementid = optional_param('elementid', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);
$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $pdfcertificateid], '*', MUST_EXIST);
$element = ($elementid == 0) ? null : $DB->get_record('pdfelements', ['id' => $elementid], '*', MUST_EXIST);
$PAGE->set_course($course);
$PAGE->set_url('/mod/pdfcertificate/delete_element.php',
        ['courseid' => $courseid,
         'pdfcertificateid' => $pdfcertificateid,
         'elementid' => $elementid]);

$PAGE->set_pagelayout('course');
$PAGE->activityheader->set_description('');

require_login();
$context = context_module::instance($cm->id);

require_capability('mod/pdfcertificate:delete', $context);

$mform = new confirm_delete_form(null, ['pdfcertificateid' => $pdfcertificateid, 'courseid' => $courseid,
        'templateid' => 0, 'elementid' => $elementid]);

// If the cancel button was pressed go back to the page.
if ($mform->is_cancelled()) {
    redirect(new moodle_url('manage_elements.php', ['pdfcertificateid' => $pdfcertificateid, 'courseid' => $courseid]), get_string('cancelled'), 2);
}

if ($data = $mform->get_data()) {

    // Delete the page.
    $DB->delete_records('pdfelements', ['id' => $elementid]);

    // Go back to view page.
    redirect(new moodle_url('manage_elements.php', ['pdfcertificateid' => $pdfcertificateid, 'courseid' => $courseid]),
            get_string('deleted', 'mod_pdfcertificate', 'element'), 2);
}

echo $OUTPUT->header();
echo $OUTPUT->render(new delete_item($mform, $element));
echo $OUTPUT->footer();
