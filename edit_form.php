<?php
 
class block_return_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        $mform->addElement('checkbox', 'config_lightbox', get_string('lightbox', 'block_return'));
        $mform->setDefault('config_lightbox', 1);

        
    }
}