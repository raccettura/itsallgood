<?php

class Check_CPU_Load extends CheckBase {

    public function about(){
        $about = array(
            'title' => 'CPU Load',
            'author' => 'Robert Accettura',
            'url' => 'http://code.google.com/p/itsallgood/',
            'description' => 'Check CPU load for spikes',
        );
        return $about;
    }

    protected $config = array(
        'maxCPU' => 0.9
    );

    public function check(){
		$this->get_cpu_avg();
        if($this->average['1m'] > $this->config['maxCPU'] || !isset($this->average['1m'])){
            return false;
        }
        return true;
    }

    public function get_values(){
        if(!isset($this->average)){
            $this->check();
        }
        
        if(!isset($this->average)){
        	return "Unsupported OS";
        }
        
		$responseTypes = Array(
			'1m' => '1 min avg',
			'5m' => '5 min avg',
			'10m' => '10 min avg',
			'tproc' => 'run proc/total proc',
			'procId' => 'last proc id',
		);

		$responseArray = Array();
		foreach($responseTypes as $key => $label){
			if(isset($this->average[$key])){
				$responseArray[$key] = array($label, $this->average[$key]);
			}
		}

		return $responseArray;
    }

    private function get_cpu_avg(){
		$osString = strtolower(php_uname());

        // This means we're running some sort of Linux based OS
        if(strpos($osString, 'linux') !== false){
	        $response_string = shell_exec('cat /proc/loadavg');
	        list($this->average['1m'], $this->average['5m'], $this->average['10m'], $this->average['tproc'], $this->average['procId']) = explode(' ', $response_string);
			return true;
        }
        
        // For BSD/Mac OS X
        else if(strpos($osString, 'bsd') !== false || strpos($osString, 'darwin') !== false){
	        $response_string = shell_exec('sysctl -n vm.loadavg');
	        list($foo, $this->average['1m'], $this->average['5m'], $this->average['10m'], $foo) = explode(' ', $response_string);
			return true;
        }

		// Nothing found
		return false;
    }
}
?>
