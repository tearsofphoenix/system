<?php

require_once('base.php');

function sendPushNotification($deviceToken, $message)
{
	// Put your private key's passphrase here:
	$passphrase = '3141';
		
	////////////////////////////////////////////////////////////////////////////////
	
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	
	// Open a connection to the APNS server
	$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', 
							   $err,
							   $errstr, 
							   60, 
							   STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
							   $ctx);
	
	if (!$fp)
	{
		fail("Failed to connect: $err $errstr" . PHP_EOL);
		return;
	}
	
	// Create the payload body
	$body['aps'] = array(
		'alert' => $message,
		'sound' => 'default'
		);
	
	// Encode the payload as JSON
	$payload = json_encode($body);
	
	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
	
	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));
	
	if (!$result)
	{
		fail('failed to deliver message!' . PHP_EOL);
	}else
	{
		success('delivered message!' . PHP_EOL);
	}
	
	// Close the connection to the server
	fclose($fp);
}

function testPush()
{
	$deviceToken = '04e7338bd3e7e3f191ffc6319b78eec9d39dcd8149bd05655c4ca55d598d8749';
	sendPushNotification($deviceToken, 'haha!');
}

?>