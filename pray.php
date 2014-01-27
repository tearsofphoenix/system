<?

require_once('database.php');

function addNewPray($deviceID, $title, $content, $messageID)
{
	$uuid = UUID::v4();
	$dbManager = DBManager::manager();
	$dbManager->runQuery("insert into pray(uuid, device_id, title, content)"
					   . " values('$uuid', '$deviceID', '$title', '$content')",
	function($dbManager, $result, $context) use($messageID)
	{
		if (mysql_error() == '')
		{
			success(null, $messageID);
		}else
		{
			fail('Daniel 10:12(KJV) "...thy words were heard, and I am come for thy words."', $messageID);
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