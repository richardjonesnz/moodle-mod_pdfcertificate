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
 * Gives a list of existing templates
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
/**
 * pdfcertificate: Design a pdf certificate by adding elements.
 */

class define_elements implements renderable, templatable {

    protected $template;
    protected $pdfcertificateid;
    protected $courseid;

    public function __construct($pdfcertificateid, $courseid, $template) {

        $this->pdfcertificateid = $pdfcertificateid;
        $this->courseid = $courseid;
        $this->template = $template;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $data = array();

        $data['name'] = $this->template->name;
        $data['height'] = $this->template->height;
        $data['width'] = $this->template->width;
        $data['courseid'] = $this->courseid;
        $data['pdfcertificateid'] = $this->pdfcertificateid;
        $data['baseimageurl'] = $this->template->baseimageurl;

        return $data;

    }
}
