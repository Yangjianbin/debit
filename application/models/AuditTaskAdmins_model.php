<?php

class AuditTaskAdmins_model extends Common_model
{

    var $table = 'IFAuditTaskAdmins';

    public function __construct()
    {
        parent::__construct();
    }

    public function all($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'select t.*,a.username from IFAuditTaskAdmins t LEFT JOIN t_admin a on a.id = t.adminId';
        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by t.id desc limit ' . $start . ' , 10';
        $query = $this->query($sql);
//        echo $sql;exit;
//        var_dump($this->db->last_query());exit;

        $sql2 = 'select count(*) c from IFAuditTaskAdmins t ';

        $query2 = $this->query($sql2);
        $data = $query->result();
        $count = $query2->result()[0]->c;
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allSelect()
    {
        $sql = 'select adminId,taskType,t1.username from IFAuditTaskAdmins t LEFT JOIN t_admin t1 ON t1.id = t.adminId';
        $query = $this->query($sql);
        $data = $query->result();
        return $data;
    }
}