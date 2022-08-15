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
 * Form for editing HTML block instances.
 *
 * @package    block_return
 * @copyright  2021 Lee Chamney, Commissionaires Ottawa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_return extends block_base {
    /**
     * Initializes the block
     */
    public function init() {
        $this->title = get_string('return', 'block_return');
    }
    /**
     * returns block content
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }
        
        global $USER;
        global $DB;

       $uid = $USER->id;
        
        if ($uid)
        {

            $table = $this->get_latest($uid);
            $activity = $this->get_module_id($table);
            if ($activity)
            {
                $this->content->text = $this->get_a($table, $activity);
        
                return $this->content;
            }
         }
    }
    
    /**
     * Finds the most recent log entry where the active user viewed a course module.
     * @param int $uid User ID of active user
     * @return StdObject Data object containing timecreated, contextinstanceid, and userid representing last course module accessed
     */
    private function get_latest($uid) {
        
        global $DB;

        
        $sql = "SELECT timecreated, contextinstanceid, component, userid FROM {logstore_standard_log} WHERE userid=? 
             AND action='viewed' AND component LIKE 'mod_%' ORDER BY timecreated DESC LIMIT 1";
        
        $outputfull = $DB->get_records_sql($sql, array(0 => $uid));

        return array_pop($outputfull);
    }
    
    /**
     * Returns the module id of the provided data object
     * @param StdObject $object Data object containing module id
     * @return string Module ID 
     */
    private function get_module_id($object) {
        //finds the viewed module's id in the table.
        $tablearray = (array) $object;
        $output = $tablearray['contextinstanceid'];
        return $output;
    }
    
    /**
     * Builds a hyperlink to the module indicated
     * @param $object StdObject Data object containing module information
     * @param $activity ID of course module
     * @return URL of course module view
     */
    private function get_link($object, $activity) {   
        $tablearray = (array) $object;
        
        //gets the component name (scorm, calendar, etc) from the table
        $component = $tablearray['component'];
        
        //Formats the component name to remove Frankenstyle
        $compname = str_replace("mod_", "", $component);
        
        $url = new moodle_url("/mod/{$compname}/view.php", ['id' => $activity]);
        
        return $url->out();
    }
    
    /**
     * Puts together an <a> tag for the hyperlink
     * @param $object StdObject Data object containing module information
     * @param $activity ID of course module
     * @return <a> tag for hyperlink
     */
    private function get_a($object, $activity) {
        $msg = get_string('click_to_continue', 'block_return');
        $link = $this->get_link($object, $activity);
        
        if($this->config->lightbox) {
            $output = '<a href='.$link.'><div class="return_lightbox">'.$msg.'</div></a>';
        }
        else {
            $output = '<a href='.$link.'>'.$msg.'</a>';
        }
        return $output;
        
    }

    public function has_config() {
        return true;
    }

    public function hide_header() {
        return TRUE;
}


}

