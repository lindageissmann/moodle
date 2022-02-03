<?php
 // block configuration form (for extending default instance configuration), um Basiskonfig.mögl. zu erweitern
/*class block_activityfeedback_edit_form extends block_edit_form {

    // Block Settings (wird in Config.form angezeigt)
    protected function specific_definition($mform) {

        // All your field names need to start with "config_", otherwise they will not be saved
        // and will not be available within the block via $this->config.
        // $this->config is available in all block methods except init()
        
        
    //    // konnte nicht finden, wo gespeichert, weder File ändert, noch Text auf DB auffindbar
    //
    //    // Section header title according to language file.
    //    // siehe auch https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
    //    //wo langfile für comp. block? // wofür hier überhaupt?
    //    $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
    //
    //    // A sample string variable with a default value.
    //    $mform->addElement('text', 'config_text', get_string('blockstring', 'block_activityfeedback'));
    //    $mform->setDefault('config_text', 'default value');
    //    $mform->setType('config_text', PARAM_RAW);
    //
    //    //$attributes='size="20"';
    //    //$mform->addElement('text', 'name', get_string('forumname', 'forum'), $attributes);
    //
    //    // A sample string variable with a default value.
    //    $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_activityfeedback'));
    //    $mform->setDefault('config_title', 'default value');
    //    $mform->setType('config_title', PARAM_TEXT); //vermutlich wegen config_title, in Block Zugriff via $this->config->title
    //
    //    // https://docs.moodle.org/dev/lib/formslib.php_Form_Definition#addElement
    //    // You cannot use the 'checkbox' element in the form (once set it will stay set). You must use advcheckbox instead. 
    //    $mform->addElement('advcheckbox', 'config_optionactive', get_string('optionactive', 'block_activityfeedback'), 
    //            'Label displayed after checkbox', null, array(0, 1));
    //    $mform->setType('config_optionactive', PARAM_BOOL);
    //    
    //    //filepicker
    //    //$mform->addElement('filepicker', 'userfile', get_string('file'), null, array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
    }

    // damit global config. (settings.php) berücksichtigt wird
    // By overriding the instance_config_save() method in our block class, we can modify the way in which
    // instance configuration data is stored after input.
    // hier wird beim Erfassen HTML aus dem Block-Inhalt gefiltert (strip_tags), wenn Einstellung
    // (aus globaler Config: Allow_HTML) gesetzt ist
    // But if Moodle takes care of the form processing for our instance configuration in edit_form.php,
    // how can we capture it and remove the HTML tags where required? 
    public function instance_config_save($data,$nolongerused =false) {
        if(get_config('activityfeedback', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }
    
        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data,$nolongerused);
    }
}*/
