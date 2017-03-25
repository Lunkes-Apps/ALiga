<?php
require_once 'includes/DbOperationsPDO.php';
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	
	if(isset($_POST['pedido'])){
		if($_POST['pedido']=="participantes"){
			$db = new DbOperationsPDO();
			$response = $db->listarParticipantes();
		}
		if($_POST['pedido']=="apostas" && isset($_POST['idRodada'])){
			$db = new DbOperationsPDO();
			$response = $db->listarApostasByRodada($_POST['idRodada']);
		}
	}	
	
}

echo json_encode($response);
?>