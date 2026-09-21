<?php
/**
 * Define all the backup steps that will be used by the backup_methodos_activity_task
 *
 * @package     mod_methodos
 * @category    backup
 * @copyright   2026 Methodos Peer Review
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete methodos structure for backup, with file and id annotations
 */
class backup_methodos_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define the structure of the backup element
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Define root activity element and its fields matching install.xml
        $methodos = new backup_nested_element('methodos', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'ltiinstanceid', 'projectid',
            'timecreated', 'timemodified'
        ));

        // Define source table
        $methodos->set_source_table('methodos', array('id' => backup::VAR_ACTIVITYID));

        // Define file annotations
        $methodos->annotate_files('mod_methodos', 'intro', null);

        // Return the root element wrapped into standard activity structure
        return $this->prepare_activity_structure($methodos);
    }
}
