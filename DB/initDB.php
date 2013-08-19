<?php
class DB
{
	protected $link=null;
	public $status=1;
	
	public function  __construct()
	{
		$dir = dirname(__FILE__);
		$ini_array = parse_ini_file("$dir/dbconf.inc.php");
		$host = $ini_array["host"];
		$user = $ini_array["user"];
		$password = $ini_array["password"];
		$this->link=mysql_connect($host,$user,$password);
		$res=mysql_select_db("cherryfull",$this->link);
		
		if(!$this->link || !$res)
		{
			$this->status=null;
			die("Mysql Connection Eror ".mysql_error());
		}
	}

	public function __destruct()
	{
//		mysql_close($this->link);
	}

}

?>