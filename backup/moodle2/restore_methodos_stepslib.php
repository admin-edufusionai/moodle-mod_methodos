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
 * Restore structure steps for the Methodos module.
 *
 * @package    mod_methodos
 * @category   backup
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defines the structure step that will restore the data for a Methodos activity from the backup XML.
 */
class restore_methodos_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines the structure of the restore XML.
     *
     * @return array List of restore path elements.
     */
    protected function define_structure() {
        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('methodos', '/activity/methodos');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Processes the methodos element from the backup XML.
     *
     * @param array $data The data read from the backup XML.
     */
    protected function process_methodos($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        // Insert the methodos record.
        $newitemid = $DB->insert_record('methodos', $data);

        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Adds post-restore actions.
     */
    protected function after_execute() {
        // Add methodos related files, no user info needed.
        $this->add_related_files('mod_methodos', 'intro', null);
    }
}
