<?php

class Redeem_Log_model extends Common_model{

    var $table = 't_redeem_log';

    public function __construct(){
        parent::__construct();
    }

    public function all($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->limit(10,$start)->select();
        $count = $this->where($where)->count();
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }


}