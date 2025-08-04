<?php
class Response{
    private $_success;
    private $_httpStatusCode;
    private $_message = array();
    private $_data="";
    private $_toCache = false;
    private $_responseData = array();

    public function setSuccess($success){
        $this->_success = $success;
    }

    public function setHTTPStatusCode($httpStatusCode){
        $this->_httpStatusCode = $httpStatusCode;
    }

    public function addMessage($message){
        $this->_message[]=$message;
    }

    public function setData($data){
        $this->_data = $data;
    }
        
    public function toCache($toChace){
        $this->_toCache = $toChace;
    }

    public function send(){
        header('Content-type: application/json;charset=utf-8');
        if($this->_toCache==true){
            header('Cache-control:max-age=60');
        }else{
            header('Cache-control:no-cache, no-store');
        }

        if(($this->_success !== false && $this->_success!==true) || 
        !is_numeric($this->_httpStatusCode)){
            http_response_code(201);
            $this->_responseData['statusCode']= '201';
            $this->_responseData['success']= false;
            $this->addMessage("Response creation error");
            $this->_responseData['messages'] = $this->_message;
        }
        else{
            http_response_code($this->_httpStatusCode);
            $this->_responseData['statusCode']= $this->_httpStatusCode;
            $this->_responseData['success']= $this->_success;
            $this->_responseData['messages'] = $this->_message;
            $this->_responseData['data'] = $this->_data;
        }
        echo json_encode($this->_responseData);
        exit;
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP') && getenv('HTTP_CLIENT_IP')  != "::1")
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR') && getenv('HTTP_X_FORWARDED_FOR')  != "::1")
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED') && getenv('HTTP_X_FORWARDED')  != "::1")
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR') && getenv('HTTP_FORWARDED_FOR')  != "::1")
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED') && getenv('HTTP_FORWARDED')  != "::1")
        $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR') && getenv('REMOTE_ADDR')  != "::1")
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;  
    }
}
?>