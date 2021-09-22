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
    public function init() {
        $this->title = get_string('return', 'block_return');
    }
    
    public function get_content() {
    global $USER;

        //variable $uid is set to the id of the active user
       $uid = $USER->id;
        
        if ($uid)
        {

           $table = $this->get_latest($uid);
           $activity = $this->get_module_id($table);
           if ($activity)
           {
                //creates the lightbox with the correct link and message.
               $this->content->text = $this->get_a($table, $activity);
        
               return $this->content;
           }
        }
    }
    
    private function get_latest($uid)
    {
        //Finds the latest log entry where the active user viewed a course module.
        global $DB;

        $sql = 'SELECT timecreated, contextinstanceid, component, userid FROM {logstore_standard_log} WHERE userid=? AND action="viewed" AND component LIKE "mod_%" ORDER BY timecreated DESC LIMIT 1';
        
        $outputfull = $DB->get_records_sql($sql, array(0=>$uid));

        //return the first (and only) row of this SQL request.
        return array_pop($outputfull);
    }
    
    private function get_module_id($table)
    {
        //finds the viewed module's id in the table.
        $table_array = (array) $table;
        $output = $table_array['contextinstanceid'];
        return $output;
    }
    
    
    private function get_link($table, $activity)
    {   
        //builds a link to the last viewed activity
        $tablearray = (array) $table;
        
        //gets the component name (scorm, calendar, etc) from the table
        $component = $tablearray['component'];
        
        //formats the component name to remove Frankenstyle
        $comp_name = str_replace("mod_", "", $component);
        
        $url = new moodle_url("/mod/{$comp_name}/view.php", ['id' => $activity]);
        
        return $url->out();

    }
    
    private function get_a($table, $activity)
    {
        $msg = get_string('click_to_continue', 'block_return');
        $link = $this->get_link($table, $activity);
        
        if($this->config->lightbox)
        {
            $output = '<a href='.$link.'><div class="return_lightbox">'.$msg.'</div></a>';
        }
        else
        {
            $output = '<a href='.$link.'>'.$msg.'</a>';
        }
        return $output;
        
    }

    function has_config() {return true;}

    function hide_header() {
        return TRUE;
}


}

?>
