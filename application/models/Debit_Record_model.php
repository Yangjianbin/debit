<?php

class Debit_Record_model extends Common_model
{

    var $table = 'IFUserDebitRecord';

    public function __construct()
    {
        parent::__construct();
    }

    public function allOverdue0($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;

        $status = 4;
//        if ($status == null) {
//            $in = '  where a.Status  in (4) ';
//        } else {
//            $in = '  where a.Status  in (' . $status . ') ';
//        }
        $in = ' where 1=1  ';

        $sql = 'select (SELECT status from  IFUserAduitDebitRecord WHERE debitId = a.DebitId order by id desc limit 1) auditStatus ,
(SELECT description from  IFUserAduitDebitRecord WHERE debitId = a.DebitId order by id desc limit 1) description , 
b.*,a.*,a.overdueDay overdueDay  from IFUserDebitRecord a,IFUsers b  ';
        $sql .= $in;
        $sql .= ' and a.UserId = b.UserId ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $paybackTimeStart = $this->input->get_post('paybackTimeStart');
        $paybackTimeEnd = $this->input->get_post('paybackTimeEnd');
        $days = 0;
        $paid = $this->input->get_post('paid');

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }
//        $sql .= 'and datediff(a.payBackDayTime , \'' . date('Y-m-d H:i:s',time()) . '\') = 0 ';
//        $sql .= ' and a.overdueDay = 0';
//        $sql .= ' and datediff(date_format( a.payBackDayTime, \'%Y-%m-%d\' ) , date_format( now( ), \'%Y-%m-%d\' )) = 0 ';
        $sql .= ' and a.payBackDayTime>= date_format(now(), \'%Y-%m-%d\') and a.payBackDayTime <  date_format(date_add(now(),interval 1 day),\'%Y-%m-%d\')  ';

