<?php
/**
 * Helper class for managing programmatic LTI tool registration in Moodle.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_methodos\local;

defined('MOODLE_INTERNAL') || die();

class lti_helper {

    /**
     * Ensures the Methodos LTI 1.3 tool is registered programmatically.
     *
     * @return \stdClass The registered tool type database record.
     */
    public static function get_or_create_lti_type() {
        global $DB, $CFG;

        require_once($CFG->dirroot . '/mod/lti/locallib.php');

        $serverurl = get_config('mod_methodos', 'methodosurl');
        if (empty($serverurl)) {
            $serverurl = 'http://localhost:8000'; // Default fallback for local sandbox
        }

        // Host domain for lookup
        $parsedurl = parse_url($serverurl);
        $domain = isset($parsedurl['host']) ? $parsedurl['host'] : 'localhost';
        if (isset($parsedurl['port'])) {
            $domain .= ':' . $parsedurl['port'];
        }

        // 1. Check if a preconfigured tool type for Methodos already exists
        $type = $DB->get_record('lti_types', array('name' => 'Methodos Peer Review', 'course' => 1), '*', IGNORE_MULTIPLE);

        $launchurl = rtrim($serverurl, '/') . '/api/lti/launch';
        $loginurl = rtrim($serverurl, '/') . '/api/lti/login';
        $jwksurl = rtrim($serverurl, '/') . '/api/lti/jwks';

        $config = new \stdClass();
        $config->authenticationurl = $loginurl;
        $config->publickeyseturl = $jwksurl;
        $config->redirectionuris = $launchurl;
        $config->keytype = 'jwk';
        $config->sendname = 1; // LTI_SETTING_ALWAYS (Always send name)
        $config->sendemailaddr = 1; // LTI_SETTING_ALWAYS (Always send email)
        $config->acceptgrades = 1; // LTI_SETTING_ALWAYS (Always accept grades)
        $config->customparameters = "methodos_project_id=\$CourseSection.id\n";

        if ($type) {
            // Check if the URL has changed and update if necessary
            if ($type->baseurl !== $launchurl) {
                $type->baseurl = $launchurl;
                $type->tooldomain = $domain;
                $type->timemodified = time();
                $DB->update_record('lti_types', $type);
            }

            // Always verify and synchronize type config records to keep them up to date
            foreach ($config as $name => $value) {
                $configrecord = $DB->get_record('lti_types_config', array('typeid' => $type->id, 'name' => $name));
                if ($configrecord) {
                    if ($configrecord->value !== $value) {
                        $configrecord->value = $value;
                        $DB->update_record('lti_types_config', $configrecord);
                    }
                } else {
                    $configrecord = new \stdClass();
                    $configrecord->typeid = $type->id;
                    $configrecord->name = $name;
                    $configrecord->value = $value;
                    $DB->insert_record('lti_types_config', $configrecord);
                }
            }
            return $type;
        }

        // 2. Create the LTI tool type programmatically
        $type = new \stdClass();
        $type->name = 'Methodos Peer Review';
        $type->baseurl = $launchurl;
        $type->tooldomain = $domain;
        $type->state = 1; // LTI_TOOL_STATE_CONFIGURED
        $type->ltiversion = '1.3.0';
        $type->course = 1; // Site level
        $type->clientid = ''; // Left blank so Moodle generates it automatically
        $type->description = 'Connects course activities securely with the Methodos postgraduate peer review platform.';
        $type->timecreated = time();
        $type->timemodified = time();

        // Add the tool type using Moodle's core helper function
        $typeid = lti_add_type($type, $config);

        // Fetch and return the fully populated record (containing Moodle's generated clientid)
        return $DB->get_record('lti_types', array('id' => $typeid), '*', MUST_EXIST);
    }
}
