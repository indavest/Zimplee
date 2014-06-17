<?php

include_once 'API/php/PHPPost.php';

// parameter is 'pageNo' for the page no to be sent

$pageNo = $_REQUEST['pageNo'];

//sample session Id
//$sessionId = '5dMSWoLpHnXI3Rm-CAGQVEv1BwYqXyns9LlE3veIfEGfKZ';
$sessionId = $_REQUEST['session_id'];
//add something like below check to add custom page range view permissions (Chapter Rentals)
/*if($pageNo > 5){
    echo "page not allowed for viewing";
}else{*/
    $inst = new PHPPost();

    echo $inst->curlPage('proxy.docspad.com/'.$sessionId.'/html/page-'.$pageNo.'.html');
/*}*/

    

?>
