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
														function($dbManager, $innerResult, $context) use($line)
														{
															$teamInfo = mysql_fetch_array($innerResult, MYSQL_ASSOC);
															__formatAccountResult($line, $teamInfo);
														});
								}else
								{
									__formatAccountResult($line, null, $messageID);
								}
							}else
							{
								fail('failed to login!', $messageID);
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
													   		fail('Fail to register device.', $messageID);
													   }
												   });
							}	
						}, array($deviceID, $deviceToken, $properties));
}

?>