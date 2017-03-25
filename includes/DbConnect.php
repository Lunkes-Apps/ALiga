<?php

	class DbConnect{
		private $con;
		
		function __construct(){
			
		}	

        function connect(){
			include_once dirname(__FILE__).'/Constants.php';
			$this->con = new mysqli(HOST,USER,PASSWORD,DATABASE);
				
			if(mysqli_connect_errno()){
				echo "Falha ao conectar com o banco de dados".mysqli_connect_err();			
			}	
			return $this->con;
		}		
		
	}

?>