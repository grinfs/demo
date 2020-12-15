<?php 
/**
 * lớp kết nối và truy vấn cơ sở sữ liệu
 */
class connectDB
{
	private $hostName='localhost:3306';
	private $userName='root';
	private $passWord='';
	private $dbName='1XXXXXX_1XXXXXX_btn';

	private $db = NULL;

	public function getConnect()
	{
		if ($this->db == NULL) {
			$this->db = new PDO("mysql:host=$this->hostName;dbname=$this->dbName;charset=utf8",$this->userName,$this->passWord);
			$this->db->setAttribute(PDO::ATTR_TIMEOUT,ini_get('max_execution_time'));
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			ini_set('mysql.connect_timeout', 300);
			ini_set('default_socket_timeout', 300); 
			mysql_query("SET @@session.wait_timeout=900", $this->db);
			if(php_sapi_name() == 'cli'){
				$query = $this->db->prepare("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");
				$query->execute();
			}
		}
		
		return $this->db;
	}

	public function __construct()
	{	
		$this->db = new PDO("mysql:host=$this->hostName;dbname=$this->dbName;charset=utf8",$this->userName,$this->passWord);
		$this->db->setAttribute(PDO::ATTR_TIMEOUT,ini_get('max_execution_time'));
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(php_sapi_name() == 'cli'){
			$query = $this->db->prepare("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");
			$query->execute();
		}
	}

	public function __destruct()
	{
		$this->db = NULL;
	}
}
?>