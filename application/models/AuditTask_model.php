<?php

class AuditTask_model extends Common_model
{

    var $table = 'IFAuditTasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function all($where = array(), $all = false)
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        $adminId2 = $this->input->get_post('adminId');
        $admin = $this->input->get_post('admin');
        $adminId = $this->session->admin->id;
        if(!$all){
            $in = ' where t.adminId = '. $adminId .' ';
        } else{
            $in = ' where 1 = 1 ';
        }
//        if ($status == null) {
//            $in = '  and a.Status  in (0,6,-1) ';
//        } else {
//            $in = '  and a.Status  in (' . $status . ') ';
//        }
        $sql = 'select b.*,a.*,b.Status userStatus,t.taskType,t.taskId,t.status,t_admin.username username,t.adminId,t.remark  from IFAuditTasks t  left JOIN IFUserDebitRecord a
         ON t.debitId = a.DebitId left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN t_admin on t_admin.id = t.adminId';
//        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
        $days = $this->input->get_post('days');
//        $taskType = $this->input->get_post('taskType');
        $taskType = null;
        if (isset($where['taskType'])) {
            $taskType = $where['taskType'];
        }

        if($status!=null){
            $sql .= ' and t.status = '. $status;
        }
        if ($days) {
            $sql .= ' and a.overdueDay = '.$days;
        }
        if ($taskType) {
            $sql .= ' and t.taskType = '. $taskType;
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

        if ($adminId2) {
            $sql .= ' and t.adminId = ' . $adminId2;
        }
        if ($admin) {
            $sql .= ' and t_admin.username = \'' . $admin . '\'';
        }

        if ($startTime) {
            $sql .= ' and a.payBackDayTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.payBackDayTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from  IFAuditTasks t  left JOIN IFUserDebitRecord a
         ON t.debitId = a.DebitId left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN t_admin on t_admin.id = t.adminId';
        $sql2 .= $in;
        if($status != null){
            $sql2 .= ' and t.status = '. $status;
        }
        if ($days) {
            $sql2 .= ' and a.overdueDay = '.$days;
        }
        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }
        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }
        if ($taskType) {
            $sql2 .= ' and t.taskType = '. $taskType;
        }

        if ($adminId2) {
            $sql2 .= ' and t.adminId = ' . $adminId2;
        }
        if ($admin) {
            $sql2 .= ' and t_admin.username = \'' . $admin . '\'';
        }

        if ($startTime) {
            $sql2 .= ' and a.payBackDayTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and a.payBackDayTime <= \'' . $endTime . '\'';
        }
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }


    public function all2($where = array(), $all = false)
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $status = $this->input->get_post('status');
        $adminId2 = $this->input->get_post('adminId');

        //获取查询type类别

        $adminId = $this->session->admin->id;
        $one = $this->getRow('select * from IFAuditTasks WHERE adminId = '. $adminId);
        $isreminder = false;
        if($one['taskType'] == 2){
            $isreminder = true;
        }

        if(!$all){
            $in = ' where t.adminId = '. $adminId .' ';
        } else{
            $in = ' where 1 = 1 ';
        }
        if ($status == null) {
//            $in = '  and a.Status  in (0,6,-1) ';
        } else {
//            $in = '  and a.Status  in (' . $status . ') ';
        }

        $sql = 'select b.*,a.*,b.Status userStatus,t.taskType,t.taskId,t.status,t_admin.username username,t.remark  from IFAuditTasks t  left JOIN IFUserDebitRecord a
         ON t.debitId = a.DebitId left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN t_admin on t_admin.id = t.adminId';
//        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $phone = $this->input->get_post('phone');
        $idcard = $this->input->get_post('idcard');
        $userid = $this->input->get_post('userid');
        $username = $this->input->get_post('username');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');
