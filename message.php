<?php

require_once('account.php');
require_once('pray.php');
require_once('push.php');
require_once('serve.php');

function dispatchMessage()
{
	$action = $_POST['action'];
/* 	echo(json_encode($_GET)); */
	
	switch($action)
	{
		case 'login':
		{
			loginUser($_POST['email'], $_POST['password']);
			break;
		}
		case 'register_device':
		{
			$deviceID = $_POST['device_id'];
			$deviceToken = $_POST['device_token'];
			$properties = $_POST['properties'];
			
			registerDevice($deviceID, $deviceToken, $properties);
			break;
		}
		case 'add_pray':
		{
			$deviceID = $_POST['device_id'];
			$content = $_POST['content'];
			
			addNewPray($deviceID, $content);
			break;
		}
		case 'fetch_pray':
		{
			getPrayList($_POST['last_update']);
			break;
		}
		case 'send_message':
		{
			$senderID = $_POST['sender_id'];
			$receiverIDs = $_POST['receiver_ids'];
			$content = $_POST['content'];
			$method = $_POST['method'];
			
			sendMessage($senderID, $receiverIDs, $content, $method);
			
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