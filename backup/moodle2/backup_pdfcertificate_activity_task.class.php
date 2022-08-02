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
 * Defines backup_pdfcertificate_activity_task class
 *
 * @package   mod_pdfcertificate
 * @category  backup
 * @copyright 2022 Richard Jones richardnz@outlook.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_pdfcertificate
 * @see https://github.com/justinhunt/moodle-mod_pdfcertificate */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/pdfcertificate/backup/moodle2/backup_pdfcertificate_stepslib.php');
require_once($CFG->dirroot . '/mod/pdfcertificate/backup/moodle2/backup_pdfcertificate_settingslib.php');
/**
 * Provides the steps to perform one complete backup of the pdfcertificate instance
 *
 * @package   mod_pdfcertificate
 * @category  backup
 * @copyright 2022 Richard Jones richardnz@outlook.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_pdfcertificate
 * @see https://github.com/justinhunt/moodle-mod_pdfcertificate */
class backup_pdfcertificate_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the pdfcertificate.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_pdfcertificate_activity_structure_step('pdfcertificate_structure', 'pdfcertificate.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of pdfcertificates.
        $search = '/('.$base.'\/mod\/pdfcertificate\/index.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@pdfcertificateINDEX*$2@$', $content);

        // Link to pdfcertificate view by moduleid.
        $search = '/('.$base.'\/mod\/pdfcertificate\/view.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@pdfcertificateVIEWBYID*$2@$', $content);

        return $content;
    }
}
