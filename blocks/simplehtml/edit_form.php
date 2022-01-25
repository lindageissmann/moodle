<?php
// block configuration form (for instance configuration), um Basiskonfig.mögl. zu erweitern
class block_simplehtml_edit_form extends block_edit_form {

    // Block Settings (wird in Config.form angezeigt)
    protected function specific_definition($mform) {

        // Section header title according to language file.
        // siehe auch https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_simplehtml'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_simplehtml'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT); //vermutlich wegen config_title, in Block Zugriff via $this->config->title
    }

    // damit global config. (settings.php) berücksichtigt wird
    // By overriding the instance_config_save() method in our block class, we can modify the way in which
    // instance configuration data is stored after input.
    // hier wird beim Erfassen HTML aus dem Block-Inhalt gefildert (strip_tags), wenn Einstellung
    // (aus globaler Config: Allow_HTML) gesetzt ist
    // But if Moodle takes care of the form processing for our instance configuration in edit_form.php,
    // how can we capture it and remove the HTML tags where required? 
    public function instance_config_save($data,$nolongerused =false) {
        if(get_config('simplehtml', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }

        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data,$nolongerused);
    }
}
