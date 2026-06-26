# Methodos Peer Review Activity Module for Moodle

Methodos is an AI-augmented peer review and mentorship platform tailored for postgraduate scholars (Master's, PhD, and Postdoc). This activity module plugin (`mod_methodos`) integrates Moodle courses securely with the Methodos Postgraduate Peer Review Platform using LTI 1.3 Advantage.

---

## Requirements

- **Moodle**: 4.5.0 LTS or higher.
- **PHP**: 8.1 or higher.
- **Methodos Platform**: An active tenant instance of the Methodos platform (local MVP or production server).

---

## Installation

1. Clone or extract this directory into your Moodle site's activities folder as `mod/methodos`:
   ```bash
   git clone https://github.com/admin-edufusionai/moodle-mod_methodos.git mod/methodos
   ```
   Or place the zip file contents inside `mod/methodos`.

2. Log in to your Moodle site as an Administrator.
3. Navigate to **Site Administration > Notifications**.
4. Moodle will detect the new plugin. Click **Upgrade Moodle database now** to perform the installation.

---

## Configuration

1. In Moodle, navigate to **Site Administration > Plugins > Activity modules > Methodos Peer Review**.
2. Specify your **Methodos Server URL** (e.g. `https://methodos.edufusionai.co.za` for production or `http://localhost:8000` for local development) and click **Save changes**.
3. Moodle will automatically register the preconfigured LTI 1.3 tool and display the generated connection details on the settings page:
   - **LMS Issuer URL (iss)**
   - **LTI Developer Client ID**
   - **LTI Deployment ID**
   - **Initiate Login URL**
   - **Tool URL / Redirection URI**
   - **JWKS Keyset URL**
4. Copy these connection credentials and enter them into your **Methodos Admin Integrations portal** under the institutional configuration settings.

---

## Usage

1. In any Moodle course, turn **Editing Mode** on and click **Add an activity or resource**.
2. Select **Methodos Peer Review** from the activity chooser.
3. Provide a name and description. Optionally, input a specific **Methodos Project Token** to route students directly to a specific review assignment.
4. Save and display. 
5. When a student or teacher clicks the activity, they will be securely logged into the Methodos platform via LTI 1.3 OIDC and redirect bindings, auto-provisioning their accounts if they are whitelisted by email domain.

---

## Privacy Subsystem

This plugin implements Moodle's core Privacy Subsystem (`\core_privacy\local\metadata\null_provider`). It acts as an LTI consumer and transmits user identity data (such as username, full name, and email address) securely to the external Methodos platform to authenticate users and associate them with their submissions and reports.

---

## License

GNU GPL v3 or later.
