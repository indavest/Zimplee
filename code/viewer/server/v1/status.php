<?php

include_once 'API/php/DPAPI-php.php';

$docId = $_REQUEST['docId'];

// pass your API key to instantiate
$inst = new DPAPI('YOUR_API_KEY');

echo $inst->statusCheck($docId);


?>
