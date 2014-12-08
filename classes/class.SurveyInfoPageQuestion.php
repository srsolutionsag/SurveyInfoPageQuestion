<?php
require_once('./Services/RTE/classes/class.ilRTE.php');

/**
 * Class SurveyInfoPageQuestion
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SurveyInfoPageQuestion extends SurveyQuestion {

	/**
	 * @return string
	 */
	public function getAdditionalTableName() {
		return '';
	}


	/**
	 * @return array
	 */
	public function _getQuestionDataArray() {
		return array(
			'question_id' => $this->getId(),
			'questiontype_fi' => $this->getQuestionTypeID(),
			'obj_fi' => $this->getObjId(),
			'owner_fi' => $this->getOwner(),
			'title' => $this->getTitle(),
			'description' => $this->getDescription(),
			'author' => $this->getAuthor(),
			'obligatory' => (int)$this->getObligatory(),
			'complete' => (int)$this->isComplete(),
			'created' => time(),
			'original_id' => $this->getOriginalId(),
			'tstamp' => time(),
			'questiontext' => $this->getQuestiontext(),
			'label' => '',
			'question_fi' => $this->getId(),
		);
	}


	/**
	 * @param array $post_data
	 * @param       $survey_id
	 *
	 * @return string
	 */
	public function checkUserInput(array $post_data, $survey_id) {
		unset($post_data);
		unset($survey_id);

		return "";
	}


	/**
	 * @param array $post_data
	 * @param       $active_id
	 * @param bool  $a_return
	 */
	public function saveUserInput(array $post_data, $active_id, $a_return = false) {
		unset($post_data);
		unset($a_return);
		$entered_value = 1;
		global $ilDB;
		/**
		 * @var $ilDB ilDB
		 */
		$next_id = $ilDB->nextId('svy_answer');
		$ilDB->manipulateF("INSERT INTO svy_answer (answer_id, question_fi, active_fi, value, textanswer, tstamp) VALUES (%s, %s, %s, %s, %s, %s)", array(
			'integer',
			'integer',
			'integer',
			'float',
			'text',
			'integer'
		), array(
			$next_id,
			$this->getId(),
			$active_id,
			NULL,
			(strlen($entered_value)) ? $entered_value : NULL,
			time()
		));
	}


	/**
	 * @param $survey_id
	 * @param $nr_of_users
	 * @param $finished_ids
	 *
	 * @return int
	 */
	public function getCumulatedResults($survey_id, $nr_of_users, $finished_ids) {
		global $ilDB;

		$question_id = $this->getId();

		$result_array = array();
		$cumulated = array();
		$textvalues = array();

		$sql = 'SELECT svy_answer.* FROM svy_answer' . ' JOIN svy_finished ON (svy_finished.finished_id = svy_answer.active_fi)'
			. ' WHERE svy_answer.question_fi = ' . $ilDB->quote($question_id, 'integer')
			. ' AND svy_finished.survey_fi = ' . $ilDB->quote($survey_id, 'integer');
		if ($finished_ids) {
			$sql .= ' AND ' . $ilDB->in('svy_finished.finished_id', $finished_ids, '', 'integer');
		}

		$result = $ilDB->query($sql);
		while ($row = $ilDB->fetchAssoc($result)) {
			$cumulated[$row['value']] ++;
			array_push($textvalues, $row['textanswer']);
		}
		asort($cumulated, SORT_NUMERIC);
		end($cumulated);
		$numrows = $result->numRows();
		$pl = ilSurveyInfoPageQuestionPlugin::getPlugin();

		$result_array['USERS_ANSWERED'] = $numrows;
		$result_array['USERS_SKIPPED'] = $nr_of_users - $numrows;
		$result_array['USERS_SKIPPED'] = '-';
		$result_array['QUESTION_TYPE'] = $pl->getPrefix() . '_common_question_type';
		$result_array['textvalues'] = $textvalues;

		return $result_array;
	}


	/**
	 * Creates a the cumulated results data for the question
	 *
	 * @param $survey_id
	 * @param $counter
	 * @param $finished_ids
	 *
	 * @return array Data
	 */
	//	public function getCumulatedResultData($survey_id, $counter, $finished_ids) {
	//		return array();
	//	}

	/**
	 * Returns an array containing all answers to this question in a given survey
	 *
	 * @param integer $survey_id The database ID of the survey
	 * @param         $finished_ids
	 *
	 * @return array An array containing the answers to the question. The keys are either the user id or the anonymous id
	 * @access public
	 */
	public function getUserAnswers($survey_id, $finished_ids) {
		global $ilDB;

		$answers = array();

		$sql = "SELECT svy_answer.* FROM svy_answer, svy_finished" . " WHERE svy_finished.survey_fi = " . $ilDB->quote($survey_id, "integer")
			. " AND svy_answer.question_fi = " . $ilDB->quote($this->getId(), "integer") . " AND svy_finished.finished_id = svy_answer.active_fi";
		if ($finished_ids) {
			$sql .= " AND " . $ilDB->in("svy_finished.finished_id", $finished_ids, "", "integer");
		}
		$result = $ilDB->query($sql);
		while ($row = $ilDB->fetchAssoc($result)) {
			$answers[$row["active_fi"]] = $row["textanswer"];
		}

		return $answers;
	}


	/**
	 * @param int $question_id
	 */
	public function loadFromDb($question_id) {
		/**
		 * @var $ilDB ilDB
		 */
		global $ilDB;
		$result = $ilDB->queryF('SELECT svy_question.* FROM svy_question WHERE svy_question.question_id = %s', array( 'integer' ), array( $question_id ));

		if ($result->numRows() == 1) {
			$data = $ilDB->fetchObject($result);
			$this->setId($data->question_id);
			$this->setTitle($data->title);
			$this->label = $data->label;
			$this->setDescription($data->description);
			$this->setObjId($data->obj_fi);
			$this->setAuthor($data->author);
			$this->setOwner($data->owner_fi);
			$this->setQuestiontext(ilRTE::_replaceMediaObjectImageSrc($data->questiontext, 1));
			$this->setObligatory($data->obligatory);
			$this->setComplete($data->complete);
			$this->setOriginalId($data->original_id);
		}
		parent::loadFromDb($question_id);
	}


	/**
	 * @return bool
	 */
	public function isComplete() {
		return true;
	}


	/**
	 * @return string
	 */
	public function getQuestionType() {
		$plugin_object = new ilSurveyInfoPageQuestionPlugin();

		return $plugin_object->getQuestionType();
	}


	/**
	 * @var string
	 */
	protected $info_page_text = '';


	/**
	 * @param string $info_page_text
	 */
	public function setInfoPageText($info_page_text) {
		$this->info_page_text = $info_page_text;
	}


	/**
	 * @return string
	 */
	public function getInfoPageText() {
		return $this->info_page_text;
	}
}

?>
