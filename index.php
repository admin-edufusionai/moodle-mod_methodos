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
 * Index page for Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review <support@methodos.edufusionai.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT); // Course ID.

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_login($course);

$context = context_course::instance($course->id);

$PAGE->set_url('/mod/methodos/index.php', array('id' => $id));
$PAGE->set_title(get_string('modulenameplural', 'mod_methodos'));
$PAGE->set_heading($course->fullname);
$PAGE->set_context($context);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_methodos'));

if (!$methodoses = get_all_instances_in_course('methodos', $course)) {
    notice(get_string('thereareno', 'moodle', get_string('modulenameplural', 'mod_methodos')), new moodle_url('/course/view.php', array('id' => $course->id)));
    exit();
}

$table = new html_table();
$table->head = array(get_string('name'));
$table->align = array('left');

foreach ($methodoses as $methodos) {
    $url = new moodle_url('/mod/methodos/view.php', array('id' => $methodos->coursemodule));
    $link = html_writer::link($url, format_string($methodos->name));
    $table->data[] = array($link);
}

echo html_writer::table($table);
echo $OUTPUT->footer();
