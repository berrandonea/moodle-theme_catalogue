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
 * @package    theme_catalogue
 * @copyright  2017 Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->dirroot/blocks/catalogue/lib.php");

function theme_catalogue_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');

    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_catalogue', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }
//~ <<<<<<< HEAD

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = ''; //file_get_contents($CFG->dirroot . '/theme/photo/scss/pre.scss');
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    //~ $post = file_get_contents($CFG->dirroot . '/theme/photo/scss/post.scss');
    //~ $post = file_get_contents($CFG->dirroot . '/theme/catalogue/scss/catalogue.scss');
    $post = '';
    // Combine them together.
    return $pre . "\n" . $scss . "\n" . $post;
}

//~ function theme_catalogue_catalogue() {
	//~ global $COURSE, $PAGE;
	//~ $thislistname = '';
	//~ $pagepath = $PAGE->url->get_path();
	//~ if ($pagepath == '/blocks/catalogue/index.php') {
		//~ $thislistname = $PAGE->url->get_param('name');
	//~ }
	//~ $maindivstyle = 'margin-top:10px;margin-left:50px;float:left';
	//~ $html = '';
    //~ $listnames = block_catalogue_get_listnames();
    //~ $coursecontext = context_course::instance($COURSE->id);
    //~ $canview = has_capability('block/catalogue:view', $coursecontext);
    //~ if ($listnames && $canview && $COURSE->id > 1) {
		//~ $bgcolor = get_config('catalogue', 'bgcolor');
        //~ $html = block_catalogue_main_table($listnames, $COURSE, $bgcolor, true);
//~ =======
    //~ return $scss;
    //~ return '';
//~ }

function theme_catalogue_catalogue() {
    global $COURSE, $PAGE;
    $thislistname = '';
    $pagepath = $PAGE->url->get_path();
    if ($pagepath == '/blocks/catalogue/index.php') {
        $thislistname = $PAGE->url->get_param('name');
    }
    $maindivstyle = 'margin-top:10px;margin-left:50px;float:left';
    $html = '';
    $listnames = block_catalogue_get_listnames();
    $coursecontext = context_course::instance($COURSE->id);
    $canview = has_capability('block/catalogue:view', $coursecontext);
    if ($listnames && $canview) {
        $bgcolor = get_config('catalogue', 'bgcolor');
        $html = block_catalogue_main_table($listnames, $COURSE, $bgcolor, false);
//~ >>>>>>> 98d634fee01c8e6eab13f9daf7a040a25a557b46
    }
    $html .= '<br>';
    return $html;
}
