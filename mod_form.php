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
 * Form for adding/editing Methodos activity instances.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_methodos_mod_form extends moodleform_mod {

    /**
     * Defines the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        // 1. General settings section
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Activity Name
        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Standard introduction/description editor
        $this->standard_intro_elements();

        // 2. Methodos Specific settings section
        $mform->addElement('header', 'methodossettings', get_string('pluginname', 'mod_methodos'));

        // Project/Workspace ID override
        $mform->addElement('text', 'projectid', get_string('projectid', 'mod_methodos'), array('size' => '64'));
        $mform->setType('projectid', PARAM_TEXT);
        $mform->addHelpButton('projectid', 'projectid', 'mod_methodos');

        // 3. Core Course Module fields (groups, grading, display etc.)
        $this->standard_coursemodule_elements();

        // Add Save/Cancel buttons
        $this->add_action_buttons();
    }
}
