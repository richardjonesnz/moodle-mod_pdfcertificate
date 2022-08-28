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
 * @copyright  202 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_pdfcertificate\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;
/**
 * pdfcertificate: Create a new view page renderable object
 *
 * @param object pdfcertificate - instance of pdfcertificate.
 * @param int id - course module id.
 * @param object moodle_url the url of the base certificate.
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 */

class view implements renderable, templatable {

    protected $pdfcertificate;
    protected $courseid;
    protected $cmid;

    public function __construct($pdfcertificate, $courseid, $cmid) {

        $this->pdfcertificate = $pdfcertificate;
        $this->courseid = $courseid;
        $this->cmid = $cmid;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $data = new stdClass();

        // Moodle handles processing of std intro field.
        $data->body = format_module_intro('pdfcertificate', $this->pdfcertificate, $this->cmid);

        // Navigation tabs.
        $url = new moodle_url('view.php', ['n' => $this->pdfcertificate->id]);
        $data->viewurl = $url->out(false);
        $url = new moodle_url('manage_templates.php', ['courseid' => $this->courseid,
                'pdfcertificateid' => $this->pdfcertificate->id]);
        $data->templatesurl = $url->out(false);

        return $data;
    }
}
