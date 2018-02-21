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
    
    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    //~ protected function render_custom_menu(custom_menu $menu) {
		//~ global $COURSE;
		//~ $custommenu = parent::render_custom_menu($menu);
		//~ $titletable = '<table><tr>';
		//~ $titletable .= '<td>'.$custommenu.'</td>';
		//~ if ($COURSE->id > 1) {
			//~ $titletable .= '<td width="30px"></td>';
			//~ $titletable .= '<td style="vertical-align:top">'.$this->context_header().'</td>';								
		//	$titletable .= '<td>'.html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav')).'</td>';
		//~ }		
		//~ $titletable .= '</tr></table>';
        //~ return $titletable;
    //~ }

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
		//~ global $COURSE, $PAGE;
		//~ $thislistname = '';
		//~ $pagepath = $PAGE->url->get_path();
		//~ $cataloguepages = array('/blocks/catalogue/index.php',
								//~ '/blocks/catalogue/chooseplace.php',
								//~ '/blocks/catalogue/list/editing/chooseobject.php');		
		//~ if (in_array($pagepath, $cataloguepages)) {
			//~ $thislistname = $PAGE->url->get_param('name');
			//~ if (!$thislistname) {
				//~ $thislistname = $PAGE->url->get_param('list');
			//~ }
		//~ }
		//~ $maindivstyle = 'margin-top:10px;margin-left:50px;float:left';
		//~ echo "<div style='$maindivstyle'>";
		//~ echo block_catalogue_display_tabs($COURSE->id, $thislistname, false);
		//~ echo '</div>';
        //~ echo "<div style='$maindivstyle'>";
        //~ echo $this->proximityarrows();
        //~ echo '</div>';
	}

	/**
	 * If we're inside a mod, displays arrow links to the next mod and the previous mod.
	 * @global object $COURSE
	 * @global object $DB
	 * @global object $PAGE
	 * @return string HTML code
	 */
	function proximityarrows() {
		global $COURSE, $DB, $PAGE;
		$pagecontext = $PAGE->context;
		if ($pagecontext->contextlevel == 70) {
			$modinfo = get_fast_modinfo($COURSE);
			$cmid = $pagecontext->instanceid;
			$cm = $DB->get_record('course_modules', array('id' => $cmid));
			$section = $DB->get_record('course_sections', array('id' => $cm->section));
			$sequence = explode(',', $section->sequence);
			$current = array_search($cmid, $sequence);
			$previousarrow = $this->proximityarrow($modinfo, $sequence, $current, -1, $section);
			$nextarrow = $this->proximityarrow($modinfo, $sequence, $current, 1, $section);
			$maindivstyle = 'margin-top:10px;margin-left:50px;float:left';
			$arrows = "<div style='$maindivstyle'>";
			$arrows .= '<table><tr><td>'.$previousarrow.'</td><td>'.$nextarrow.'</td></tr></table>';
			$arrows .= '</div>';
			return $arrows;
		} else {
			return '';
		}
	}

	/**
	 * Draws the "previous" or the "next" arrow, with appropriate link and label.
	 * @global object $CFG
	 * @global object $COURSE
	 * @global object $DB
	 * @param cm_info $modinfo
	 * @param array of numeric strings $sequence
	 * @param int $current
	 * @param int $direction
	 * @param stdClass $section
	 * @return string HTML code
	 */
	function proximityarrow($modinfo, $sequence, $current, $direction, $section) {
		global $CFG, $COURSE, $DB;
		$cataloguepixdir = "$CFG->wwwroot/theme/catalogue/pix";
		$courselink = "$CFG->wwwroot/course/view.php?id=$COURSE->id";
		$sectionlink = "$courselink#section-$section->section";
		if ($direction > 0) {
			$picture = 'next.png';
		} else {
			$picture = 'previous.png';
		}
		$proxy = $this->proximod($modinfo, $sequence, $current, $direction);
		if ($proxy !== null) {
			$proxicm = $DB->get_record('course_modules', array('id' => $sequence[$proxy]));
			$proximodule = $DB->get_record('modules', array('id' => $proxicm->module));
			if (($proximodule->name == 'label')||($proximodule->name == 'customlabel')) {
				$proxilink = $sectionlink;
				$proxilabel = get_string('modulename', "mod_$proximodule->name");
			} else {
				$proxilink = "$CFG->wwwroot/mod/$proximodule->name/view.php?id=$proxicm->id";
				$proxiinstance = $DB->get_record($proximodule->name, array('id' => $proxicm->instance));
				$proxilabel = $proxiinstance->name;
			}
		} else {
			$proxilink = $sectionlink;
			if ($section->name) {
				$sectionname = $section->name;
			} else {
				$sectionname = ucwords(get_string('section'))." $section->section";
			}
			$proxilabel = $sectionname;
		}
		$arrow = "<a href='$proxilink'><img src='$cataloguepixdir/$picture' width='50px' alt='$proxilabel' title='$proxilabel'></a>";
		return $arrow;
	}

	/**
	 * Looks for the first available mod before or after the current one.
	 * @param cm_info $modinfo
	 * @param array of numeric strings $sequence
	 * @param int $current
	 * @param int $direction
	 * @return int
	 */
	function proximod($modinfo, $sequence, $current, $direction) {
		$proxy = $current + $direction;
		if (!isset($sequence[$proxy])) {
			return null;
		}
		$proxicmid = $sequence[$proxy];
		if (!is_numeric($proxicmid)) {
			return null;
		}
		$proxicm = $modinfo->get_cm($proxicmid);
		if (!$proxicm) {
			return null;
		}
		$uservisible = $proxicm->uservisible;
		if (!$uservisible) {
			return $this->proximod($modinfo, $sequence, $proxy, $direction);
		}
		return $proxy;
	}
	

}
