<#1>
<?php
/** @noinspection PhpUndefinedVariableInspection */
$res = $ilDB->queryF('SELECT * FROM svy_qtype WHERE type_tag = %s', array('text' ), array('SurveyInfoPageQuestion' ));
if ($res->numRows() == 0) {
	$res = $ilDB->query('SELECT MAX(questiontype_id) maxid FROM svy_qtype');
	$data = $ilDB->fetchAssoc($res);
	$max = $data['maxid'] + 1;

	$affectedRows = $ilDB->manipulateF('INSERT INTO svy_qtype (questiontype_id, type_tag, plugin) VALUES (%s, %s, %s)', array(
			'integer',
			'text',
			'integer'
		), array(
			$max,
			'SurveyInfoPageQuestion',
			1
		));
}
?>
