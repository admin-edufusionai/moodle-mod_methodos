<?php
/**
 * Event triggered when a Methodos activity is viewed.
 *
 * @package    mod_methodos
 * @copyright  2026 Methodos Peer Review
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_methodos\event;

defined('MOODLE_INTERNAL') || die();

class course_module_viewed extends \core\event\course_module_viewed {

    /**
     * Initializes the event.
     */
    protected function init() {
        $this->data['objecttable'] = 'methodos';
        parent::init();
    }
}
