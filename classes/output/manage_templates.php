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
 * pdfcertificate: Create a new manage templates renderable object
 *
 * @param object pdfcertificate - instance of pdfcertificate.
 * @param int id - course module id.
 * @param object moodle_url the url of the base certificate.
 * @copyright  2020 Richard Jones <richardnz@outlook.com>
 */

class manage_templates implements renderable, templatable {

    protected $pdfcertificate;
    protected $course;
    protected $templates;

    public function __construct($pdfcertificate, $course, $templates) {

        $this->pdfcertificate = $pdfcertificate;
        $this->course = $course;
        $this->templates = $templates;
    }
    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {

        $table = new stdClass();
        $baseparams = ['courseid' => $this->course->id, 'pdfcertificateid' => $this->pdfcertificate->id];
        $table->add_template = new moodle_url('edit_template.php', $baseparams);

        // Get the table headers.
        $table->headers = self::get_headers();

        // Set up the table rows.
        foreach($this->templates as $template) {
            $data = array();

            $data['id'] = $template->id;
            $data['name'] = $template->name;
            $data['description'] = $template->description;
            $data['height'] = $template->height;
            $data['width'] = $template->width;
            $data['baseimageurl'] = $template->baseimageurl;

            // Set up the action links.
            $actions = array();

            $url = new moodle_url('edit_template.php', $baseparams);
            $icon = ['icon' => 't/edit', 'component' => 'core', 'alt' => get_string('edittemplate', 'mod_pdfcertificate')];
            $actions['edit'] = ['link' => $url->out(false, ['templateid' => $template->id]), 'icon' => $icon];
            $url = new moodle_url('delete_template.php', $baseparams);
            $icon = ['icon' => 't/block', 'component' => 'core', 'alt' => get_string('edittemplate', 'mod_pdfcertificate')];
            $actions['delete'] = ['link' => $url->out(false, ['templateid' => $template->id]), 'icon' => $icon];

            $data['actions'] = $actions;

            $table->tabledata[] = $data;
        }
        // Navigation tabs.
        $url = new moodle_url('view.php', ['n' => $this->pdfcertificate->id]);
        $table->viewurl = $url->out(false);
        $url = new moodle_url('manage_templates.php', ['courseid' => $this->course->id,
                'pdfcertificateid' => $this->pdfcertificate->id]);
        $table->templatesurl = $url->out(false);

        return $table;
    }
    /**
     * Get the headers for the templates table
     */
    private function get_headers() {

        return [get_string('id', 'mod_pdfcertificate'),
                get_string('name', 'mod_pdfcertificate'),
                get_string('description', 'mod_pdfcertificate'),
                get_string('height', 'mod_pdfcertificate'),
                get_string('width', 'mod_pdfcertificate'),
                get_string('baseimageurl', 'mod_pdfcertificate'),
                get_string('actions', 'mod_pdfcertificate'),
               ];
    }
}
