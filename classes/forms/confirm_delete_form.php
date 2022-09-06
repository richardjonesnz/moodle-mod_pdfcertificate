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
 * Confirm deletion form.
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
class confirm_delete_form extends \moodleform {

   /**
    * Defines forms elements
    */
    public function definition() {
        global $DB;

        $mform = $this->_form;

        $mform->addElement('hidden', 'pdfcertificateid', $this->_customdata['pdfcertificateid']);
        $mform->setType('pdfcertificateid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'templateid', $this->_customdata['templateid']);
        $mform->setType('templateid', PARAM_INT);
        $mform->addElement('hidden', 'elementid', $this->_customdata['elementid']);
        $mform->setType('elementid', PARAM_INT);

        $this->add_action_buttons(true, get_string('confirm', 'mod_pdfcertificate'));
    }
}