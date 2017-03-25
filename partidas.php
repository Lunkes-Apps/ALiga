<?php

require_once 'includes/DbOperationsPDO.php';
require_once 'includes/Calculadora.php';
$response = array();
$messages = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(isset($_POST['rodada'])){
			$db = new DbOperationsPDO();
			$partidas = array();
			$partidas = $db->partidasByRodada($_POST['rodada']);
			$response = $partidas;
			
		}elseif(isset($_POST['idJogo'],$_POST['placarA'],$_POST['placarB'])){
			$db = new DbOperationsPDO();
			if($db->atualizarPlacar($_POST['idJogo'],$_POST['placarA'],$_POST['placarB'])){
				$response['error'] = false;
				$response['message'] = "Jogo de id ".$_POST['idJogo']." foi atualizado com sucesso!";
			}else{
				$response['error'] = true;
				$response['message'] = "Jogo de id ".$_POST['idJogo']." não foi atualizado!";
			}
            $calcPontos = new Calculadora();
			echo "chegando idJogo ".$_POST['idJogo']."</br>";		
			$calcPontos->atualizarPontos($_POST['idJogo']);
			$response['message_2'] = "Pontuacao atualizada!!!";
		}		
		
	}else{
		$db = new DbOperationsPDO();
	    $response = $db->todasPartidas();        		
	}
echo json_encode($response);

?>