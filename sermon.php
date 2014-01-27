<?php
require_once('database.php');

function fetchSermonCategories($last_update, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select * from sermon_category where last_update > '$last_update' order by last_update",
	function($dbManager, $result, $context) use($messageID)
	{
		$data = array();
		while($iLooper = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			array_push($data, $iLooper);
		}
		
		success($data, $messageID);
	});
}

function fetchSermonInCategory($categoryID, $last_update, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select * from sermon where category_id='$categoryID' and last_update > '$last_update' order by last_update",
	function($dbManager, $result, $context) use($messageID)
	{
		$data = array();
		while($iLooper = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			array_push($data, $iLooper);
		}
		
		success($data, $messageID);
	});
}
?>