<?php

require_once('account.php');
require_once('pray.php');
require_once('push.php');
require_once('serve.php');
require_once('feedback.php');
require_once('resources.php');
require_once('sermon.php');

function validateMessage($action, $messageID, $messageHash)
{
	return md5($action . $messageID) == $messageHash;
}

function dispatchMessage()
{ 
	$jsonString = $_POST['request'];
	$request = json_decode($jsonString, true);
	
	$action = $request['action'];
	$arguments = $request['arguments'];
	$messageID = $request['messageID'];
	$messageHash = $request['messageHash'];
	
/* 	var_dump($_POST); */
		
	if (!validateMessage($action, $messageID, $messageHash))
	{
		fail('Exodus 20:16 Thou shalt not bear false witness against thy neighbour.', $messageID);
		return ;
	}	
	
	switch($action)
	{
		case 'login':
		{
			loginUser($arguments['email'], $arguments['password'], $messageID);
			break;
		}
		case 'get_team_members':
		{
			getTeamMembers($arguments['team_id'], $messageID);
			break;
		}
		case 'register_device':
		{
			$deviceID = $arguments['device_id'];
			$deviceToken = $arguments['device_token'];
			$properties = $arguments['properties'];
			
			registerDevice($deviceID, $deviceToken, $properties, $messageID);
			break;
		}
		case 'add_pray':
		{
			$deviceID = $arguments['device_id'];
			$content = $arguments['content'];
			$title = $arguments['title'];
			
			addNewPray($deviceID, $title, $content, $messageID);
			break;
		}
		case 'fetch_pray':
		{
			getPrayList($arguments['last_update'], $messageID);
			break;
		}
		case 'send_message':
		{
			$senderID = $arguments['sender_id'];
			$receiverIDs = $arguments['receiver_ids'];
			$content = $arguments['content'];
			$method = $arguments['method'];
			
			sendMessage($senderID, $receiverIDs, $content, $method, $messageID);
			
			break;
		}
		case 'fetch_message':
		{
			$receiverID = $arguments['receiver_id'];
			$lastUpdate = $arguments['last_update'];
			
			fetchMessage($receiverID, $lastUpdate, $messageID);
			break;
		}
		case 'feedback':
		{
			feedback($arguments['device_id'], $arguments['content'], $messageID);
			break;
		}
		case 'fetch_resource_category':
		{
			fetchResourceCategories($arguments['last_update'], $messageID);
			break;
		}
		case 'fetch_resource':
		{
			fetchResourceInCategory($arguments['category_id'], $arguments['last_update'], $messageID);
			break;
		}
		case 'fetch_sermon_category':
		{
			fetchSermonCategories($arguments['last_update'], $messageID);
			break;
		}
		case 'fetch_sermon':
		{
			fetchSermonInCategory($arguments['category_id'], $arguments['last_update'], $messageID);
			break;
		}
		default:
		{
			break;
		}
	}
};

dispatchMessage();

?>