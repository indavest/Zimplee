<?php 
	//create session for docspadid - vCFqSRq68D - PjvoLsPTOvKG8Kr
    //$session_id = $_GET['id'];
	$session_id = json_decode(file_get_contents("http://localhost/zimplee/trunk/viewer/server/v1/session.php?docId=vCFqSRq68D"))->sessionId;
	echo $session_id;	
?>
