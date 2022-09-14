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
 * Delete an item.
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
$itemid = required_param('itemid', PARAM_INT);
$return = required_param('return', PARAM_ALPHA);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('pdfcertificate', $pdfcertificateid, $courseid, false, MUST_EXIST);
$pdfcertificate = $DB->get_record('pdfcertificate', ['id' => $pdfcertificateid], '*', MUST_EXIST);

$PAGE->set_course($course);
$PAGE->set_url('/mod/pdfcertificate/delete_item.php',
        ['courseid' => $courseid,
         'pdfcertificateid' => $pdfcertificateid,
         'itemid' => $itemid]);

$PAGE->set_pagelayout('course');
$PAGE->activityheader->set_description('');

require_login();
$context = context_module::instance($cm->id);

require_capability('mod/pdfcertificate:delete', $context);

$mform = new confirm_delete_form(null, ['pdfcertificateid' => $pdfcertificateid, 'courseid' => $courseid,
        'itemid' => $itemid, 'return' => $return]);

// Return url's.
$view_url = new moodle_url('view.php', ['n' => $pdfcertificateid]);
$template_url = new moodle_url('manage_templates.php', ['pdfcertificateid' => $pdfcertificateid,
        'courseid' => $courseid]);
$element_url = new moodle_url('manage_elements.php', ['pdfcertificateid' => $pdfcertificateid,
        'courseid' => $courseid]);
$design_url = new moodle_url('manage_designs.php', ['pdfcertificateid' => $pdfcertificateid,
        'courseid' => $courseid]);

// Find table to delete from.
$table = null;

switch ($return) {

    case 'template': {
        $return_url = $template_url;
        $table = 'pdftemplates';
        break;
    }
    case 'element': {
        $return_url = $element_url;
        $table = 'pdfelements';
        break;
    }
    case 'design': {
        $return_url = $design_url;
        $table = 'pdfdesigns';
        break;
    }
    default: {
        $return_url = $view_url;
    }
}

// Do we have a valid item record?
$item = null;
if ($table) {
    // Get the item.
    $item = $DB->get_record($table, ['id' => $itemid], '*', MUST_EXIST);
} else {
    redirect($return_url, get_string('deletefailed', 'mod_pdfcertificate'), 2);
}

// If the cancel button was pressed go back to the originating page.
if ($mform->is_cancelled()) {
    redirect($return_url, get_string('cancelled'), 2);
}

// If form was submitted.
if ($data = $mform->get_data()) {
    $DB->delete_records($table, ['id' => $itemid]);
    redirect($return_url, get_string('deleted', 'mod_pdfcertificate', $return), 2);
}

echo $OUTPUT->header();
echo $OUTPUT->render(new delete_item($mform, $item));
echo $OUTPUT->footer();
