<?php


class DbOperationsPDO{ 
		
		private $con;
		
		function __construct(){
			
			require_once dirname(__FILE__).'/DbConnectPDO.php';
			$db = new DbConnectPDO();
			
			$this->con = $db->connect();
			
		}
		
		function partidasByRodada($rodada){
			$sql = "SELECT id FROM rodadas WHERE rodada = :r LIMIT 1"; 		
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam(':r', $rodada, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			$id_rodada = $result[0]['id'];	
			
			$sql = "SELECT * FROM partidas WHERE id_rodada = :id";
			
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam(':id', $id_rodada, PDO::PARAM_INT);
			$stmt->execute();
			$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
            return $partidas;			
		}
		
		function getJogo($id){
			
			// SELECT placares.placarA, placares.placarB, rodadas.rodada FROM placares
			// INNER JOIN partidas
			// ON placares.idJogo = partidas.id
			// INNER JOIN rodadas
			// ON partidas.id_rodada = rodadas.id
			// WHERE placares.idJogo = 1
						
			$tables = "placares.placarA, placares.placarB, rodadas.id, rodadas.rodada";
			$joinPartidas = " ON placares.idJogo = partidas.id";
			$joinRodadas = " ON partidas.id_rodada = rodadas.id";			
			$sql = "SELECT ".$tables." FROM placares INNER JOIN partidas".$joinPartidas." INNER JOIN rodadas ".$joinRodadas." WHERE placares.idJogo = :id LIMIT 1";
            $stmt = $this->con->prepare($sql);
			$stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			echo "Funcao getJogo </br>";
			echo "    ->placar A ".$result[0]['placarA']." </br>";
			echo "    ->placar B ".$result[0]['placarB']." </br>";
			
			return $result[0];			
		}
		
		function todasPartidas(){
			$sql = "SELECT * FROM partidas";
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
            return $partidas;			
		}
		
		function listOfParticipantes(){
			$sql = "SELECT nome FROM users";		
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC); 
			return $participantes;
		}
		
		function atualizarPlacar($idJogo, $placarA, $placarB){
			$sql;
			$sql2 = "SELECT id FROM placares WHERE idJogo = :id LIMIT 1";
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam(':id', $idJogo, PDO::PARAM_INT);
			$stmt->execute();
            if($stmt->rowCount()>0){
				$sql = "UPDATE placares SET placarA = :pA, placarB = :pB WHERE idJogo = :id";
			}else{
				$sql = "INSERT INTO placares(placarA, placarB, idJogo) VALUES (:pA,:pB,:id)";
			}
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam(':id', $idJogo, PDO::PARAM_INT);
			$stmt->bindParam(':pA', $placarA, PDO::PARAM_INT);
			$stmt->bindParam(':pB', $placarB, PDO::PARAM_INT);
			return $stmt->execute();            		
		}
		
		
		
		function apostar($idJogo, $placarA, $placarB, $idUser){
			$sql = "SELECT id FROM apostas WHERE idJogo = :id AND idUser = :idu LIMIT 1;";		
			$stmt = $this->con->prepare($sql);		
			$stmt->bindParam(':id', $idJogo, PDO::PARAM_INT);
			$stmt->bindParam(':idu', $idUser, PDO::PARAM_INT);
			$stmt->execute();
			
			if($stmt->rowCount()>0){
				$sql2 = "UPDATE apostas SET placarA = :pA, placarB = :pB WHERE idJogo = :id AND idUser = :idu";
			}else{
				$sql2 = "INSERT INTO apostas(placarA, placarB, idJogo, idUser) VALUES (:pA,:pB,:id,:idu)";
			}
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam(':id', $idJogo, PDO::PARAM_INT);
			$stmt->bindParam(':pA', $placarA, PDO::PARAM_INT);
			$stmt->bindParam(':pB', $placarB, PDO::PARAM_INT);
			$stmt->bindParam(':idu', $idUser, PDO::PARAM_INT);
			
			return $stmt->execute();		
		}
		
