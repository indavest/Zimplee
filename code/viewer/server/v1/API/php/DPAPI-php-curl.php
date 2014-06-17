<?php

class DPAPI{
    private $uploadURL;
    private $statusURL;
    private $sessionURL;
    private $accessKey;

    public function __construct($key) {
        $this->uploadURL = 'http://apis.docspad.com/v1/upload.php';
        
        $this->statusURL = 'http://apis.docspad.com/v1/status.php';
        
        $this->sessionURL = 'http://apis.docspad.com/v1/session.php';
        
        $this->accessKey = $key;
    }
    
    public function uploadURL($docURL){
        $docId = shell_exec('curl -F docURL='.$docURL.' -F key='.$this->accessKey.' '.$this->uploadURL);
        
        return $docId;
    }
    
    public function statusCheck($docId){
        $status = shell_exec('curl -F docId='.$docId.' -F key='.$this->accessKey.' '.$this->statusURL);
        
        return $status;
    }
    
    public function sessionCreate($docId){
        $session = shell_exec('curl -F docId='.$docId.' -F key='.$this->accessKey.' '.$this->sessionURL);
        
        return $session;
    }
}



?>
