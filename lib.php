<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * lib.php.
 *
 * @package     tool_inplacetranslate
 * @copyright   2021 Devlion <info@devlion.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/admin/tool/inplacetranslate/locallib.php');

function tool_inplacetranslate_before_footer() {
    global $PAGE, $OUTPUT, $CFG;

    $enabled = get_config('tool_inplacetranslate', 'enabled');

    $adminandedit = is_admin_and_page_edit();

    if ($adminandedit && $enabled) {
        if (isset($CFG->langstrings)) {

            // Hack to add self strings.
            $selfstrings = [
                    'searchandtranslatethewords',
                    'search',
                    'setnewtranslate',
                    'availablelangs',
                    'original',
                    'cancel',
                    'refresh',
                    'stringwasupdated',
                    'string_updated',
                    'stringwasupdated',
                    'stringwasupdated',
            ];
            foreach ($selfstrings as $string) {
                $result = get_string($string, 'tool_inplacetranslate');
                $fullstringid = $string . '/' . 'tool_inplacetranslate';
                $CFG->langstrings[$fullstringid] = [
                        'identifier' => $string,
                        'component' => 'tool_inplacetranslate',
                        'text' => $result,
                ];
            }

            return $OUTPUT->render_from_template('tool_inplacetranslate/stringmenu',
                    ['langstrings' => array_values($CFG->langstrings),
                    ]);
        }
    }
    return '';
}

function tool_inplacetranslate_after_config() {
    global $CFG;

    $enabled = get_config('tool_inplacetranslate', 'enabled');

    $adminandedit = is_admin_and_page_edit();

    if ($adminandedit && $enabled) {
        tool_inplacetranslate\custom_string_manager::hook();
    }

}
