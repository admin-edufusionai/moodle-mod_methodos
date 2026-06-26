<?php
/**
 * Privacy Subsystem implementation for mod_methodos.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_methodos\privacy;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Provider class for mod_methodos.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\null_provider {

    /**
     * Get the language string identifier explaining why this plugin does not store personal data.
     *
     * @return string The privacy reason language string identifier.
     */
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}
