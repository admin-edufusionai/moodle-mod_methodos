<?php
/**
 * Defines backup_methodos_activity_task class
 *
 * @package     mod_methodos
 * @category    backup
 * @copyright   2026 Methodos Peer Review
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/methodos/backup/moodle2/backup_methodos_stepslib.php');

/**
 * Provides all the settings and steps to perform one complete backup of the activity
 */
class backup_methodos_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // Methodos only has one structure step.
        $this->add_step(new backup_methodos_activity_structure_step('methodos_structure', 'methodos.xml'));
    }

    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     *
     * @param string $content
     * @return string
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, "/");

        // Link to the list of methodos instances.
        $search = "/(" . $base . "\/mod\/methodos\/index\.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@METHODOSINDEX*$2@$', $content);

        // Link to methodos view by moduleid.
        $search = "/(" . $base . "\/mod\/methodos\/view\.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@METHODOSVIEWBYID*$2@$', $content);

        return $content;
    }
}
