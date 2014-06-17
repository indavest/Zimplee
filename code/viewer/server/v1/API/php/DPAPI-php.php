<?php

include_once 'PHPPost.php';

class DPAPI{
    private $uploadURL;
    private $statusURL;
    private $sessionURL;
    private $accessKey;

    public function __construct($key) {
        $this->uploadURL = 'http://apis.docspad.com/v1/upload.php';
        
        $this->statusURL = 'http://apis.docspad.com/v1/status.php';
        
        $this->sessionURL = 'http://apis.docspad.com/v1/session.php';
        
        $this->deleteURL = 'http://apis.docspad.com/v1/delete.php';
        
        $this->accessKey = $key;
    }
    
    public function uploadURL($docURL){
        $inst = new PHPPost();
        
        $arr = array('docURL' => $docURL, 'key'=> $this->accessKey);
        
        $docId = $inst->postRequest($this->uploadURL, $arr);
        
        return $docId;
    }
    
    public function statusCheck($docId){
        $inst = new PHPPost();
        
        $arr = array('docId' => $docId, 'key'=> $this->accessKey);
        
        $status = $inst->postRequest($this->statusURL, $arr);
        
        return $status;
    }
    
    public function sessionCreate($docId){
        $inst = new PHPPost();
        $arr = array('docId' => $docId, 'key'=> $this->accessKey);

        $session = $inst->postRequest($this->sessionURL, $arr);
        
        return $session;
    }
    
    public function deleteDocument($docId){
        $inst = new PHPPost();
        
        $arr = array('docId' => $docId, 'key'=> $this->accessKey);
        
        $flag = $inst->postRequest($this->deleteURL, $arr);
        
        return $flag;
        
    }
    
    public function deleteSession($sessionId){
        $inst = new PHPPost();
        
        $arr = array('sessionId' => $sessionId, 'key'=> $this->accessKey);
        
        $flag = $inst->postRequest($this->deleteURL, $arr);
        
        return $flag;
    }
}



?>
