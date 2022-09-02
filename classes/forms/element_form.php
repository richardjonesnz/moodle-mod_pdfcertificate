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
 * Element creation form.
 *
 * @package   mod_pdfcertificate
 * @copyright 2022 Richard Jones https://richardnz.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_pdfcertificate\forms;
require_once('../../lib/formslib.php');
/**
 * Define the form elements.
 */
class element_form extends \moodleform {

   /**
    * Defines forms elements
    */
    public function definition() {

        $mform = $this->_form;

        // The template name.
        $mform->addElement('text', 'name', get_string('name', 'mod_pdfcertificate'), ['size' => '64']);
        $mform->addRule('name', null, 'required', null, null);
        $mform->setType('name', PARAM_TEXT);

        // The template description.
        $mform->addElement('text', 'description', get_string('description', 'mod_pdfcertificate'), ['size' => '64']);
        $mform->addRule('description', null, 'required', null, null);
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('text', 'type', get_string('type', 'mod_pdfcertificate'));
        $mform->addRule('type', null, 'required', null, null);
        $mform->setType('type', PARAM_TEXT);

        $mform->addElement('static', 'info', get_string('note', 'mod_pdfcertificate'),
                get_string('ignored', 'mod_pdfcertificate'));

        $mform->addElement('text', 'mtable', get_string('table', 'mod_pdfcertificate'));
        $mform->setType('mtable', PARAM_TEXT);

        $mform->addElement('text', 'mfield', get_string('field', 'mod_pdfcertificate'));
        $mform->setType('mfield', PARAM_TEXT);

        // Hidden.
        $mform->addElement('hidden', 'pdfcertificateid', $this->_customdata['pdfcertificateid']);
        $mform->setType('pdfcertificateid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'elementid', $this->_customdata['elementid']);
        $mform->setType('elementid', PARAM_INT);


        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }
}