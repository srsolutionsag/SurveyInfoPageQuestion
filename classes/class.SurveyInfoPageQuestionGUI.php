<?php
require_once('./Customizing/global/plugins/Modules/SurveyQuestionPool/SurveyQuestions/SurveyInfoPageQuestion/classes/class.SurveyInfoPageQuestion.php');
require_once('./Modules/SurveyQuestionPool/classes/class.SurveyQuestionGUI.php');
require_once('./Services/Object/classes/class.ilObject2.php');

/**
 * Class SurveyInfoPageQuestionGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy SurveyInfoPageQuestionGUI: ilObjSurveyQuestionPoolGUI, ilSurveyEditorGUI
 */
class SurveyInfoPageQuestionGUI extends SurveyQuestionGUI {

	const FIELD_NAME = 'field_info_page';
	/**
	 * @var ilSurveyInfoPageQuestionPlugin
	 */
	protected $plugin_object;
	/**
	 * @var SurveyInfoPageQuestion
	 */
	public $object;


	/**
	 * @param $a_id
	 */
	public function __construct($a_id = - 1) {
		parent::__construct($a_id);
		$this->plugin_object = new ilSurveyInfoPageQuestionPlugin();
	}


	/**
	 * @return mixed
	 */
	public function &executeCommand() {
		global $ilTabs, $ilCtrl;
		$cmd = $this->ctrl->getCmd();
		switch ($cmd) {
			case 'preview':

				/**
				 * @var $ilTabs ilTabsGUI
				 * @var $ilCtrl ilCtrl
				 */
				$ilTabs->clearTargets();
				$ilCtrl->setParameterByClass('ilObjSurveyQuestionPoolGUI', 'ref_id', $_GET['ref_id']);
				$title = ilObject2::_lookupTitle(ilObject2::_lookupObjId($_GET['ref_id']));
				$ilTabs->setBackTarget($title, $ilCtrl->getLinkTargetByClass('ilObjSurveyQuestionPoolGUI', 'questions'));
				$ilTabs->addTab('preview', $this->plugin_object->txt('common_preview'), '');
				$ret =& $this->$cmd();
				break;
			default:
				$ret =& $this->$cmd();
				break;
		}

		return $ret;
	}


	/**
	 * @return string
	 */
	public function getQuestionType() {
		$plugin_object = new ilSurveyInfoPageQuestionPlugin();

		return $plugin_object->getPrefix() . '_common_question_type';
	}


	protected function initObject() {
		$this->object = new SurveyInfoPageQuestion();
	}


	public function setQuestionTabs() {
		// TODO: Implement setQuestionTabs() method.
	}


	/**
	 * @param ilPropertyFormGUI $a_form
	 */
	protected function addFieldsToEditForm(ilPropertyFormGUI $a_form) {
		$this->removeFields($a_form);
		$this->addHiddenFields();
		$this->addTiny($a_form);
	}


	/**
	 * @param ilPropertyFormGUI $a_form
	 */
	protected function importEditFormValues(ilPropertyFormGUI $a_form) {
		$this->object->setQuestiontext($a_form->getInput(self::FIELD_NAME));
	}


	/**
	 * @param int $question_title
	 * @param int $show_questiontext
	 *
	 * @return string
	 */
	public function getPrintView($question_title = 1, $show_questiontext = 1) {
		return $this->getWorkingForm();
	}


	/**
	 * @param string $working_data
	 * @param int    $question_title
	 * @param int    $show_questiontext
	 * @param string $error_message
	 * @param null   $survey_id
	 *
	 * @return string
	 */
	public function getWorkingForm($working_data = '', $question_title = 1, $show_questiontext = 1, $error_message = '', $survey_id = NULL) {
		return $this->object->getQuestiontext();
	}


	/**
	 * @param $survey_id
	 * @param $counter
	 * @param $finished_ids
	 */
	public function getCumulatedResultsDetails($survey_id, $counter, $finished_ids) {
		// TODO: Implement getCumulatedResultsDetails() method.
	}


	/**
	 * @param ilPropertyFormGUI $a_form
	 *
	 * @internal param $ilUser
	 */
	protected function addTiny(ilPropertyFormGUI $a_form) {
		$finalstatement = new ilTextAreaInputGUI($this->plugin_object->txt(self::FIELD_NAME), self::FIELD_NAME);
		$finalstatement->setValue($this->object->prepareTextareaOutput($this->object->getQuestiontext()));
		$finalstatement->setRows(30);
		$finalstatement->setCols(80);
		$finalstatement->setUseRte(true);
		$finalstatement->setRteTags(ilObjAdvancedEditing::_getUsedHTMLTags('survey'));
		$finalstatement->addPlugin('latex');
		$finalstatement->addButton('latex');
		$finalstatement->addButton('pastelatex');
		$finalstatement->setRTESupport($this->object->getId(), 'svy', 'survey', NULL, true);

		$a_form->addItem($finalstatement);
	}


	protected function addHiddenFields() {
		$question_hidden = new ilHiddenInputGUI('question');
		$question_hidden->setValue('-');
		$question_hidden = new ilHiddenInputGUI('obligatory');
		$question_hidden->setValue(false);
	}


	/**
	 * @param ilPropertyFormGUI $a_form
	 */
	protected function removeFields(ilPropertyFormGUI $a_form) {
		$a_form->removeItemByPostVar('question');
		$a_form->removeItemByPostVar('obligatory');
	}
}

?>
