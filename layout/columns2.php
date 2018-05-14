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
 * A two column layout for the boost theme.
 *
 * @package   theme_boost
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

if (isloggedin() && !behat_is_test_site()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu)
];
$templatecontext['catalogue'] = theme_catalogue_catalogue();
<<<<<<< HEAD
$templatecontext['iconsurl'] = "$CFG->wwwroot/theme/catalogue";
=======
>>>>>>> 98d634fee01c8e6eab13f9daf7a040a25a557b46
$templatecontext['flatnavigation'] = $PAGE->flatnav;
$output = $OUTPUT->render_from_template('theme_catalogue/columns2', $templatecontext);
<<<<<<< HEAD

=======
>>>>>>> 98d634fee01c8e6eab13f9daf7a040a25a557b46
echo $output;


//~ $coursehomebuttonboost = 'data-key="coursehome">
                //~ <div class="m-l-0">
                        //~ '.$COURSE->shortname.'
                //~ </div>';
//~ $coursehomebuttoncatalogue = 'data-key="coursehome">
                //~ <div class="m-l-0">
                       //~ '.'<table width="100%"><tr><td style="margin-right:5px">'.
                       //~ '<img src="'."$CFG->wwwroot/blocks/catalogue/pix/coursehome.png".'" height="30px">'.
                       //~ '</td><td style="text-align:center">'.$COURSE->shortname.'</td></tr></table>'.'
                //~ </div>';


//~ $extract = substr($output, 36896, 300);
//~ $position = strpos($output, $coursehomebuttonboost);
//~ print_object($COURSE);
//~ echo "$extract<br><br>";
//~ var_dump($position);


//~ $output = str_replace($coursehomebuttonboost, $coursehomebuttoncatalogue, $output);
//~ echo $output;
