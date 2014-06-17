<?php

class PHPPost{
    public $ch;
    
    public function __construct() {
        $this->ch = curl_init();
    }
    
    public function __destruct() {
        curl_close($this->ch);
    }
    
    public function setProxy($server, $port, $username = FALSE, $password = FALSE, $type = 'HTTP'){
        curl_setopt($this->ch, CURLOPT_PROXYTYPE, $type);
        curl_setopt($this->ch, CURLOPT_PROXY, $server);
        curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);
        
        if($username)
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $username.':'.$password);
    }
    
    public function curlPage($url){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($this->ch);
                
        return $result;
    }

    public function postRequest($url, $postdata = FALSE, $headers = 0, $ssl = FALSE){
        // target url
        curl_setopt($this->ch, CURLOPT_URL, $url);
        
        // if headers
        curl_setopt($this->ch, CURLOPT_HEADER, $headers); // no headers in the output
        
        // return to variable
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        
        curl_setopt($this->ch, CURLOPT_POST, count($postdata));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postdata);
        
        $result = curl_exec($this->ch);
                
        return $result;
    }
    
    public function getRequest($url, $getdata = FALSE, $headers = 0, $ssl = FALSE){
        if(count($getdata)){
            $url = $url."?";
            foreach ($getdata as $key=>$val){
                $url = $url.'&'.$key.'='.$val;
            }
        }
        
        // target url
        curl_setopt($this->ch, CURLOPT_URL, $url);
        
        // if headers
        curl_setopt($this->ch, CURLOPT_HEADER, $headers); // no headers in the output
        
        // return to variable
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        
        $result = curl_exec($this->ch);
                
        return $result;
    }
    
}

?>