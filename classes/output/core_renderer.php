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

namespace theme_catalogue\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/blocks/catalogue/lib.php");

class core_renderer extends \theme_boost\output\core_renderer {

    protected $language = null;

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $COURSE;
        block_catalogue_check_sequences($COURSE);
        $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'row'));
        $html .= html_writer::start_div('col-xs-12 p-a-1');
        $html .= html_writer::start_div('card');
        $html .= html_writer::start_div('card-block');
        $html .= html_writer::div($this->context_header_settings_menu(), 'pull-xs-right context-header-settings-menu');
        $html .= $this->context_header();
        $coursecontext = context_course::instance($COURSE->id);
        if (has_capability('block/catalogue:view', $coursecontext)) {
            $html .= $this->headercatalogue();
        }
        $html .= html_writer::start_div('clearfix', array('id' => 'page-navbar'));
        $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
        $html .= html_writer::div($this->page_heading_button(), 'breadcrumb-button');
        $html .= html_writer::end_div();
        $html .= html_writer::tag('div', $this->course_header(), array('id' => 'course-header'));
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_tag('header');
        return $html;
    }

    public function headercatalogue() {
        global $COURSE, $PAGE;
        $thislistname = '';
        $pagepath = $PAGE->url->get_path();
        if ($pagepath == '/blocks/catalogue/index.php') {
            $thislistname = $PAGE->url->get_param('name');
        }
        $maindivstyle = 'margin-top:10px;margin-left:50px;float:left';
        echo "<div style='$maindivstyle'>";
        echo block_catalogue_display_tabs($COURSE->id, $thislistname, false);
        echo '</div>';
        echo "<div style='$maindivstyle'>";
        echo block_catalogue_navigation();
        echo '</div>';
    }
}
