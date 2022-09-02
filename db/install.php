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
 * Provides code to be executed during the module installation
 *
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php.
 *
 * @package    mod_pdfcertificate
 * @copyright  2022 Richard Jones richardnz@outlook.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://github.com/moodlehq/moodle-mod_pdfcertificate
 * @see https://github.com/justinhunt/moodle-mod_pdfcertificate
 */

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_pdfcertificate_install() {
    global $DB;

    // Populate the pdf elements table with some standard elements.
    $names = ['site', 'coursename', 'date', 'username', 'teacher'];
    $descriptions = ['Moodle site name', 'Course fullname', 'Current Date',
            'User\'s full name', 'Teacher\'s full name'];
    $tables = ['course', 'course', 'none', 'user', 'user'];
    $fields = ['fullname', 'shortname', 'none', 'username', 'username'];

    for($i = 0; $i < count($names); $i++) {
        $items[] = new stdClass();
        $items[$i]->name = $names[$i];
        $items[$i]->description = $descriptions[$i];
        $items[$i]->type = 'dynamic';
        $items[$i]->mtable = $tables[$i];
        $items[$i]->mfield = $fields[$i];
        $items[$i]->timecreated = time();
    }
    $DB->insert_records('pdfelements', $items);
}

/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_pdfcertificate_install_recovery() {
}
