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
 * Form for editing Return Block instances.
 *
 * @package    block_return
 * @copyright  2021 Lee Chamney, Commissionaires Ottawa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_return_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        $mform->addElement('advcheckbox', 'config_lightbox', get_string('lightbox', 'block_return'));
        $mform->setDefault('config_lightbox', 1);
        $mform->setType('config_lightbox', PARAM_RAW);    

        
    }
}
