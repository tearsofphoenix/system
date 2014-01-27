<?php

require_once('database.php');

function sendMessage($senderID, $receiverIDs, $content, $method)
{	
	$uuid = UUID::v4();
	
	$dbManager = DBManager::manager();
	
	$dbManager->runQuery("insert into message_content(uuid, content) "
					   . "   values('$uuid', '$content')",	
	function($dbManager, $result, $context) use($senderID, $uuid, $receiverIDs, $method)
	{
		if (mysql_error() == '')
		{
			$sqlError = null;
			
			foreach ($receiverIDs as $iLooper)
			{
				$messageID = UUID::v4();
				$str = "insert into message(uuid, sender_id, receiver_id, content_id, send_method) "
								   . " values('$messageID', '$senderID', '$iLooper', '$uuid','$method')";
				
				$dbManager->runQuery($str,
								   function($dbManager, $result, $context) use($sqlError)
								   {
								   		$sqlError = mysql_error();
								   });
								   
				if ($sqlError)
				{
					break;
				}
			}
			
			if ($sqlError)
			{
				fail($sqlError);
			}else
			{
				success();
			}
		}else
		{
			fail('Psalms 19:2(KJV) "Day unto day uttereth speech, and night unto night sheweth knowledge."');
		}
	});
}

function fetchMessage($receiverID, $lastUpdate, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select message_content.uuid, message_content.content, message_content.last_update from message_content "
					   . "  left join message    "
					   . "	on message.content_id = message_content.uuid"
				       . "  where message.receiver_id='$receiverID' and message.last_update > '$lastUpdate'"
				       . "  order by message_content.last_update",
	function($dbManager, $result, $context) use($messageID)
	{
		$data = array();
		
		while ($iLooper = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			array_push($data, $iLooper);	
		}
		
		success($data, $messageID);
	});
}

?>