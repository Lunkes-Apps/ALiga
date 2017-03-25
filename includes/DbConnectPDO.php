<?php

	class DbConnectPDO{
		private $con;
		
		function __construct(){
			
		}	

        function connect(){
			include_once dirname(__FILE__).'/Constants.php';
			$dsn = 'mysql: host='.HOST.'; dbname='.DATABASE;
			$this->con = new PDO($dsn,USER,PASSWORD);
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						
			return $this->con;
		}		
		
	}
	
?>
	