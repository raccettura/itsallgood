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
        $this->average = $this->get_cpu_avg();
        if($this->average[1] > $this->config['maxCPU']){
            return false;
        }
        return true;
    }

    public function get_values(){
        if(!isset($this->average)){
            $this->check();
        }
        return array(
            '1m' => array('1 min avg', $this->average[0]),
            '5m' => array('5 min avg', $this->average[1]),
            '10m' => array('10 min avg', $this->average[2]),
            'tproc' => array('run proc/total proc', $this->average[3]),
            'procId' => array('last proc id', $this->average[4])
        );
    }

    private function get_cpu_avg(){
        //XXX TODO find a Darwin/Windows way of doing this
        $response_string = shell_exec('cat /proc/loadavg');
        $response_array = explode(' ', $response_string);
        return $response_array;
    }
}
?>
