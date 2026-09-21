<?php
/**
 * Define all the restore steps that will be used by the restore_methodos_activity_task
 *
 * @package     mod_methodos
 * @category    backup
 * @copyright   2026 Methodos Peer Review
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Structure step to restore one methodos activity
 */
class restore_methodos_activity_structure_step extends restore_activity_structure_step {

    /**
     * Structure step definitions
     *
     * @return array
     */
    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('methodos', '/activity/methodos');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process a methodos restore
     *
     * @param object $data The data in object form
     */
    protected function process_methodos($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Ensure the preconfigured LTI tool is ready
        try {
            $ltitype = \mod_methodos\local\lti_helper::get_or_create_lti_type();
            $ltitypeid = $ltitype->id;
        } catch (Exception $e) {
            $ltitypeid = 0;
        }

        // Recreate shadow LTI record for restored course
        $lti = new stdClass();
        $lti->course = $data->course;
        $lti->name = $data->name;
        $lti->intro = isset($data->intro) ? $data->intro : '';
        $lti->introformat = isset($data->introformat) ? $data->introformat : FORMAT_HTML;
        $lti->timecreated = time();
        $lti->timemodified = time();
        $lti->typeid = $ltitypeid;
        $lti->toolurl = '';
        $lti->securetoolurl = '';
        $lti->instructorchoicesendname = 1;
        $lti->instructorchoicesendemailaddr = 1;
        $lti->instructorchoiceacceptgrades = 1;
        $lti->grade = 100;
        $lti->launchcontainer = 3;
        $lti->resourcekey = '';
        $lti->password = '';
        $lti->debuglaunch = 0;
        $lti->showtitlelaunch = 0;
        $lti->showdescriptionlaunch = 0;
        $lti->servicesintro = '';
        $lti->servicesintroformat = 0;

        $projectid = isset($data->projectid) ? trim($data->projectid) : '';
        $lti->customparameters = "methodos_project_id=" . $projectid . "\n";

        $ltiinstanceid = $DB->insert_record('lti', $lti);
        $data->ltiinstanceid = $ltiinstanceid;

        // Insert the methodos record.
        $newitemid = $DB->insert_record('methodos', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Post-execution actions
     */
    protected function after_execute() {
        // Add methodos related files, no need to match by itemname (just intro).
        $this->add_related_files('mod_methodos', 'intro', null);
    }
}
