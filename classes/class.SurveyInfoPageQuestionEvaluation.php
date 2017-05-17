<?php
require_once('./Modules/SurveyQuestionPool/classes/class.SurveyQuestionEvaluation.php');

/**
 * Class SurveyInfoPageQuestionEvaluation
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class SurveyInfoPageQuestionEvaluation extends SurveyQuestionEvaluation {

	/**
	 * @param array $a_row
	 * @param int $a_user_id
	 * @param array|\ilSurveyEvaluationResults $a_results
	 */
	public function addUserSpecificResults(array &$a_row, $a_user_id, $a_results) {
		if($a_results instanceof ilSurveyEvaluationResults) {
			$a_results->addVariable(new ilSurveyEvaluationResultsVariable('lorem', 5, 0.5));
		}
	}


	/**
	 * @param array|\ilSurveyEvaluationResults $a_results
	 * @return bool
	 */
	public function getChart($a_results) {
		return false;
	}
}
