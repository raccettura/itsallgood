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
        $this->result = $this->_fetchHttpUrl($this->config['url'], $this->config['timeout']);
        if($this->_HttpResponse &&  $this->analyzePage()){
            return true;
        }
        return false;
    }

    public function get_values(){
        if(!isset($this->_HttpError)){
            $this->check();
        }
        if($this->_HttpError == ''){
            return 'Success';
        }
        return $this->_HttpError;
    }

    private function analyzePage(){
        if($this->config['contains']){
        	if(strpos($this->_HttpResponse, $this->config['contains']) !== false){
                    return true;
                }
                return false;
        }
        else if($this->config['not']){
                if(strpos($this->_HttpResponse, $this->config['not']) !== false){
       	       	    return false;
       	       	}
                return true;
        }
        return true;
    }
}
?>
