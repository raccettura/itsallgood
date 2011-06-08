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
        if($this->result &&  $this->analyzePage()){
            return true;
        }
        return false;
    }

    public function get_values(){
        if(!isset($this->results)){
            $this->check();
        }
        if($this->results == ''){
            return 'Success';
        }
        return $this->results;
    }

    private function analyzePage(){
        if($this->config['contains']){
        	if(strpos($this->response, $this->config['contains']) !== false){
                    return true;
                }
                return false;
        }
        else if($this->config['not']){
                if(strpos($this->response, $this->config['not']) !== false){
       	       	    return false;
       	       	}
                return true;
        }
        return true;
    }
}
?>
