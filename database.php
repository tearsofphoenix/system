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
	    or fail("Ephesians 4:15(KJV) 'But speaking the truth in love, may grow up into him in all things, which is the head, even Christ:'." . mysql_error());
		
		mysql_select_db('grace_road', $this->_link) or die("Genesis 11:30(KJV) 'But Sarai was barren; she had no child.'");
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
		
		$result = mysql_query($query, $this->_link) or die("Joshua 9:14(KJV) '..and asked not counsel at the mouth of the LORD.'" . mysql_error());
				
		if ($callback)
		{
			call_user_func_array($callback, array($this, $result, $context));
		}
				
		mysql_free_result($result);		
	}
};

?>