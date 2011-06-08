<?php

class Check_HTTP extends CheckBase {

    public function about(){
        $about = array(
            'title' => 'Check HTTP',
            'author' => 'Robert Accettura',
            'url' => 'http://code.google.com/p/itsallgood/',
            'description' => 'Uses curl to fetch an analyze a page to see if it was retreived successfully or not.',
        );
        return $about;
    }

    protected $config = array(
        'url'         => 'localhost',
        'timeout'     => 15,
        'contains'    => null,
        'not'         => null
    );

    public function check(){
        $this->result = $this->fetchHttpUrl($this->config['url'], $this->config['timeout']);
        if($this->HttpResponse &&  $this->analyzePage()){
            return true;
        }
        return false;
    }

    public function get_values(){
        if(!isset($this->HttpError)){
            $this->check();
        }
        if($this->HttpError == ''){
            return 'Success';
        }
        return $this->HttpError;
    }

    private function analyzePage(){
        if($this->config['contains']){
        	if(strpos($this->HttpResponse, $this->config['contains']) !== false){
                    return true;
                }
                return false;
        }
        else if($this->config['not']){
                if(strpos($this->HttpResponse, $this->config['not']) !== false){
       	       	    return false;
       	       	}
                return true;
        }
        return true;
    }

    protected function fetchHttpUrl($url, $timeout=15){
        if(!function_exists("curl_init")){
            $this->HttpError = "cURL is not installed";
            $this->_log($this->HttpError, 2);
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
	
	
        $this->HttpResponse = curl_exec($ch);
        $this->HttpError = curl_error($ch);
        if(strlen($this->HttpResponse) > 0){
            return false;
        }
        return true;
    }
}
?>