		function listarApostasByUser($idUser){
			$sql = "SELECT * FROM apostas WHERE idUser = :idu";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('idu',$idUser,PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		
		function apostasByJogo($idJogo){
			$sql = "SELECT * FROM apostas WHERE idJogo = :idj";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('idj',$idJogo,PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
				
		function listarPlacares(){
			$sql = "SELECT * FROM placares";
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;	
		}
		
		function listarRodadas(){
			$sql = "SELECT * FROM rodadas";
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;	
		}
		
		function pontosByUserAndRodada($idUser,$idRodada){
			$sql="SELECT apostas.pontos FROM apostas
			INNER JOIN partidas
			ON apostas.idJogo = partidas.id
			INNER JOIN rodadas
			ON partidas.id_rodada = rodadas.id
			WHERE apostas.idUser = :idu AND
			rodadas.rodada = :idr;";		
						
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('idu',$idUser,PDO::PARAM_INT);
            $stmt->bindParam('idr',$idRodada,PDO::PARAM_INT);			
			$stmt->execute();					
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$c = count($result); 
			$total = 0;
			if($c > 0){
				for($i = 0; $i<$c; $i++){
					$total+= $result[$i]['pontos'];
				}				 
			}else{			
				return 0;
			}				
		}	
		
		function salvarPontuacao($idJogo, $idUser, $pontos){
			$sql2 = "UPDATE apostas SET pontos = :p WHERE idJogo = :idj AND idUser = :idu";
			
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam('idj',$idJogo,PDO::PARAM_INT);
			$stmt->bindParam('idu',$idUser,PDO::PARAM_INT);
			$stmt->bindParam('p',$pontos,PDO::PARAM_INT);
						
			return $stmt->execute();          	    			
		}
		
		function listarParticipantes(){
			$sql = "SELECT id, nome FROM users";
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		function listarApostasByRodada($idRodada){
			$sql = "SELECT apostas.id, apostas.idUser, apostas.placarA, apostas.placarB, apostas.idJogo, apostas.pontos, rodadas.rodada
				FROM apostas
				INNER JOIN partidas 
				ON apostas.idJogo = partidas.id
				INNER JOIN rodadas
				ON partidas.id_rodada = rodadas.id
				WHERE rodadas.id = :idr";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('idr',$idRodada,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);				
		}
		
		
		function criarLiga($idUser,$nomeLiga,$data,$idCamp){
			$sql = "SELECT * FROM ligas WHERE nome = :n LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('n',$nomeLiga,PDO::PARAM_STR);
			$stmt->execute();			
			if($stmt->rowCount()>0){
				return 0;
			}else{
				$sql2 = "INSERT INTO ligas(nome,idAdmin,dataCriac,campeonato) VALUES (:n,:idU,:d,:c)";
			}
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam(':n',$nomeLiga,PDO::PARAM_STR);
			$stmt->bindParam(':idU',$idUser,PDO::PARAM_INT);
			$stmt->bindParam(':d',$data,PDO::PARAM_STR);
			$stmt->bindParam(':c',$idCamp,PDO::PARAM_INT);
			if($stmt->execute()){
				$idLiga = getLigaIdByName($nomeLiga);
				inserirUserOnLiga($idUser,$idLiga);
				return 1;
			}else{
				return 2;
			}
									
		}
		
		function criarCamp($camp){
			$sql = "SELECT * FROM campeonatos WHERE nomeCamp = :n LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('n',$camp,PDO::PARAM_STR);
			$stmt->execute();			
			if($stmt->rowCount()>0){
				return 0;
			}else{
				$sql2 = "INSERT INTO campeonatos(nomeCamp) VALUES (:n)";
			}
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam(':n',$camp,PDO::PARAM_STR);
			if($stmt->execute()){
				return 1;
			}else{
				return 2;
			}			
		}
		
		function inserirUserOnLiga($idUser,$idLiga){
			$sql = "SELECT * FROM `participantes-liga` WHERE idUser = :u AND idLiga = :l LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('u',$idUser,PDO::PARAM_INT);
			$stmt->bindParam('l',$idLiga,PDO::PARAM_INT);
			$stmt->execute();			
			if($stmt->rowCount()>0){
				return 0;
			}else{
				$sql2 = "INSERT INTO `participantes-liga`(idUser, idLiga) VALUES (:u, :l)";
			}
			$stmt = $this->con->prepare($sql2);
			$stmt->bindParam('u',$idUser,PDO::PARAM_INT);
			$stmt->bindParam('l',$idLiga,PDO::PARAM_INT);
			if($stmt->execute()){
				return 1;
			}else{
				return 2;
			}					
		}
		
		function inserirComent($idUser, $idLiga, $message, $data, $hora){
			
			$sql = "INSERT INTO `comentarios-liga` (idLiga, idUser, message, data, hora) VALUES (:l,:u,:m,:d,:h)";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('l',$idLiga,PDO::PARAM_INT);
			$stmt->bindParam('u',$idUser,PDO::PARAM_INT);
			$stmt->bindParam('m',$message,PDO::PARAM_STR);
			$stmt->bindParam('d',$data,PDO::PARAM_STR);
			$stmt->bindParam('h',$hora,PDO::PARAM_STR);
			if($stmt->execute()){
				return 1;
			}else{
				return 2;
			}			
		}
		
		function listarLigasByUserAdmin($idUser){
			$sql = "SELECT * FROM ligas WHERE idAdmin = :id";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('id',$idUser,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);					
		}
		
		function listarLigasByUser($idUser){
			$sql = "SELECT ligas.id, ligas.nome, ligas.idAdmin, ligas.dataCriac, ligas.campeonato 
				FROM `participantes-liga`
				INNER JOIN ligas 
				ON `participantes-liga`.idLiga = ligas.id
				WHERE `participantes-liga`.idUser = :idu";
		
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('idu',$idUser,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);			
		}
		
		function listarComentariosByLiga($idLiga){
			$sql = "SELECT * FROM `comentarios-liga` WHERE idLiga = :id";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('id',$idLiga,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);			
		}
		
		function listarParticipantesByLiga($idLiga){
			$sql = "SELECT users.NOME, users.EMAIL 
				FROM `participantes-liga` 
				INNER JOIN users 
				ON `participantes-liga`.idUser = users.id				
				WHERE `participantes-liga`.idLiga = :id";
		
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('id',$idLiga,PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);			
		}
		
		function getLigaIdByName($name){
			$sql = "SELECT * FROM ligas WHERE nome = :n LIMIT 1";
			$stmt = $this->con->prepare($sql);
			$stmt->bindParam('n',$name,PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result[0]['id'];			
		}
		
		function listarCampeonatos(){
			$sql = "SELECT * FROM campeonatos";
			$stmt = $this->con->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
}



?>