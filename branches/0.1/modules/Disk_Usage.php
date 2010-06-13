<?php

class Check_Disk_Usage extends CheckBase {

    public function about(){
        $about = array(
            'title' => 'Disk Usage',
            'author' => 'Robert Accettura',
            'url' => 'http://code.google.com/p/itsallgood/',
            'description' => 'Check Disk Usage that it doesn\'t exceed a threshold',
        );
        return $about;
    }

    protected $config = array(
        'threshold' => '90%',
	'volume'    => '/'
    );

    public function check(){
        $this->getData();

        if(substr($this->config['threshold'], -1) == '%'){
            $percentage = substr($this->config['threshold'], 0, -1);
            $this->data['diff'] =  100 - ($this->data['free']/$this->data['total'])*100;
            if($this->data['diff'] > $percentage){
                return false;
            }
            return true;
        } else {
            $this->data['diff'] = $this->data['total'] - $this->data['free'];
            if($this->data['diff'] < $this->config['threshold']){
                return false;
            }
            return true;
        }
    }

    public function get_values(){
        if(!isset($this->data)){
            $this->check();
        }
        $labels = Array(
            "free" => "Free",
            "total" => "Total",
            "diff" => "Difference"
        );
        return Array("labels" => $labels, "data" => $this->data);
    }

    private function getData(){
        $this->data = Array();
        $this->data['free'] = disk_free_space($this->config['volume']);
	$this->data['total'] = disk_total_space($this->config['volume']);
        return true;    
    }
}
?>
