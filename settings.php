<?php
/**
 * Settings for the Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // 1. Methodos Server URL setting
    $settings->add(new admin_setting_configtext(
        'mod_methodos/methodosurl',
        get_string('methodosurl', 'mod_methodos'),
        get_string('methodosurl_desc', 'mod_methodos'),
        'http://localhost:8000',
        PARAM_URL
    ));

    // Initialize integration details
    $clientid = '-';
    $deploymentid = '-';
    $errorMsg = '';

    // Check if the lti_types table exists before querying to prevent breaking the install process.
    try {
        global $DB;
        $dbman = $DB->get_manager();
        if ($dbman->table_exists('lti_types')) {
            $ltitype = \mod_methodos\local\lti_helper::get_or_create_lti_type();
            if ($ltitype) {
                $clientid = $ltitype->clientid;
                $deploymentid = $ltitype->id;
            }
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }

    $issuer = $CFG->wwwroot;
    $loginurl = $CFG->wwwroot . '/mod/lti/auth.php';
    $redirectionurl = $CFG->wwwroot . '/mod/lti/launch.php';
    $keyseturl = $CFG->wwwroot . '/mod/lti/certs.php';

    // Render a premium administrative card to present the LTI credentials
    $desc = html_writer::start_tag('div', array(
        'class' => 'card p-4 my-3',
        'style' => 'background: #0f172a; color: #f8fafc; border: 1px solid #1e293b; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);'
    ));

    $desc .= html_writer::tag('h4', get_string('admin_integration_settings', 'mod_methodos'), array(
        'style' => 'color: #38bdf8; font-weight: 600; margin-top: 0; margin-bottom: 12px;'
    ));

    $desc .= html_writer::tag('p', get_string('admin_integration_desc', 'mod_methodos'), array(
        'style' => 'color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 20px;'
    ));

    if (!empty($errorMsg)) {
        $desc .= html_writer::tag('div', 'Configuration warning: ' . s($errorMsg), array(
            'class' => 'alert alert-warning',
            'style' => 'background-color: #78350f; color: #fef3c7; border: 1px solid #92400e; border-radius: 6px; padding: 12px; margin-bottom: 15px;'
        ));
    }

    $desc .= html_writer::start_tag('table', array(
        'class' => 'table table-bordered',
        'style' => 'margin-top: 15px; background-color: #1e293b; color: #f8fafc; border-color: #334155; font-family: monospace; font-size: 13px;'
    ));
    $desc .= html_writer::start_tag('tbody');

    // Issuer URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('moodle_issuer', 'mod_methodos') . '</strong>', array('style' => 'width: 35%; border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', s($issuer), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Client ID
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('clientid', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', empty($clientid) ? '<em>Generating during first save...</em>' : html_writer::tag('span', s($clientid), array(
        'style' => 'font-size: 13px; font-weight: 600; padding: 4px 8px; border-radius: 4px; background-color: #0d9488; color: #ffffff; display: inline-block;'
    )), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Deployment ID
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('deploymentid', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', empty($deploymentid) ? '-' : html_writer::tag('span', s($deploymentid), array(
        'style' => 'font-size: 13px; font-weight: 600; padding: 4px 8px; border-radius: 4px; background-color: #4f46e5; color: #ffffff; display: inline-block;'
    )), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Login URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('login_initiation_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', s($loginurl), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Redirection URI
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('target_link_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', s($redirectionurl), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // JWKS Keyset URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('jwks_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155; color: #94a3b8;'));
    $desc .= html_writer::tag('td', s($keyseturl), array('style' => 'border-color: #334155; color: #f8fafc; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    $desc .= html_writer::end_tag('tbody');
    $desc .= html_writer::end_tag('table');
    $desc .= html_writer::end_tag('div');

    $settings->add(new admin_setting_heading(
        'mod_methodos/integration_details',
        '',
        $desc
    ));
}
