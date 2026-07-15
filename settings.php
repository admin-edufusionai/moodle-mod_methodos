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
 * Settings for the Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
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

    // Initialize integration details.
    $clientid = '-';
    $deploymentid = '-';
    $errormsg = '';

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
        $errormsg = $e->getMessage();
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

    if (!empty($errormsg)) {
        $desc .= html_writer::tag('div', get_string('configwarning', 'mod_methodos', s($errormsg)), array(
            'class' => 'alert alert-warning',
            'style' => 'background-color: #78350f; color: #fef3c7; border: 1px solid #92400e; border-radius: 6px; padding: 12px; margin-bottom: 15px;'
        ));
    }

    // Helper function to render copyable credentials
    $make_copyable = function($value) {
        $jsvalue = addslashes($value);
        $btn = html_writer::tag('button', 'Copy', array(
            'class' => 'btn btn-secondary btn-sm',
            'style' => 'padding: 1px 6px; font-size: 10px; cursor: pointer; float: right; background-color: #475569 !important; border-color: #475569 !important; color: #ffffff !important; font-family: sans-serif;',
            'onclick' => "navigator.clipboard.writeText('{$jsvalue}').then(() => {
                const btn = this;
                const oldText = btn.innerText;
                btn.innerText = 'Copied!';
                btn.style.backgroundColor = '#10b981';
                setTimeout(() => {
                    btn.innerText = oldText;
                    btn.style.backgroundColor = '#475569';
                }, 1500);
            }); return false;"
        ));
        return html_writer::tag('span', s($value), array('style' => 'vertical-align: middle;')) . $btn;
    };

    $desc .= html_writer::start_tag('table', array(
        'class' => 'table table-bordered',
        'style' => 'margin-top: 15px; background-color: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important; font-family: monospace; font-size: 13px;'
    ));
    $desc .= html_writer::start_tag('tbody');

    // Issuer URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('moodle_issuer', 'mod_methodos') . '</strong>', array('style' => 'width: 35%; border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $make_copyable($issuer), array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Client ID
    $clientid_html = empty($clientid) || $clientid === '-' ? '<em>Generating during first save...</em>' : html_writer::tag('span', s($clientid), array(
        'style' => 'font-size: 13px; font-weight: 600; padding: 4px 8px; border-radius: 4px; background-color: #0d9488 !important; color: #ffffff !important; display: inline-block; vertical-align: middle;'
    ));
    if (!empty($clientid) && $clientid !== '-') {
        $jsvalue = addslashes($clientid);
        $clientid_html .= html_writer::tag('button', 'Copy', array(
            'class' => 'btn btn-secondary btn-sm',
            'style' => 'padding: 1px 6px; font-size: 10px; cursor: pointer; float: right; background-color: #475569 !important; border-color: #475569 !important; color: #ffffff !important; font-family: sans-serif;',
            'onclick' => "navigator.clipboard.writeText('{$jsvalue}').then(() => {
                const btn = this;
                const oldText = btn.innerText;
                btn.innerText = 'Copied!';
                btn.style.backgroundColor = '#10b981';
                setTimeout(() => {
                    btn.innerText = oldText;
                    btn.style.backgroundColor = '#475569';
                }, 1500);
            }); return false;"
        ));
    }

    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('clientid', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $clientid_html, array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Deployment ID
    $deploymentid_html = empty($deploymentid) || $deploymentid === '-' ? '-' : html_writer::tag('span', s($deploymentid), array(
        'style' => 'font-size: 13px; font-weight: 600; padding: 4px 8px; border-radius: 4px; background-color: #4f46e5 !important; color: #ffffff !important; display: inline-block; vertical-align: middle;'
    ));
    if (!empty($deploymentid) && $deploymentid !== '-') {
        $jsvalue = addslashes($deploymentid);
        $deploymentid_html .= html_writer::tag('button', 'Copy', array(
            'class' => 'btn btn-secondary btn-sm',
            'style' => 'padding: 1px 6px; font-size: 10px; cursor: pointer; float: right; background-color: #475569 !important; border-color: #475569 !important; color: #ffffff !important; font-family: sans-serif;',
            'onclick' => "navigator.clipboard.writeText('{$jsvalue}').then(() => {
                const btn = this;
                const oldText = btn.innerText;
                btn.innerText = 'Copied!';
                btn.style.backgroundColor = '#10b981';
                setTimeout(() => {
                    btn.innerText = oldText;
                    btn.style.backgroundColor = '#475569';
                }, 1500);
            }); return false;"
        ));
    }

    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('deploymentid', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $deploymentid_html, array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Login URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('login_initiation_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $make_copyable($loginurl), array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // Redirection URI
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('target_link_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $make_copyable($redirectionurl), array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
    $desc .= html_writer::end_tag('tr');

    // JWKS Keyset URL
    $desc .= html_writer::start_tag('tr', array('style' => 'border-color: #334155 !important;'));
    $desc .= html_writer::tag('td', '<strong>' . get_string('jwks_uri', 'mod_methodos') . '</strong>', array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #94a3b8 !important;'));
    $desc .= html_writer::tag('td', $make_copyable($keyseturl), array('style' => 'border-color: #334155 !important; background-color: #1e293b !important; color: #f8fafc !important; word-break: break-all;'));
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
