<?php

require_once 'includes/DbOperationsPDO.php';
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['idJogo'],$_POST['placarA'],$_POST['placarB'],$_POST['idUser'])){
		$db = new DbOperationsPDO();
		if($db->apostar($_POST['idJogo'],$_POST['placarA'],$_POST['placarB'],$_POST['idUser'])){
			$response['error'] = false;
			$response['message'] = "Aposta salva com sucesso!";
		}else{
			$response['error'] = true;
			$response['message'] = "A aposta não foi salva";
		}							
	}elseif(isset($_POST['idUser'])){
		$db = new DbOperationsPDO();
		$response = $db->listarApostasByUser($_POST['idUser']); 
	}else{
		$response['error'] = true;
		$response['message'] = "Os campos estão faltando ser preenchidos";
	}
}else{
		$response['error'] = true;
		$response['message'] = "Os campos estão faltando ser preenchidos";
	}

echo json_encode($response);

?>