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
 * design creation form.
 *
 * @package   mod_pdfcertificate
 * @copyright 2022 Richard Jones https://richardnz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_pdfcertificate\forms;
require_once('../../lib/formslib.php');
/**
 * Define the form designs.
 */
class design_form extends \moodleform {

   /**
    * Defines forms designs
    */
    public function definition() {
        global $DB;
        $mform = $this->_form;

        // The design name.
        $mform->addElement('text', 'name', get_string('name', 'mod_pdfcertificate'), ['size' => '64']);
        $mform->addRule('name', null, 'required', null, null);
        $mform->setType('name', PARAM_TEXT);

        // The design description.
        $mform->addElement('text', 'description', get_string('description', 'mod_pdfcertificate'), ['size' => '64']);
        $mform->addRule('description', null, 'required', null, null);
        $mform->setType('description', PARAM_TEXT);

        // The template to use for this design.
        $records = $DB->get_records('pdftemplates', null, null, 'id, name, baseimage');
        $templates = [];
        // Array of data for select.
        foreach ($records as $record) {
            $templates[$record->id] = $record->id . ': ' . $record->name . ' (' . $record->baseimage . ')';
        }
        $templates[0] = get_string('none', 'mod_pdfcertificate');
        //var_dump($templates);exit;
        $mform->addElement('select', 'templateid',
                get_string('select_template', 'mod_pdfcertificate'), $templates);
        $mform->addHelpButton('templateid', 'templateid', 'mod_pdfcertificate');
        $mform->setType('templateid', PARAM_INT);
        $mform->setDefault('templateid', 0);

        // Hidden.
        $mform->addElement('hidden', 'pdfcertificateid', $this->_customdata['pdfcertificateid']);
        $mform->setType('pdfcertificateid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'designid', $this->_customdata['designid']);
        $mform->setType('designid', PARAM_INT);

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }
}