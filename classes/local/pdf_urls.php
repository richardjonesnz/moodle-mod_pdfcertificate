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
 * Class defining management urls for navigation tabs.
 *
 * @package    mod_pdfcertificate
 * @copyright  2022 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_pdfcertificate\local;        // Navigation tabs.
use moodle_url;

class pdf_urls {

    static function get_urls($pdfcertificateid, $courseid) {

        $data = array();

        $url = new moodle_url('view.php', ['n' => $pdfcertificateid]);
        $data['viewurl'] = $url->out(false);
        $url = new moodle_url('manage_templates.php', ['courseid' => $courseid,
                'pdfcertificateid' => $pdfcertificateid]);
        $data['templatesurl'] = $url->out(false);
        $url = new moodle_url('manage_elements.php', ['courseid' => $courseid,
                'pdfcertificateid' => $pdfcertificateid]);
        $data['elementsurl'] = $url->out(false);
        $url = new moodle_url('manage_designs.php', ['courseid' => $courseid,
                'pdfcertificateid' => $pdfcertificateid]);
        $data['designsurl'] = $url->out(false);

        return $data;
    }
}