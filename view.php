<?php
/**
 * View page for Methodos activities. Launches the external peer review tool.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/lti/locallib.php');

$id = required_param('id', PARAM_INT); // Course module ID.

// 1. Resolve and validate course module context
$cm = get_coursemodule_from_id('methodos', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$methodos = $DB->get_record('methodos', array('id' => $cm->instance), '*', MUST_EXIST);

// Require authentication and verify view capability
require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/methodos:view', $context);

// 2. Validate configuration
$serverurl = get_config('mod_methodos', 'methodosurl');
if (empty($serverurl)) {
    // Show configuration warning if the site-wide URL is missing
    $PAGE->set_url('/mod/methodos/view.php', array('id' => $id));
    $PAGE->set_title(format_string($methodos->name));
    $PAGE->set_heading(format_string($course->fullname));
    
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('not_configured_title', 'mod_methodos'), 3);
    echo $OUTPUT->notification(get_string('not_configured_warning', 'mod_methodos'), 'warning');
    echo $OUTPUT->footer();
    exit();
}

// 3. Retrieve or dynamically rebuild the shadow LTI record
$lti = null;
if (!empty($methodos->ltiinstanceid)) {
    $lti = $DB->get_record('lti', array('id' => $methodos->ltiinstanceid));
}

if (!$lti) {
    // Fallback: Dynamically recreate the shadow LTI record if missing
    $ltitype = \mod_methodos\local\lti_helper::get_or_create_lti_type();
    
    $lti = new stdClass();
    $lti->course = $methodos->course;
    $lti->name = $methodos->name;
    $lti->intro = $methodos->intro;
    $lti->introformat = $methodos->introformat;
    $lti->timecreated = time();
    $lti->timemodified = time();
    $lti->typeid = $ltitype->id;
    $lti->toolurl = '';
    $lti->securetoolurl = '';
    $lti->instructorchoicesendname = 1;
    $lti->instructorchoicesendemailaddr = 1;
    $lti->instructorchoiceacceptgrades = 1;
    $lti->grade = 100;
    $lti->launchcontainer = 3; // Embed launch
    $lti->resourcekey = '';
    $lti->password = '';
    $lti->debuglaunch = 0;
    $lti->showtitlelaunch = 0;
    $lti->showdescriptionlaunch = 0;
    $lti->servicesintro = '';
    $lti->servicesintroformat = 0;
    
    $projectid = isset($methodos->projectid) ? trim($methodos->projectid) : '';
    $lti->customparameters = "methodos_project_id=" . $projectid . "\n";
    
    $lti->id = $DB->insert_record('lti', $lti);
    
    // Update reference in Methodos table
    $methodos->ltiinstanceid = $lti->id;
    $DB->update_record('methodos', $methodos);
}

// Bind current course module context to the LTI object for grading callback routing
$lti->cmid = $cm->id;

// 4. Trigger viewed event log
$params = array(
    'context' => $context,
    'objectid' => $methodos->id
);
$event = \mod_methodos\event\course_module_viewed::create($params);
$event->trigger();

// 5. Perform the secure LTI 1.3 launch redirect
lti_launch_tool($lti);
