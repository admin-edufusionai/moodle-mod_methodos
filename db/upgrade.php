<?php
/**
 * Upgrade code for the Methodos activity module.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Performs database upgrade tasks for the Methodos module.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool True on success.
 */
function xmldb_methodos_upgrade($oldversion) {
    global $CFG, $DB;

    // Upgrade scripts will be implemented here for future schema updates.

    return true;
}
