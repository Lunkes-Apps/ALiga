<?php

require_once 'includes/DbOperationsPDO.php';
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['idUser'],$_POST['nomeLiga'],$_POST['data'],$_POST['idCampeonato'])){
		$db = new DbOperationsPDO();
	    $ok = $db->criarLiga($_POST['idUser'],$_POST['nomeLiga'],$_POST['data'],$_POST['idCampeonato']);
        if($ok == 1){
			$response['error']=false;
			$response['message']="Liga criada com sucesso!";
		}else{
			if($ok == 0){
				$response['error']=true;
				$response['message']="Já existe uma liga com esse nome!";
			}elseif($ok == 2){
				$response['error']=true;
				$response['message']="Erro ao criar Liga";
			}			
		}
    }elseif(isset($_POST['campeonato'])){
		$db = new DbOperationsPDO();
	    $ok = $db->criarCamp($_POST['campeonato']);
		if($ok == 1){
				$response['error']=false;
				$response['message']="Campeonato criado com sucesso!";
		}else{
			if($ok == 0){
				$response['error']=true;
				$response['message']="Já existe um campeonato com esse nome!";
			}elseif($ok == 2){
				$response['error']=true;
				$response['message']="Erro ao criar campeonato";
			}			
		}
	}elseif(isset($_POST['idUser2'],$_POST['idLiga2'],$_POST['message'],$_POST['data'],$_POST['hora'])){
		$db = new DbOperationsPDO();
	    $ok = $db->inserirComent($_POST['idUser2'],$_POST['idLiga2'],$_POST['message'],$_POST['data'],$_POST['hora']);
		if($ok == 1){
				$response['error']=false;
				$response['message']="Comentário adicionado com sucesso!";
		}else{
			if($ok == 2){
				$response['error']=true;
				$response['message']="Erro ao adicionar Comentário!";
			}		
		}		
	}elseif(isset($_POST['idUser'],$_POST['idLiga'])){
		$db = new DbOperationsPDO();
	    $ok = $db->inserirUserOnLiga($_POST['idUser'],$_POST['idLiga']);
		if($ok == 1){
				$response['error']=false;
				$response['message']="Adicionado na Liga com sucesso!";
		}else{
			if($ok == 0){
				$response['error']=true;
				$response['message']="Participante ja consta na Liga!";
			}elseif($ok == 2){
				$response['error']=true;
				$response['message']="Erro ao adicionar participante";
			}			
		}
	}elseif(isset($_POST['pedido'])){
		$db = new DbOperationsPDO();
	    if($_POST['pedido']=="ligas-admin"){
			
			if(isset($_POST['idUser'])){
				$response = $db->listarLigasByUserAdmin($_POST['idUser']);
			}
		}elseif($_POST['pedido']=="ligas-user-inscrito"){
			
			if(isset($_POST['idUser'])){
				$response = $db->listarLigasByUser($_POST['idUser']);
			}
		}elseif($_POST['pedido']=="ligas-comentarios"){
			
			if(isset($_POST['idLiga'])){
				$response = $db->listarComentariosByLiga($_POST['idLiga']);
			}
		}elseif($_POST['pedido']=="ligas-participantes"){
			
			if(isset($_POST['idLiga'])){
				$response = $db->listarParticipantesByLiga($_POST['idLiga']);
			}
		}elseif($_POST['pedido']=="campeonatos"){
			$response = $db->listarCampeonatos();
		}
	}

	
}else{

			$db = new DbOperationsPDO();
			$partidas = array();
			$partidas = $db->listOfParticipantes();
			$response = $partidas;	
	
}


echo json_encode($response);


?>