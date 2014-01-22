<?php

require_once('base.php');

class DBManager
{
	private $_link;
	private static $_manager;
	
	public function __construct()
	{		
		$site = 'www.tearsofphoenix.com';		
		$this->_link = mysql_connect($site, 'tearsofphoenix', 'veritasLXM1128')
	    or fail('Could not connect: ' . mysql_error());
		
		mysql_select_db('grace_road', $this->_link) or die('Could not select database');
	}
	
	public function __destruct()
	{
		mysql_close($this->_link);
	}
	
	public static function manager()
	{
		if(!self::$_manager)
		{
			self::$_manager = new DBManager();
		}
		
		return self::$_manager;
	}
	
	public function runQuery($query, $callback, $context)
	{
		mysql_query("SET NAMES 'utf8'");
		
/* 		success($query, 'debug'); */
		
		$result = mysql_query($query, $this->_link) or die('Query failed: ' . mysql_error());
				
		if ($callback)
		{
			call_user_func_array($callback, array($this, $result, $context));
		}
				
		mysql_free_result($result);		
	}
};

?>