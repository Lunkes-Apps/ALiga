<?php

require_once 'includes/DbOperationsPDO.php';
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
   if(isset($_POST['idRodada'],$_POST['idUser'],$_POST['pontos'])){
	$db = new DbOperationsPDO();
	if($db->salvarPontuacao($_POST['idRodada'],$_POST['idUser'],$_POST['pontos'])){
		$response['error'] = false;
		$response['message'] = "Pontucao salva com sucesso";
	}else{
		$response['error'] = true;
		$response['message'] = "Pontucao nao foi salva";		
	}
   }else{
	   if(isset($_POST['idRodada'])){
		
		$db = new DbOperationsPDO();
		$response = $db->listarPontuacaoByRodada($_POST['idRodada']);
   }
   }
}else{
				
}

echo json_encode($response);


?>
