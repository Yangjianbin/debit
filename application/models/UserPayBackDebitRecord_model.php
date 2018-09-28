<?php

class UserPayBackDebitRecord_model extends Common_model{

    var $table = 'IFUserPayBackDebitRecord';

    public function __construct(){
        parent::__construct();
    }


    public function sumTotalMoney($debitId)
    {
        $sql = 'select sum(money) s from IFUserPayBackDebitRecord a where DebitId = '. $debitId;
        $res = $this->getRow($sql);
        return $res['s'] ? $res['s'] : 0;
    }

    public function all($where = array())
    {
        $where['money > '] = 0;
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $_data = $this->where($where)->limit(10, $start)->orderby(array('id' => 'desc'));
        $_data->db->join('IFUserDebitRecord', 'IFUserDebitRecord.DebitId = IFUserPayBackDebitRecord.DebitId');
        $data = $_data->select('*,IFUserPayBackDebitRecord.CreateTime paybackCreateTime,IFUserPayBackDebitRecord.StatusTime paybackStatusTime');
//        echo $this->db->last_query();exit;

        $this->db->join('IFUserDebitRecord', 'IFUserDebitRecord.DebitId = IFUserPayBackDebitRecord.DebitId');
        $count = $this->where($where)->count();
        $sql = "select id,realname from t_admin";
        $rows = $this->getRows($sql);
        $arr = array();
        foreach ($rows as $k=>$v){
            $arr[$v['id']] = $v['realname'];
        }
        foreach ($data as $k=>$v){
            $v['realname'] = $arr[$v['AdminId']];
            $data[$k] = $v;
        }

        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }


}