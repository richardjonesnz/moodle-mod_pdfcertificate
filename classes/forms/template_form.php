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
 * Template creation form.
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
class template_form extends \moodleform {

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

        // Dimensions.
        $mform->addElement('text', 'height', get_string('height', 'mod_pdfcertificate'), ['size' => '4']);
        $mform->addRule('height', null, 'required', null, '210');
        $mform->setType('height', PARAM_INT);

        $mform->addElement('text', 'width', get_string('width', 'mod_pdfcertificate'), ['size' => '4']);
        $mform->addRule('width', null, 'required', null, '297');
        $mform->setType('width', PARAM_INT);

        // Get and store the base image file.
        $mform->addElement('filemanager', 'baseimage_filemanager', get_string('baseimage', 'mod_pdfcertificate'),
                null, $this->get_file_options());
        $mform->addRule('baseimage_filemanager', null, 'required');
        $mform->addHelpButton('baseimage_filemanager', 'baseimage', 'mod_pdfcertificate');

        //Add a placeholder for the baseimageurl
        $mform->addElement('hidden', 'baseimageurl', 'filename');

        // Hidden.
        $mform->addElement('hidden', 'pdfcertificateid', $this->_customdata['pdfcertificateid']);
        $mform->setType('pdfcertificateid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'templateid', $this->_customdata['templateid']);
        $mform->setType('templateid', PARAM_INT);


        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }
    private function get_file_options() {
        return ['subdirs' => 0, 'maxbytes' => 104857600, 'areamaxbytes' => 104857600, 'maxfiles' => 1,
        'accepted_types' => ['web_image']];
    }
}