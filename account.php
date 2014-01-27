<?php

require_once('database.php');

function __formatAccountResult($account, $teamInfo, $messageID)
{
	if ($teamInfo)
	{
		success(array('account' => $account, 'team' => $teamInfo), $messageID);
	}else
	{
		success(array('account' => $account), $messageID);
	}
}

function loginUser($email, $password, $messageID)
{
	$dbManager = DBManager::manager();

	$dbManager->runQuery("SELECT * FROM account where email='$email' and password='$password' limit 1", 
						 function ($dbManager, $result) use($messageID)
						{
							$line = mysql_fetch_array($result, MYSQL_ASSOC);
							
							if ($line) 
							{
								$teamID = $line['team_id'];
								if ($teamID)
								{
									$dbManager->runQuery("select * from team where uuid='$teamID'", 
														function($dbManager, $innerResult, $context) use($line, $messageID)
														{
															$teamInfo = mysql_fetch_array($innerResult, MYSQL_ASSOC);
															__formatAccountResult($line, $teamInfo, $messageID);
														});
								}else
								{
									__formatAccountResult($line, null, $messageID);
								}
							}else
							{
								fail("Luke 11:10 (KJV) '...and to him that knocketh it shall be opened.'", $messageID);
							}
						});
}


function registerAccount($email, $password, $properties, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("insert into account(email, password, properties) "
					   . "values('$email', '$password', '$properties')", 					   
						function($dbManager, $result) use($messageID)
						{
							$error = mysql_error();
							if ($error == '')
							{
								success(null, $messageID);
							}else
							{
								fail($error, $messageID);
							}
							
						});
}

function registerDevice($deviceID, $deviceToken, $properties, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select device_id from device_info where device_id='$deviceID'", 
					   function($dbManager, $result, $context) use($messageID)
						{
							$data = mysql_fetch_array($result, MYSQL_ASSOC);
							
							if ($data)
							{
								
							}else
							{
								$dbManager->runQuery("insert into device_info(device_id, device_token, properties)"
												   . "  values('$context[0]', '$context[1]', '$context[2]')",
												   function($dbManager, $result, $context) use($messageID)
												   {
													   if(mysql_error() == '')
													   {
													   		success(null, $messageID);
													   }else
													   {
													   		fail("John 14:3(KJV) '...I will come again, and receive you unto myself...'", $messageID);
													   }
												   });
							}	
						}, array($deviceID, $deviceToken, $properties));
}

function getTeamMembers($teamID, $messageID)
{
	$dbManager = DBManager::manager();
	$dbManager->runQuery("select * from account where team_id='$teamID'", 
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