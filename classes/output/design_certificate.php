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
use moodle_url;
/**
 * pdfcertificate: Design a pdf certificate by adding elements.
 */

class design_certificate implements renderable, templatable {

    protected $pdfcertificate;
    protected $courseid;
    protected $template;
    protected $elements;  // Available elements that can be added to a design.
    protected $design;
    protected $elementlist; // Elements already in this dsign.


    public function __construct($pdfcertificate, $courseid, $template, $elements, $design,
            $elementlist) {

        $this->pdfcertificate = $pdfcertificate;
        $this->courseid = $courseid;
        $this->template = $template;
        $this->elements = $elements;
        $this->design = $design;
        $this->elementlist = $elementlist;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $data = new stdClass();

        $data->designname = $this->design->name;
        $data->description = $this->design->description;
        // Editing area dimensions in px.
        $data->height = $this->pdfcertificate->height;
        $data->width = $this->pdfcertificate->width;
        $data->baseimageurl = $this->template->baseimageurl;

        // Set up the available element list.
        $data->elements = [];
        foreach($this->elements as $element) {
            $listelements = [];
            $listelements['name'] = $element->name;
            $listelements['url'] = new moodle_url('addelement.php', ['id' => $element->id]);
            $data->elements[] = $listelements;
        }

        return $data;
    }
}