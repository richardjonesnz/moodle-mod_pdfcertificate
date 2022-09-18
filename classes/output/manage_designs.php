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
 * Gives a list of existing designs
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
use mod_pdfcertificate\local\pdf_urls;

/**
 * pdfcertificate: Create a new manage designs renderable object
 *
 * @param object pdfcertificate - instance of pdfcertificate.
 * @param int id - course module id.
 * @param object moodle_url the url of the base certificate.
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 */

class manage_designs implements renderable, templatable {

    protected $pdfcertificateid;
    protected $courseid;
    protected $designs;

    public function __construct($pdfcertificateid, $courseid, $designs) {

        $this->pdfcertificateid = $pdfcertificateid;
        $this->courseid = $courseid;
        $this->designs = $designs;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $DB;

        $table = new stdClass();
        $baseparams = ['courseid' => $this->courseid, 'pdfcertificateid' => $this->pdfcertificateid];
        $table->add_design = new moodle_url('edit_design.php', $baseparams);

        // Get the table headers.
        $table->headers = self::get_headers();

        // Set up the table rows.
        foreach($this->designs as $design) {
            $data = array();

            $data['id'] = $design->id;
            $data['name'] = $design->name;
            $data['description'] = $design->description;
            $template = $DB->get_record('pdftemplates', ['id' => $design->templateid], '*', IGNORE_MISSING);
            $data['template'] = ($template == null) ? get_string('none', 'mod_pdf_certificate') : $template->name;

            // Set up the action links.
            $actions = array();

            $url = new moodle_url('edit_design.php', $baseparams);
            $icon = ['icon' => 't/edit', 'component' => 'core', 'alt' => get_string('edit', 'mod_pdfcertificate')];
            $actions['edit'] = ['link' => $url->out(false, ['designid' => $design->id]), 'icon' => $icon];
            $url = new moodle_url('delete_design.php', $baseparams);
            $icon = ['icon' => 't/block', 'component' => 'core', 'alt' => get_string('delete', 'mod_pdfcertificate')];
            $actions['delete'] = ['link' => $url->out(false, ['itemid' => $design->id, 'return' => 'design']),
                    'icon' => $icon];
            $url = new moodle_url('design_certificate.php', $baseparams);
            $icon = ['icon' => 't/index_drawer', 'component' => 'core', 'alt' => get_string('design', 'mod_pdfcertificate')];
            $actions['design'] = ['link' => $url->out(false, ['designid' => $design->id, 'return' => 'design']),
                    'icon' => $icon];

            $data['actions'] = $actions;

            $table->tabledata[] = $data;
        }
        // Navigation tabs.
        $table->urls = pdf_urls::get_urls($this->pdfcertificateid, $this->courseid);

        return $table;
    }
    /**
     * Get the headers for the designs table
     */
    private function get_headers() {

        return [get_string('id', 'mod_pdfcertificate'),
                get_string('name', 'mod_pdfcertificate'),
                get_string('description', 'mod_pdfcertificate'),
                get_string('templatename', 'mod_pdfcertificate'),
                get_string('actions', 'mod_pdfcertificate')
               ];
    }
}
