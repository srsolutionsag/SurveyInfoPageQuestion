<?php
require_once('./Modules/SurveyQuestionPool/classes/class.ilSurveyQuestionsPlugin.php');

/**
 * Class ilSurveyInfoPageQuestionPlugin
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilSurveyInfoPageQuestionPlugin extends ilSurveyQuestionsPlugin
{

    const SURVEY_INFO_PAGE_QUESTION = 'SurveyInfoPageQuestion';
    /**
     * @var ilSurveyInfoPageQuestionPlugin
     */
    protected static $cache;

    /**
     * @return string
     */
    public function getPluginName()
    {
        return self::SURVEY_INFO_PAGE_QUESTION;
    }

    /**
     * @return string
     */
    public function getQuestionType()
    {
        return self::SURVEY_INFO_PAGE_QUESTION;
    }

    /**
     * @return string
     */
    public function getQuestionTypeTranslation()
    {
        return $this->txt('common_question_type');
    }

    /**
     * @return ilSurveyInfoPageQuestionPlugin
     */
    public static function getPlugin()
    {
        if (!isset(self::$cache)) {
            self::$cache = new self();
        }

        return self::$cache;
    }
}
