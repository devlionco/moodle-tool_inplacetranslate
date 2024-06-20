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
 * Web service external functions and service definitions.
 *
 * @package    tool_inplacetranslate
 * @subpackage inplacetranslate
 * @copyright  2021 Devlionco <info@devlion.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// We defined the web service functions to install.
$functions = [
        'tool_inplacetranslate_translation_get_string' => [
                'classname' => 'tool_inplacetranslate_external',
                'methodname' => 'translation_get_string',
                'classpath' => '',
                'description' => 'Get string for translation',
                'type' => 'read',
                'ajax' => true,
                'capabilities' => '',
                'loginrequired' => true,
                'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ],
        'tool_inplacetranslate_translation_update_string' => [
                'classname' => 'tool_inplacetranslate_external',
                'methodname' => 'translation_update_string',
                'classpath' => '',
                'description' => 'Update translation',
                'type' => 'write',
                'ajax' => true,
                'capabilities' => '',
                'loginrequired' => true,
                'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ],
        'tool_inplacetranslate_get_translated_string' => [
                'classname' => 'tool_inplacetranslate_external',
                'methodname' => 'get_translated_string',
                'classpath' => '',
                'description' => 'Get translated strings',
                'type' => 'read',
                'ajax' => true,
                'capabilities' => '',
                'loginrequired' => true,
                'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ],
        'tool_inplacetranslate_set_translated_string' => [
                'classname' => 'tool_inplacetranslate_external',
                'methodname' => 'set_translated_string',
                'classpath' => '',
                'description' => 'Set translation',
                'type' => 'write',
                'ajax' => true,
                'capabilities' => '',
                'loginrequired' => true,
                'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        ],
];

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = [
        'Tool inplacetranslate services' => [
                'functions' => [
                        'tool_inplacetranslate_translation_get_string',
                        'tool_inplacetranslate_translation_update_string',
                        'tool_inplacetranslate_get_translated_string',
                        'tool_inplacetranslate_set_translated_string',

                ],
                'enabled' => 1,
                'shortname' => 'tool_inplacetranslate',
        ],
];
