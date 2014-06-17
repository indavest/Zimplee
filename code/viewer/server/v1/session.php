<?php

// session can be created only if the status API gives a status of COMPLETED

include_once 'API/php/DPAPI-php.php';

$docId = $_REQUEST['docId'];

// pass your API key to instantiate
$inst = new DPAPI('GymYytftOy7MUXDk');

echo $inst->sessionCreate($docId);

?>
