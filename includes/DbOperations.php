<?php

	class DbOperations{
		
		private $con;
		
		function __construct(){
			
			require_once dirname(__FILE__).'/DbConnect.php';
			$db = new DbConnect();
			
			$this->con = $db->connect();
			
		}

			
        function createUser($username, $pass, $email){
			if($this->isUserExist($username,$email)){
				return 0;
			}else{
				$password = md5($pass);
				$sql = "INSERT INTO `users` (`nome`, `email`, `password`) VALUES (?,?,?)";
				$stmt = $this->con->prepare($sql);
				$stmt->bind_param("sss",$username,$email,$password);

				if($stmt->execute()){
					return 1;				
				}else{
					return 2;
				}
			}			
		}	
		
		function userLogin($username, $pass){
			$stmt = $this->con->prepare("SELECT * FROM users WHERE nome = ? AND password = ? LIMIT 1");
			$password = md5($pass);
			$stmt->bind_param("ss",$username,$password);
			$stmt->execute();
			$stmt->store_result();			
			return $stmt->num_rows > 0;
		}

		public function getUserByUsername($username){
			$stmt = $this->con->prepare("SELECT id, nome, email FROM users WHERE nome = ? LIMIT 1");
			$stmt->bind_param("s",$username);
			$stmt->execute();
						
			$stmt->bind_result($id,$nome,$email);			
			
			$result = array();
			
			while($stmt->fetch()){
				$result['id']=$id;
				$result['nome']=$nome;
				$result['email']=$email;
			}
			
			return $result;
		}
		
		function isUserExist($username,$email){
			$sql = "SELECT * FROM users WHERE nome = ? OR email = ? LIMIT 1";
			$stmt = $this->con->prepare($sql);			
			$stmt->bind_param("ss",$username,$email);
			$stmt->execute();
			$stmt->store_result();
			
			return $stmt->num_rows > 0;
		}	
		
		function partidasByRodada($rodada){
			$sql = "SELECT id FROM rodadas"; 		
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($id);
			
			echo $stmt->fetch();
			echo $stmt->fetch();
			
			
			
			
			return $lista_partidadas;			
		}
		
	}
?>