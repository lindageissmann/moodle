<?php
/**
 * This file contains the message block class, based upon block_base.
 *
 * @package    block_message
 * @copyright  2021 linda.geissmann@students.ffhs.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class block_message
 *
 * @package    block_message
 * @copyright  2021 linda.geissmann@students.ffhs.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_message extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_message');
    }

    function get_content() {
        global $DB, $CFG, $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

		// if user accessing the page is logged in
		// change in version file not necessary
		
		// only show content if user is logged in
		if($USER->firstname) {

			$text = '';

			//greet user by name
			$text .= "Hello, " . $USER->firstname . '! <br />';		
			
			//$text .= "Hello, ";
			//$text .= $USER->firstname;
			//$text .= '!';
			
			// query for cohort membership of currently logged in user
			$cohortmembership = $DB->get_records_select('cohort_members', 'userid = ?', array($USER->id), 'id');
			
			// if currently logged in user is assignet to a cohort
			if ($cohortmembership) {
				//they are assigned to a cohort
				// show a message for each cohort they belong to
				foreach ($cohortmembership as $showcohortmembership) {
					//um globale Variable der DB zu nutzen, auch oben bei global einbinden
					//tabelle, where, bdg., order by id
					//$cohortresult = $DB->get_records_select('cohort', 'name = ?', array('Freshmen'), 'id');				
					//$cohortresult = $DB->get_records_select('cohort', '1 = ?', array('1'), 'id'); //für alle: 1=1
					// uer fo cohort info for the cohort(s) they belong to
					$cohortresult = $DB->get_records_select('cohort', 'id = ?', array($showcohortmembership->cohortid), 'id');
					
					// verify the chort record(s) exist
					if ($cohortresult) {
						
						// cycle through the cohort query results
						foreach ($cohortresult as $showcohortresult) {
							
							//konkatenieren mit Punkt
							//$text .= $showcohortresult->id . ',' . $showcohortresult->name . '<br />';
							
							/*$text .= $showcohortresult->id;
							$text .= ',' ;
							$text .= $showcohortresult->name;
							$text .= '<br />';*/
							
							//case statemtn to show specific message fo each of the cohorts
							switch ($showcohortresult->name) {
								// if assignet to Freshmen cohort
								case 'Freshmen':
									$text .= 'Welcome to your new school! <br />';
									break;
								case 'Sophomore':
									$text .= 'Welcome back! <br />';
									break;
								case 'Junior':
									$text .= 'Are you ready junior? <br />';
									break;
								case 'Senior':
									$text .= 'Welcome back senior! <br />';
									break;
							}
						}
					}
				}
				
			} else {
				//not in a cohort
				$text .= 'Not in a cohort. <br />';
					
			}
		
			// wie String heisst (Arrayindex), wie lang-file heisst
			$text .= get_string('message:whateveryouwant','block_message');

			//assign $text varaible to the content object
			$this->content->text = $text;

			//function returns the content object
			return $this->content;
		}
	}
}