<?php

include_once 'API/php/PHPPost.php';

// sample session Id
//$sessionId = '5dMSWoLpHnXI3Rm-CAGQVEv1BwYqXyns9LlE3veIfEGfKZ';
$sessionId = $_REQUEST['session_id'];

$inst = new PHPPost();

echo $inst->curlPage('proxy.docspad.com/'.$sessionId.'/html/page.html');

?>
