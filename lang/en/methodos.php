<?php
/**
 * English strings for Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Methodos Peer Review';
$string['modulename'] = 'Methodos Peer Review';
$string['modulenameplural'] = 'Methodos Peer Reviews';
$string['modulename_help'] = 'Methodos is an AI-augmented peer review and mentorship platform. It structures postgraduate academic feedback using cognitive empathy, Socratic questioning, and automated LLM alignment analysis.';
$string['pluginadministration'] = 'Methodos Peer Review Administration';

// Settings
$string['methodosurl'] = 'Methodos Server URL';
$string['methodosurl_desc'] = 'The API URL where the Methodos application is running (e.g., http://localhost:8000 or https://methodos.ac.za). Do not include a trailing slash.';
$string['projectid'] = 'Methodos Project Token / ID';
$string['projectid_help'] = 'Enter the specific Project Token or Course Workspace ID from the Methodos platform. If left blank, this activity will resolve to the default course workspace or let users choose.';

// Admin dashboard guide
$string['admin_integration_settings'] = 'Methodos LTI 1.3 Connection Settings';
$string['admin_integration_desc'] = 'To connect this Moodle site with your Methodos platform instance, first specify the Methodos URL below and click Save. The plugin will automatically register Methodos as a secure LTI 1.3 external tool in Moodle and display the generated credentials below. Copy these credentials into your Methodos Admin Integrations portal.';
$string['clientid'] = 'LTI Developer Client ID';
$string['clientid_desc'] = 'Copy this Client ID and paste it in the Methodos Institutional Integrations form.';
$string['deploymentid'] = 'LTI Deployment ID';
$string['deploymentid_desc'] = 'Copy this Deployment ID and paste it in the Methodos Institutional Integrations form.';
$string['public_key'] = 'Platform Public Key (PEM)';
$string['public_key_desc'] = 'Moodle uses its global platform keys to sign launches. Direct Methodos to fetch Moodle\'s public key from the JWKS Keyset URL below.';

// Keyset & LTI details
$string['lti_endpoints'] = 'Moodle LTI 1.3 Endpoints';
$string['lti_endpoints_desc'] = 'Provide these URLs to the Methodos administrator to configure the connection:';
$string['target_link_uri'] = 'Tool URL / Redirection URI';
$string['login_initiation_uri'] = 'Initiate Login URL';
$string['jwks_uri'] = 'JWKS Keyset URL';
$string['moodle_issuer'] = 'LMS Issuer URL (iss)';

// Warnings and Errors
$string['not_configured_title'] = 'Integration Settings Pending';
$string['not_configured_warning'] = 'The Methodos Integration has not been fully configured yet. Please ask your Moodle Administrator to set the Methodos Server URL under Site Administration -> Plugins -> Activity modules -> Methodos Peer Review.';
$string['configwarning'] = 'Configuration warning: {$a}';
$string['privacy:metadata'] = 'The Methodos Peer Review plugin acts as an LTI consumer and transmits user identity data (such as username, full name, and email address) securely to the external Methodos platform to authenticate the student or teacher and associate them with their academic submission files and peer review reports.';

// Capability strings (required by db/access.php).
$string['methodos:addinstance'] = 'Add a new Methodos Peer Review activity';
$string['methodos:view'] = 'View Methodos Peer Review activity';
