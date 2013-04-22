<?php

class Check_Memory extends CheckBase {

    public $about = array(
        'title' => 'Memory',
        'author' => 'Robert Accettura',
        'url' => 'http://code.google.com/p/itsallgood/',
        'description' => 'Check free/used memory',
    );

    protected $config = array(
        'threshold' => '25%' 
    );

    protected $stats = array();

    public function check(){
        $this->get_memory_info();

        $percentage = $this->_percecentToInteger($this->config['threshold']);

        // Free memory + Buffers + Cache = total free memory.  Likely need to condition this logic based on OS at some point when non-Linux support is provided
        if( ( ($this->stats['MemFree']['value'] + $this->stats['Buffers']['value'] + $this->stats['Cached']['value']) / $this->stats['MemTotal']['value'] ) * 100 < $percentage){
           return false;
        }

        return true;
    }

    public function get_values(){
        if(!isset($this->stats)){
            $this->check();
        }

        if(!isset($this->stats)){
            return "Unsupported OS";
        }
        
        $responseTypes = Array(
            'MemTotal' => 'Total Memory',
            'MemFree' => 'Free Memory',
            'Buffers' => 'Buffers',
            'Cached' => 'Cached',
            'SwapTotal' => 'Swap Total',
            'SwapFree' => 'Swap Free'
        );

        $responseArray = Array();
        foreach($responseTypes as $key => $label){
            if(isset($this->stats[$key])){
                $responseArray[$key] = array($label, $this->stats[$key]['value'] . ' ' . $this->stats[$key]['measurement']);
            }
        }

        return $responseArray;
    }

    private function get_memory_info(){
        $osString = strtolower(php_uname());

        // This means we're running some sort of Linux based OS
        if(strpos($osString, 'linux') !== false){
            $response_string = file_get_contents('/proc/meminfo');
            $response_array = explode("\n", $response_string);
            $stats = array();
            foreach($response_array as $response_item){
                $response = explode(':', $response_item);
                list($value, $measurement) = explode(' ', trim($response[1]));
                $stats[$response[0]] = array('value' => floatval($value), 'measurement' => $measurement);
            }

            $this->stats = $stats;
            return true;
        }
        
        // For BSD/Mac OS X
        //else if(strpos($osString, 'bsd') !== false || strpos($osString, 'darwin') !== false){
        //}

        // Nothing found
        return false;
    }
}
?>
