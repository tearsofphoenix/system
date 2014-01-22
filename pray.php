<?

require_once('database.php');

function addNewPray($deviceID, $content, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("insert into pray(device_id, content) values('$deviceID', '$content')",
	function($dbManager, $result, $context) use($messageID)
	{
		if (mysql_error() == '')
		{
			success(null, $messageID);
		}else
		{
			fail('Failed to add pray!', $messageID);
		}
	});
}

function getPrayList($last_update, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select * from pray where last_update > '$last_update'",
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