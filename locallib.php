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
 * Plugin general functions are defined here.
 *
 * @package     tool/inplacetranslate
 * @copyright   2024 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function is_admin_and_page_edit() {
    global $USER;

    if (!is_siteadmin()) {
        return false;
    }

    if (!$USER->editing) {
        return false;
    }

    return true;
}

function store_string_in_moodledata($component, $lang, $stringid, $customization) {
    global $CFG;

    if ($lang !== clean_param($lang, PARAM_LANG)) {
        throw new moodle_exception('Unable to dump local strings for non-installed language pack ' . s($lang));
    }
    if ($component !== clean_param($component, PARAM_COMPONENT)) {
        throw new coding_exception('Incorrect component name');
    }

    if (!$filename = get_component_filename($component)) {
        throw new moodle_exception('Unable to find the filename for the component ' . s($component));
    }

    if ($filename !== clean_param($filename, PARAM_FILE)) {
        throw new coding_exception('Incorrect file name ' . s($filename));
    }

    list($package, $subpackage) = core_component::normalize_component($component);
    $packageinfo = " * @package    $package";
    if (!is_null($subpackage)) {
        $packageinfo .= "\n * @subpackage $subpackage";
    }

    $customlangdir = $CFG->dataroot . '/lang/' . $lang . '_local/';
    if (!file_exists($customlangdir)) {
        mkdir($customlangdir, $CFG->directorypermissions, true);
    }

    $filepath = $customlangdir . $filename;
    if (!is_dir(dirname($filepath))) {
        check_dir_exists(dirname($filepath));
    }

    if (file_exists($filepath)) {
        include($filepath);
    }

    $string[$stringid] = $customization;
    $strings = $string;

    if (!$f = fopen($filepath, 'w')) {
        throw new moodle_exception('Unable to write ' . s($filepath));
    }

    fwrite($f, <<<EOF
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
 * Local language pack for $packageinfo
 *
$packageinfo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

EOF
    );

    foreach ($strings as $id => $text) {
        fwrite($f, "\$string['$id'] = " . var_export($text, true) . ";\n");
    }

    fclose($f);
    @chmod($filepath, $CFG->filepermissions);

    return true;
}

function get_component_filename($component) {
    return $component . '.php';
}

function get_string_from_lang_file($identifier, $component, $lang) {
    global $CFG;

    static $langconfigstrs = array(
            'strftimedate' => 1,
            'strftimedatefullshort' => 1,
            'strftimedateshort' => 1,
            'strftimedatetime' => 1,
            'strftimedatetimeaccurate' => 1,
            'strftimedatetimeshort' => 1,
            'strftimedatetimeshortaccurate' => 1,
            'strftimedaydate' => 1,
            'strftimedaydatetime' => 1,
            'strftimedayshort' => 1,
            'strftimedaytime' => 1,
            'strftimemonth' => 1,
            'strftimemonthyear' => 1,
            'strftimerecent' => 1,
            'strftimerecentfull' => 1,
            'strftimetime' => 1);

    if (empty($component)) {
        if (isset($langconfigstrs[$identifier])) {
            $component = 'langconfig';
        } else {
            $component = 'moodle';
        }
    }

    if ($lang === null) {
        $lang = current_language();
    }

    $stringmanager = get_string_manager();

    $strings = $stringmanager->load_component_strings($component, $lang, false, true);

    if (isset($strings[$identifier])) {
        return $strings[$identifier];
    } else {
        return '';
    }
}
