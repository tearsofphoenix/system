<?php

require_once('database.php');
require_once('uuid.php');

function feedback($deviceID, $content, $messageID)
{
	$uuid = UUID::v4();
	$dbManager = DBManager::manager();
	$dbManager->runQuery("insert into feedback(uuid, device_id, content) values('$uuid', '$deviceID', '$content')",
	function($dbManager, $result, $context) use($messageID)
	{
		$error = mysql_error();
		if($error == '')
		{
			success(null, $messageID);
		}else
		{
			fail($error, $messageID);
		}
	});
}
?>