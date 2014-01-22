<?php

require_once('uuid.php');

function success($data, $messageID)
{
	if ($data)
	{
		echo(json_encode(array('status' => '0', 'data' => $data, 'messageID' => $messageID)));
	}else
	{
		echo(json_encode(array('status' => '0', 'messageID' => $messageID)));
	}
}

function fail($reason, $messageID)
{
	echo(json_encode(array('status' => '-1', 'reason' => $reason, 'messageID' => $messageID)));
}

?>