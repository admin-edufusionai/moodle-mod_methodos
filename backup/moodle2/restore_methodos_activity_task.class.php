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
 * Restore activity task for the Methodos module.
 *
 * @package    mod_methodos
 * @category   backup
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Provides all the settings and steps to perform one complete restore of a Methodos activity.
 */
class restore_methodos_activity_task extends restore_activity_task {

    /**
     * No specific settings for this activity.
     */
    protected function define_my_settings() {
        // No specific settings.
    }

    /**
     * Defines a restore step to restore the methodos instance from the XML backup.
     */
    protected function define_my_steps() {
        $this->add_step(new restore_methodos_activity_structure_step('methodos_structure', 'methodos.xml'));
    }

    /**
     * Defines the contents in the activity that must be processed by the link decoder.
     *
     * @return array List of items to decode.
     */
    static public function define_decode_contents() {
        $contents = array();
        $contents[] = new restore_decode_content('methodos', array('intro'), 'methodos');
        return $contents;
    }

    /**
     * Defines the decoding rules for links belonging to the activity to be executed by the link decoder.
     *
     * @return array List of decoding rules.
     */
    static public function define_decode_rules() {
        $rules = array();
        $rules[] = new restore_decode_rule('METODOSVIEWBYID', '/mod/methodos/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('METODOSINDEX', '/mod/methodos/index.php?id=$1', 'course');
        return $rules;
    }

    /**
     * Defines the restore log rules that will be applied to restore the Methodos activity logs.
     *
     * @return array List of log rules.
     */
    static public function define_restore_log_rules() {
        $rules = array();
        $rules[] = new restore_log_rule('methodos', 'view', 'view.php?id={course_module}', '{methodos}');
        return $rules;
    }

    /**
     * Defines the restore log rules that will be applied to restore the course logs.
     *
     * @return array List of log rules.
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();
        $rules[] = new restore_log_rule('methodos', 'view all', 'index.php?id={course}', null);
        return $rules;
    }
}
