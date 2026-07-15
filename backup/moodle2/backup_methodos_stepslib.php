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
 * Backup structure steps for the Methodos module.
 *
 * @package    mod_methodos
 * @category   backup
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defines the complete structure for backup of a Methodos activity.
 */
class backup_methodos_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the backup for the methodos activity.
     *
     * @return backup_nested_element The root element of the backup structure.
     */
    protected function define_structure() {
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $methodos = new backup_nested_element('methodos', array('id'), array(
            'name',
            'intro',
            'introformat',
            'timecreated',
            'timemodified',
        ));

        // Build the tree (no children for this LTI-proxy module).

        // Define sources.
        $methodos->set_source_table('methodos', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        $methodos->annotate_ids('user', 'userid');

        // Define file annotations.
        $methodos->annotate_files('mod_methodos', 'intro', null);

        // Return the root element (methodos), wrapped into standard activity structure.
        return $this->prepare_activity_structure($methodos);
    }
}
