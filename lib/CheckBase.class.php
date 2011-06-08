<?php

/* all methods in this base class should be prefixed with '_' */
class CheckBase {

    protected $config = array();

    public function __construct($config, &$logRef){
        $this->config = $config;
        $this->logRef = $logRef;
    }

    protected function _log($message, $verbosity = 1){
        // We pass the name of the class in as the environment
        $this->logRef->log($message, $verbosity, get_class($this));
    }

    protected function _fetchHttpUrl($url, $timeout=15){
        if(!function_exists("curl_init")){
            $this->_HttpError = "cURL is not installed";
            $this->_log($this->_HttpError, 2);
            return false;
        }

        // Initialize session and set URL.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
	
        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
        // Timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLE_OPERATION_TIMEOUTED, $timeout);
	
        // URL
        curl_setopt($ch, CURLOPT_URL, $url);
	
        // Follow location
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
        // SSL Verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	
	
        $this->_HttpResponse = curl_exec($ch);
        $this->_HttpError = curl_error($ch);
        if(strlen($this->_HttpResponse) > 0){
            return false;
        }
        return true;
    }
}

?>
