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
 * Library of functions and callbacks for the Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds a new instance of the Methodos activity.
 *
 * @param stdClass $methodos The form data submitted.
 * @param mod_methodos_mod_form $mform The form object.
 * @return int The new activity instance ID.
 */
function methodos_add_instance($methodos, $mform = null) {
    global $DB;

    $methodos->timecreated = time();
    $methodos->timemodified = time();

    // 1. Ensure the preconfigured LTI 1.3 tool is created
    try {
        $ltitype = \mod_methodos\local\lti_helper::get_or_create_lti_type();
        $ltitypeid = $ltitype->id;
    } catch (Exception $e) {
        $ltitypeid = 0;
    }

    // 2. Create the shadow LTI record for Moodle's core LTI engine
    $lti = new stdClass();
    $lti->course = $methodos->course;
    $lti->name = $methodos->name;
    $lti->intro = isset($methodos->intro) ? $methodos->intro : '';
    $lti->introformat = isset($methodos->introformat) ? $methodos->introformat : FORMAT_HTML;
    $lti->timecreated = time();
    $lti->timemodified = time();
    $lti->typeid = $ltitypeid;
    $lti->toolurl = '';
    $lti->securetoolurl = '';
    $lti->instructorchoicesendname = 1;      // Always send name
    $lti->instructorchoicesendemailaddr = 1;  // Always send email
    $lti->instructorchoiceacceptgrades = 1;   // Accept grades
    $lti->grade = isset($methodos->grade) ? $methodos->grade : 0;
    $lti->launchcontainer = 3;                // LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS
    $lti->resourcekey = '';
    $lti->password = '';
    $lti->debuglaunch = 0;
    $lti->showtitlelaunch = 0;
    $lti->showdescriptionlaunch = 0;
    $lti->servicesintro = '';
    $lti->servicesintroformat = 0;
    
    // Inject the custom project ID so it's passed as a claim in the LTI 1.3 token
    $projectid = isset($methodos->projectid) ? trim($methodos->projectid) : '';
    $lti->customparameters = "methodos_project_id=" . $projectid . "\n";

    // Insert the shadow LTI record
    $ltiinstanceid = $DB->insert_record('lti', $lti);

    // 3. Save the Methodos instance referencing the shadow LTI instance
    $methodos->ltiinstanceid = $ltiinstanceid;
    $methodos->id = $DB->insert_record('methodos', $methodos);

    return $methodos->id;
}

/**
 * Updates an existing instance of the Methodos activity.
 *
 * @param stdClass $methodos The form data submitted.
 * @param mod_methodos_mod_form $mform The form object.
 * @return bool True on success.
 */
function methodos_update_instance($methodos, $mform = null) {
    global $DB;

    $methodos->timemodified = time();
    $methodos->id = $methodos->instance;

    // Retrieve existing instance
    $existing = $DB->get_record('methodos', array('id' => $methodos->id), '*', MUST_EXIST);
    $methodos->ltiinstanceid = $existing->ltiinstanceid;

    // Update the Methodos record
    $DB->update_record('methodos', $methodos);

    // Update the corresponding shadow LTI record
    if (!empty($methodos->ltiinstanceid)) {
        $lti = $DB->get_record('lti', array('id' => $methodos->ltiinstanceid));
        if ($lti) {
            $lti->name = $methodos->name;
            $lti->intro = isset($methodos->intro) ? $methodos->intro : '';
            $lti->introformat = isset($methodos->introformat) ? $methodos->introformat : FORMAT_HTML;
            $lti->grade = isset($methodos->grade) ? $methodos->grade : 0;
            $lti->timemodified = time();

            // Update custom project ID parameters
            $projectid = isset($methodos->projectid) ? trim($methodos->projectid) : '';
            $lti->customparameters = "methodos_project_id=" . $projectid . "\n";

            $DB->update_record('lti', $lti);
        }
    }

    return true;
}

/**
 * Deletes an instance of the Methodos activity.
 *
 * @param int $id The activity instance ID.
 * @return bool True on success.
 */
function methodos_delete_instance($id) {
    global $DB;

    $methodos = $DB->get_record('methodos', array('id' => $id));
    if (!$methodos) {
        return false;
    }

    // Delete shadow LTI record first
    if (!empty($methodos->ltiinstanceid)) {
        $DB->delete_records('lti', array('id' => $methodos->ltiinstanceid));
    }

    // Delete Methodos record
    $DB->delete_records('methodos', array('id' => $id));

    return true;
}

/**
 * Return list of features supported by the module.
 *
 * @param string $feature FEATURE_xx constant.
 * @return mixed True if supported, null if not.
 */
function methodos_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}