        if ($paid && $paid == 1) {
            $sql .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest > 0 ';
        } else if($paid && $paid == -1){
            $sql .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest = 0 ';
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if($paybackTimeStart){
            $sql .= ' and a.payBackDayTime >= \'' . $paybackTimeStart . '\'';
        }
        if($paybackTimeEnd){
            $sql .= ' and a.payBackDayTime <= \'' . $paybackTimeEnd . '\'';
        }

        $sql .= ' AND a.DebitId NOT IN (SELECT debit_id from t_bad) ';

        if (!empty($where)) {
            //TODO
        }
//        $sql .= ' GROUP BY a.DebitId ';
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= '  limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;

        $sql2 = 'SELECT count(*) c from (select a.DebitId from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
//        $sql2 .= ' and datediff(date_format( a.payBackDayTime, \'%Y-%m-%d\' ) , date_format( now( ), \'%Y-%m-%d\' )) = 0 ';
        $sql2 .= ' and a.payBackDayTime>= date_format(now(), \'%Y-%m-%d\') and a.payBackDayTime <  date_format(date_add(now(),interval 1 day),\'%Y-%m-%d\')  ';

        //$sql2 .= ' and a.overdueDay = 0';
//        $sql2 .= 'and datediff(a.payBackDayTime , \'' . date('Y-m-d H:i:s',time()) . '\') = 0 ';
        if ($paid && $paid == 1) {
            $sql2 .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest > 0 ';
        } else if($paid && $paid == -1){
            $sql2 .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest = 0 ';
        }

        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }
        if($paybackTimeStart){
            $sql2 .= ' and a.payBackDayTime >= \'' . $paybackTimeStart . '\'';
        }
        if($paybackTimeEnd){
            $sql2 .= ' and a.payBackDayTime <= \'' . $paybackTimeEnd . '\'';
        }
        $sql2 .= ' AND a.DebitId NOT IN (SELECT debit_id from t_bad) ';
        $sql2 .= ' GROUP BY a.DebitId ) b';

//        echo $sql2;exit;
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allOverdue($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = 4;
        if ($status == null) {
            $in = '  where a.Status  in (4) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,c.*,a.overdueDay overdueDay from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN IFUserDebitOverdueRecord c ON a.DebitId = c.debitId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $days = $this->input->get_post('days');
        $paid = $this->input->get_post('paid');

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if($days && $days == 16){
            $sql .= ' and a.overdueDay > 15';
        }else if ($days) {
            $sql .= ' and a.overdueDay = ' . $days;
        }
        if ($paid && $paid == 1) {
            $sql .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest > 0 ';
        } else if($paid && $paid == -1){
            $sql .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest = 0 ';
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        $sql .= ' AND a.DebitId NOT IN (SELECT debit_id from t_bad) ';

        if (!empty($where)) {
            //TODO
        }
	    $sql .= ' GROUP BY a.DebitId ';
        $sql .= ' order by redStar desc, greenStar desc,a.overdueDay desc, a.DebitId desc ';
        $sql .= '  limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;

        $sql2 = 'SELECT count(*) c from (select a.DebitId from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN IFUserDebitOverdueRecord c ON a.DebitId = c.debitId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if($days && $days == 16){
            $sql2 .= ' and a.overdueDay > 15';
        }else if ($days) {
            $sql2 .= ' and a.overdueDay = ' . $days;
        }
        if ($paid && $paid == 1) {
            $sql2 .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest > 0 ';
        } else if($paid && $paid == -1){
            $sql2 .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest = 0 ';
        }

        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }
        $sql2 .= ' AND a.DebitId NOT IN (SELECT debit_id from t_bad) ';
        $sql2 .= ' GROUP BY a.DebitId ) b';

//        echo $sql2;exit;
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function preloan($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');

        if ($status == null) {
            $in = '  where a.Status  in (0) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a LEFT JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');

        $sql .= ' and a.audit_step = 0 ';

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        $sql2 .= ' and a.audit_step = 0 ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function vall($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');

        if ($status == null) {
            $in = '  where a.Status  in (0,6,-1) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;
        $sql .= ' and (b.redStar > 0 or b.greenStar > 0) ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');

        if ($status == 0) {
            $sql .= ' and a.audit_step = 1 ';
        }

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by  redStar desc, greenStar desc,DebitId desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;
        $sql2 .= ' and (b.redStar > 0 or b.greenStar > 0) ';

        if ($status == 0) {
            $sql2 .= ' and a.audit_step = 1 ';
        }

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function all($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');

        if ($status == null) {
            $in = '  where a.Status  in (0,6,-1) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');

        if ($status == 0) {
            $sql .= ' and a.audit_step = 1 ';
        }

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($status == 0) {
            $sql2 .= ' and a.audit_step = 1 ';
        }

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function vallAdvances($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (5,1)  and audit_step=1 ';
        } else {
            $in = '  where a.Status  in (' . $status . ')  and audit_step=1 ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;
        $sql .= ' and (b.redStar > 0 or b.greenStar > 0) ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,StatusTime desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;
        $sql2 .= ' and (b.redStar > 0 or b.greenStar > 0) ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allAdvances($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (5,1) and audit_step=1 ';
        } else {
            $in = '  where a.Status  in (' . $status . ') and audit_step=1 ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus,c.BankName,c.BankCode  from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId left JOIN IFUserBankInfo c ON a.BankId = c.BankId';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.StatusTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.StatusTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,StatusTime desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.StatusTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.StatusTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function get_record($id)
    {
        $sql = 'select * from IFUserDebitRecord a WHERE a.DebitId = ' . $id;
        $query = $this->query($sql);
        $result = $query->result();
        $ret = empty($result) ? null : $result[0];

        $sql2 = 'select *,year(now())-year(a.birthday) age from IFUsers a WHERE a.UserId = ' . $ret->UserId;
        $query2 = $this->query($sql2);
        $result2 = $query2->result();
        $ret2 = empty($result2) ? null : $result2[0];

        $sql3 = 'select * from IFUserBankInfo a WHERE a.UserId = ' . $ret->UserId;
        $query3 = $this->query($sql3);
        $result3 = $query3->result();
        $ret3 = empty($result3) ? null : $result3[0];

        $sql4 = 'select * from IFUserContactInfo a WHERE a.UserId = ' . $ret->UserId;
        $query4 = $this->query($sql4);
        $result4 = $query4->result();
        $ret4 = $result4;

        $sql5 = 'select * from IFCertificate a WHERE a.CertificateUserId = ' . $ret->UserId;
        $query5 = $this->query($sql5);
        $result5 = $query5->result();
        $ret5 = $result5;

        $sql6 = 'select * from IFUserPayBackDebitRecord a WHERE a.DebitId = ' . $ret->DebitId;
        $query6 = $this->query($sql6);
        $result6 = $query6->result();
        $ret6 = empty($result6) ? null : $result6[0];

        $sql7 = 'select phone,`name` from IFUserContacts a WHERE 1=1 AND recordType = 1 AND a.userId = ' . $ret->UserId;
        $query7 = $this->query($sql7);
        $result7 = $query7->result();
        $ret7 = $result7;
        $ret77 = array();
        $contactName = array();
        foreach ($ret7 as $k => $v) {
            array_push($ret77, $v->phone);
            $contactName[$v->phone] = $v->name;
        }

        //$sql8 = 'select * from IFUserContacts a WHERE a.recordType =2 AND a.userId = ' . $ret->UserId . ' order by id desc limit 100';
        $sql8 = 'SELECT COUNT(*) c, phone,name,callTime,sum(duration) s from IFUserContacts a WHERE a.userId = ' . $ret->UserId . ' AND recordType = 2 GROUP BY phone ORDER BY s DESC,c DESC limit 1000';
        $query8 = $this->query($sql8);
        $result8 = $query8->result();
        $ret8 = $result8;

        $sql9 = 'select * from IFUserAduitDebitRecord a WHERE a.DebitId = '  . $ret->DebitId .' order by id desc limit 3';
        $query9 = $this->query($sql9);
        $result9 = $query9->result();
        $ret9 = $result9;

        $sql10 = 'select count(*) s from IFUserAduitDebitRecord a LEFT JOIN IFUserDebitRecord b on a.DebitId = b.DebitId WHERE a.AduitType = 5 and a.Status = 1  and b.userId = ' . $ret->UserId;
        $query10 = $this->query($sql10);
        $result10 = $query10->result();
        $ret10 = $result10[0]->s;

        $sql11 = 'select count(*) s from IFUserDebitOverdueRecord where userId= ' . $ret->UserId;
        $query11 = $this->query($sql11);
        $result11 = $query11->result();
        $ret11 = $result11[0]->s;

        $sql12 = 'select count(*) s from IFUserAduitDebitRecord a LEFT JOIN IFUserDebitRecord b on a.DebitId = b.DebitId WHERE a.AduitType = 6 and a.Status = 1  and b.userId = ' . $ret->UserId;
        $query12 = $this->query($sql12);
        $result12 = $query12->result();
        $ret12 = $result12[0]->s;

        $sql13 = 'SELECT * from IFUserPayBackDebitRecord WHERE UserId = '.$ret->UserId.' and Status = 0 and DebitId = '.$ret->DebitId.' and type = 1 order by id DESC limit 1';
        $query13 = $this->query($sql13);
        $result13 = $query13->result();
        $ret13 = null;
        if(!empty($result13)){
            $ret13 = $result13[0];
        }

        $sql14 = 'SELECT * from IFUserPayBackDebitRecord WHERE UserId = '.$ret->UserId.' and Status = 0 and DebitId = '.$ret->DebitId.' and type = 2 order by id DESC limit 1';
        $query14 = $this->query($sql14);
        $result14 = $query14->result();
        $ret14 = null;
        if(!empty($result14)){
            $ret14 = $result14[0];
        }

        $sql15 = 'SELECT * from IFUserPayBackDebitRecord WHERE UserId = '.$ret->UserId.' and Status = 0 and DebitId = '.$ret->DebitId.' and type = 3 order by id DESC limit 1';
        $query15 = $this->query($sql15);
        $result15 = $query15->result();
        $ret15 = null;
        if(!empty($result15)){
            $ret15 = $result15[0];
        }

        $sql16 = 'SELECT * from IFUserPayBackDebitRecord WHERE UserId = '.$ret->UserId.' and Status = 0 and DebitId = '.$ret->DebitId.' and type = 4 order by id DESC limit 1';
        $query16 = $this->query($sql16);
        $result16 = $query16->result();
        $ret16 = null;
        if(!empty($result16)){
            $ret16 = $result16[0];
        }


//        var_dump($ret77);exit;

        $ret->user = $ret2;
        $ret->bank = $ret3;
        $ret->contact = $ret4;
        $ret->cert = $ret5;
        $ret->payback = $ret6;
        $ret->contacts = $ret77;
        $ret->contactName = $contactName;
        $ret->records = $ret8;
        $ret->audits = $ret9;
        $ret->red = $ret10;
        $ret->black = $ret11;
        $ret->green = $ret12;
        $ret->paycert = $ret13;
        $ret->extend = $ret14;
        $ret->duextend = $ret15;
        $ret->dupay = $ret16;
        $ret->paybackRecord = $result6;
        return $ret;
    }

    public function allRepayment($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (-2,2,3) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN (
	SELECT DebitId,type,Status pStatus FROM IFUserPayBackDebitRecord where id in (SELECT
		max(Id)
	FROM
		IFUserPayBackDebitRecord  where Status = 0
	GROUP BY
		DebitId )
) b ON a.DebitId = b.DebitId ';
        $sql .= $in;
        $sql.=' and type = 1 ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId  LEFT JOIN (
	SELECT DebitId,type,Status pStatus FROM IFUserPayBackDebitRecord where id in (SELECT
		max(Id)
	FROM
		IFUserPayBackDebitRecord  where Status = 0
	GROUP BY
		DebitId )
) b ON a.DebitId = b.DebitId ';
        $sql2 .= $in;
        $sql2.=' and type = 1 ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }


    /*public function allRepayment($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (-2,2,3) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }*/

    /*public function allExtend($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (6) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }*/

    public function allExtend($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (6) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN (
	SELECT
		DebitId,
		type,
		max(Id)
	FROM
		IFUserPayBackDebitRecord where Status = 0
	GROUP BY
		DebitId
) b ON a.DebitId = b.DebitId ';
        $sql .= $in;
        $sql.=' and type = 2 ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId  LEFT JOIN (
	SELECT
		DebitId,
		type,
		max(Id)
	FROM
		IFUserPayBackDebitRecord where Status = 0
	GROUP BY
		DebitId
) b ON a.DebitId = b.DebitId ';
        $sql2 .= $in;
        $sql2.=' and type = 2 ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allReleased()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');

        if ($status == null) {
            $in = '  where a.Status  in (-2,1) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a LEFT JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $paybacktimeStart = $this->input->get_post('paybacktimeStart');
        $paybacktimeEnd = $this->input->get_post('paybacktimeEnd');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if ($paybacktimeStart) {
            $sql .= ' and a.payBackDayTime >= \'' . $paybacktimeStart . '\'';
        }
        if ($paybacktimeEnd) {
            $sql .= ' and a.payBackDayTime <= \'' . $paybacktimeEnd . '\'';
        }

        $sql .= ' order by redStar desc, greenStar desc,DebitId desc ';
        if (!empty($where)) {
            //TODO
        }
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;
        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }
        if ($paybacktimeStart) {
            $sql2 .= ' and a.payBackDayTime >= \'' . $paybacktimeStart . '\'';
        }
        if ($paybacktimeEnd) {
            $sql2 .= ' and a.payBackDayTime <= \'' . $paybacktimeEnd . '\'';
        }
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function reminders()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');

        if ($status == null) {
            $in = '  where a.Status  in (1) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }
        $date = date('Y-m-d',time());

        $sql = 'select datediff( date_format(a.payBackDayTime,\'%Y-%m-%d\'), date_format(\''.$date.'\',\'%Y-%m-%d\') )  diff,
b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $paybackTimeStart = $this->input->get_post('paybackTimeStart');
        $paybackTimeEnd = $this->input->get_post('paybackTimeEnd');



        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }

        if($paybackTimeStart){
            $sql .= ' and a.payBackDayTime >= \'' . $paybackTimeStart . '\'';
        }
        if($paybackTimeEnd){
            $sql .= ' and a.payBackDayTime <= \'' . $paybackTimeEnd . '\'';
        }

//        $sql .= ' and datediff(a.payBackDayTime , \'' . date('Y-m-d H:i:s',time()) . '\') = 1 ';

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' HAVING diff <= 2 AND diff >=1 order by redStar desc, greenStar desc,a.payBackDayTime desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;
        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }

        if($paybackTimeStart){
            $sql2 .= ' and a.payBackDayTime >= \'' . $paybackTimeStart . '\'';
        }
        if($paybackTimeEnd){
            $sql2 .= ' and a.payBackDayTime <= \'' . $paybackTimeEnd . '\'';
        }

        $sql2 .= ' and datediff( date_format(a.payBackDayTime,\'%Y-%m-%d\'), date_format(\''.$date.'\',\'%Y-%m-%d\') ) >=1 
        and datediff( date_format(a.payBackDayTime,\'%Y-%m-%d\'), date_format(\''.$date.'\',\'%Y-%m-%d\') ) <=2 ';
//        echo $sql2;exit;
        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function chart()
    {
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $sql = 'select count(*) total,Status from IFUserDebitRecord a WHERE 1 = 1 ';
        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        $sql.=' group by Status ';
        $query = $this->query($sql);
        $data = $query->result();
        $total = 0;
        foreach ($data as $k => $v) {
            $total += $v->total;
        }
        foreach ($data as $k => $v) {
//            $data[$k]->y = round($v->total / $total,6);
            $data[$k]->name = $this->config->item('statusEnum')[$v->Status];
            $data[$k]->y = floatval($v->total);
        }

        return $data;
    }

    public function chart2()
    {
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $sql = 'select count(*) total,Status,DATE_FORMAT(CreateTime,\'%Y-%m-%d\') date from IFUserDebitRecord a WHERE 1 = 1 ';
        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        $sql.=' group by DATE_FORMAT(CreateTime,\'%Y-%m-%d\') ';
        $query = $this->query($sql);
        $data = $query->result();
        $total = 0;
        foreach ($data as $k => $v) {
            $total += $v->total;
        }
        foreach ($data as $k => $v) {
//            $data[$k]->y = round($v->total / $total,6);
            $data[$k]->name = $this->config->item('statusEnum')[$v->Status];
            $data[$k]->y = floatval($v->total);
        }

        return $data;
    }

    public function chart3()
    {
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $sql = 'select DebitMoney,Status,DATE_FORMAT(CreateTime,\'%Y-%m-%d\') date from IFUserDebitRecord a WHERE 1 = 1 ';
        if ($startTime) {
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }
        $sql.=' group by DATE_FORMAT(CreateTime,\'%Y-%m-%d\') ';
        $query = $this->query($sql);
        $data = $query->result();
        foreach ($data as $k => $v) {
//            $data[$k]->y = round($v->total / $total,6);
            $data[$k]->name = $this->config->item('statusEnum')[$v->Status];
            $data[$k]->y = floatval($v->DebitMoney);
        }

        return $data;
    }

    public function allDailyReport($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->orderby(array('id'=>'desc'))->limit(10,$start)->select();
        $count = $this->where($where)->count();
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function allPaybackuser($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT b.* from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId WHERE a.`Status` = 3';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId WHERE a.`Status` = 3';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;

        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);

    }

    public function allUploadCert($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $in = ' where 1=1 ';
        $sql = 'select b.*,a.*,b.Status as userStatus,c.BankName,c.BankCode  from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId left JOIN IFUserBankInfo c ON a.BankId = c.BankId';
        $sql .= $in;
        $debitid = $this->input->get_post('debitid') ? $this->input->get_post('debitid') : -1;
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');

        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.StatusTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.StatusTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,StatusTime desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.StatusTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.StatusTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allDuextend($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (-2,6) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN (
	SELECT
		DebitId,
		type,
		Status pStatus,
		max(Id)
	FROM
		IFUserPayBackDebitRecord where Status = 0
	GROUP BY
		DebitId
) b ON a.DebitId = b.DebitId ';
        $sql .= $in;
        $sql.=' and type = 3 ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId  LEFT JOIN (
	SELECT
		DebitId,
		type,
		Status pStatus,
		max(Id)
	FROM
		IFUserPayBackDebitRecord  where Status = 0
	GROUP BY
		DebitId
) b ON a.DebitId = b.DebitId ';
        $sql2 .= $in;
        $sql2.=' and type = 3 ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allDurepayment($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        if ($status == null) {
            $in = '  where a.Status  in (-2,2) ';
        } else {
            $in = '  where a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN (
	SELECT DebitId,type,Status pStatus FROM IFUserPayBackDebitRecord where id in (SELECT
		max(Id)
	FROM
		IFUserPayBackDebitRecord where Status = 0
	GROUP BY
		DebitId )
) b ON a.DebitId = b.DebitId ';
        $sql .= $in;
        $sql.=' and type = 4 ';

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');


        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($startTime) {
            $sql .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId  LEFT JOIN (
	SELECT DebitId,type,Status pStatus FROM IFUserPayBackDebitRecord where id in (SELECT
		max(Id)
	FROM
		IFUserPayBackDebitRecord  where Status = 0
	GROUP BY
		DebitId )
) b ON a.DebitId = b.DebitId ';
        $sql2 .= $in;
        $sql2.=' and type = 4 ';

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql2 .= ' and b.Phone = ' . $phone;
        }

        if ($idcard) {
            $sql2 .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($startTime) {
            $sql2 .= ' and a.releaseLoanTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.releaseLoanTime <= \'' . $endTime . '\'';
        }

        if (!empty($where)) {
            //TODO
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function latelypayback($where = array())
    {
        $adminId = $this->session->admin->id;
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT *,c.CreateTime PaybackTime from IFUserPayBackDebitRecord c LEFT JOIN IFAuditTasks a on a.debitId = c.DebitId LEFT JOIN IFUserDebitRecord
b on b.DebitId = c.DebitId
 where a.adminId = '.$adminId.' and money > 0 and b.overdueDay<1 order by c.Id desc ';
//        $sql = 'SELECT * from IFUserPayBackDebitRecord a JOIN IFUserDebitRecord b ON a.DebitId = b.DebitId
//        WHERE a.money > 0 order by id desc ';
        $sql .= ' limit ' . $start . ' , 10';
//        echo $sql;exit;
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from IFUserPayBackDebitRecord c LEFT JOIN IFAuditTasks a on a.debitId = c.DebitId LEFT JOIN IFUserDebitRecord
b on b.DebitId = c.DebitId
 where a.adminId = '.$adminId.' and money > 0 and b.overdueDay<1 order by c.Id desc ';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;
//        $count = count($data);
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
        /*$start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT * from IFUserPayBackDebitRecord a JOIN IFUserDebitRecord b ON a.DebitId = b.DebitId WHERE a.money > 0 order by id desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from IFUserPayBackDebitRecord a WHERE a.money > 0 ';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);*/
    }

    public function overdue0_latelypayback($where = array())
    {
        $adminId = $this->session->admin->id;
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT *,c.CreateTime PaybackTime from IFUserPayBackDebitRecord c LEFT JOIN IFAuditTasks a on a.debitId = c.DebitId where a.adminId = '.$adminId.' and money > 0 order by c.Id desc ';
//        $sql = 'SELECT * from IFUserPayBackDebitRecord a JOIN IFUserDebitRecord b ON a.DebitId = b.DebitId
//        WHERE a.money > 0 order by id desc ';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from IFUserPayBackDebitRecord c LEFT JOIN IFAuditTasks a on a.debitId = c.DebitId where a.adminId = '.$adminId.' and money > 0';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;
//        $count = count($data);
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

}
