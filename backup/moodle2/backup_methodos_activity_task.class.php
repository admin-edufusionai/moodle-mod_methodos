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
 * Backup activity task for the Methodos module.
 *
 * @package    mod_methodos
 * @category   backup
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/backup/moodle2/backup_mod_lesson_activity_task.class.php');

/**
 * Provides all the settings and steps to perform one complete backup of the activity.
 */
class backup_methodos_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity.
     */
    protected function define_my_settings() {
        // No specific settings.
    }

    /**
     * Defines a backup step to store the instance data in the methodos.xml file.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_methodos_activity_structure_step('methodos_structure', 'methodos.xml'));
    }

    /**
     * Encodes URLs to the Methodos module view.php script.
     *
     * @param string $content Content to encode.
     * @return string Encoded content.
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of methodos activities.
        $search = '/(' . $base . '\/mod\/methodos\/index\.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@METODOSINDEX*$2@$', $content);

        // Link to methodos view by module id.
        $search = '/(' . $base . '\/mod\/methodos\/view\.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@METODOSVIEWBYID*$2@$', $content);

        return $content;
    }
}
