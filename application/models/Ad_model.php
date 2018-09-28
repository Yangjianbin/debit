<?php

class Ad_model extends Common_model{

    var $table = 't_ad';

    public function __construct(){
        parent::__construct();
        $this->load->driver('cache',array('adapter'=>'memcached','key_prefix'=>'yd_'));
    }

    public function all($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->limit(10,$start)->select();
        $count = $this->where($where)->count();
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function allByCache($where = array()){
        $ad = $this->cache->get('ad');
        if(!$ad){
            $ad = (object) $this->where($where)->limit(10)->select();
            $this->cache->save('ad', $ad, 24 * 3600);
        }
        return $ad;
    }

    public function cleanCache(){
        $this->cache->delete('ad');
    }


}