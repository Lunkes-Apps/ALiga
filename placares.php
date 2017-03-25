<?php

require_once 'includes/DbOperationsPDO.php';
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	
}else{
	$db = new DbOperationsPDO();
	$response = $db->listarPlacares();		
}

echo json_encode($response);



?>