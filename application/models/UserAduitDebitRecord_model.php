<?php

class UserAduitDebitRecord_model extends Common_model{

    var $table = 'IFUserAduitDebitRecord';

    public function __construct(){
        parent::__construct();
    }

    public function all($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'select a.*,b.* from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId';
        if (!empty($where)) {
            //TODO
        }
        $sql.=' limit '.$start.' , 10';
        $query = $this->query($sql);

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId';
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function all2($where = array())
    {
        $sql = 'SELECT * from IFUserAduitDebitRecord WHERE DebitId IN (SELECT DebitId from IFUserDebitRecord WHERE UserId = '.$where['userId'].') order by auditTime desc limit 100';
        $query = $this->query($sql);
        $data = $query->result();
        return $data;
    }
    

}