//        $taskType = $this->input->get_post('taskType');
        $taskType = null;
        if (isset($where['taskType'])) {
            $taskType = $where['taskType'];
        }
        //payBackDayTime
        if ($isreminder) {
            if (isset($where['overdue']) && $where['overdue'] == 0) {
                $sql .= ' and a.payBackDayTime <= \''. date('Y-m-d 23:59:59',time()) . '\'';
            } else {
                $sql .= ' and a.payBackDayTime > \''. date('Y-m-d 23:59:59',time()) . '\'';
            }
        }


        if($status!=null){
            $sql .= ' and t.status = '. $status;
        }
        if ($taskType) {
            $sql .= ' and t.taskType = '. $taskType;
        }
        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($phone) {
            $sql .= ' and b.Phone = ' . $phone;
        }
        if ($username) {
            $sql .= ' and b.UserName = \'' . $username . '\'';
        }

        if ($idcard) {
            $sql .= ' and b.IdCard = ' . $idcard;
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if ($adminId2) {
            $sql .= ' and t.adminId = ' . $adminId2;
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

        $sql2 = 'select count(*) c from IFAuditTasks t LEFT JOIN IFUserDebitRecord a ON a.DebitId = t.debitId LEFT JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql2 .= $in;
        if($status != null){
            $sql2 .= ' and t.status = '. $status;
        }
        if ($taskType) {
            $sql2 .= ' and t.taskType = '. $taskType;
        }

        if ($debitid) {
            $sql2 .= ' and a.DebitId = ' . $debitid;
        }

        if ($adminId2) {
            $sql2 .= ' and t.adminId = ' . $adminId2;
        }

        if ($username) {
            $sql2 .= ' and b.UserName = \'' . $username . '\'';
        }

        if ($userid) {
            $sql2 .= ' and a.UserId = ' . $userid;
        }

        //payBackDayTime
        if ($isreminder) {
            if (isset($where['overdue']) && $where['overdue'] == 0) {
                $sql2 .= ' and a.payBackDayTime <= \''. date('Y-m-d 23:59:59',time()) . '\'';
            } else{
                $sql2 .= ' and a.payBackDayTime > \''. date('Y-m-d 23:59:59',time()) . '\'';
            }
        }
//        echo $sql2;exit;
        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }


    public function allTask($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $in = ' where 1 = 1 ';
        $sql = 'select b.*,a.*,b.Status userStatus,t.taskType,t.taskId,t.status,t_admin.username username,t.adminId,t.createTime  from IFAuditTasks t  left JOIN IFUserDebitRecord a
         ON t.debitId = a.DebitId left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN t_admin on t_admin.id = t.adminId';
//        $sql = 'select b.*,a.*,b.Status as userStatus from IFUserDebitRecord a JOIN IFUsers b ON a.UserId = b.UserId ';
        $sql .= $in;

        $debitid = $this->input->get_post('debitId');
        $adminId = $this->input->get_post('adminId');
        $type = $this->input->get_post('type');
        $status = $this->input->get_post('status');
        $startTime = $this->input->get_post('startTime');
        $endTime = $this->input->get_post('endTime');

        if($status!=null){
            $sql .= ' and t.status = '. $status;
        }
        if ($debitid) {
            $sql .= ' and t.debitId = ' . $debitid;
        }

        if ($adminId) {
            $sql .= ' and t.adminId = ' . $adminId;
        }

        if ($type) {
            $sql .= ' and t.taskType = ' . $type;
        }
        if ($startTime) {
            $sql .= ' and t.createTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and t.createTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFAuditTasks t ';
        $sql2 .= $in;
        if($status != null){
            $sql2 .= ' and t.status = '. $status;
        }
        if ($debitid) {
            $sql2 .= ' and t.debitId = ' . $debitid;
        }

        if ($adminId) {
            $sql2 .= ' and t.adminId = ' . $adminId;
        }

        if ($type) {
            $sql2 .= ' and t.taskType = ' . $type;
        }
        if ($startTime) {
            $sql2 .= ' and t.createTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql2 .= ' and t.createTime <= \'' . $endTime . '\'';
        }

        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
//        var_dump($query2->result(),$query->result());exit;
        //$data = $this->where($where)->limit(10,$start)->select();
        //$count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

}