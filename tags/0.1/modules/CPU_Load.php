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
        $labels = Array(
            "1 min avg",
            "5 min avg",
            "10 min avg",
            "run proc/total proc",
            "last proc id"
        );
        return array("labels" => $labels, "data" => $this->average);
    }

    private function get_cpu_avg(){
        //XXX TODO find a Darwin/Windows way of doing this
        $response_string = shell_exec('cat /proc/loadavg');
        $response_array = explode(' ', $response_string);
        return $response_array;
    }
}
?>
