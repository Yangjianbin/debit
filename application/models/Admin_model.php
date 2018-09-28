<?php

class Admin_model extends Common_model
{

    var $table = 't_admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function all($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->limit(10, $start)->orderby(array('gmt_create' => 'desc'))->select();
        $count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allSelect()
    {
        $sql = 'select username,id from t_admin';
        $query = $this->query($sql);
        $data = $query->result();
        return $data;
    }

    public function getRole($adminId)
    {
        return $this->getRow('select t_role.* from t_admin_role left JOIN t_role  ON t_role.id = t_admin_role.role_id WHERE t_admin_role.admin_id = ' . $adminId);
    }

    public function getMenu($adminId)
    {
        $role = $this->getRow('select t_role.* from t_admin_role left JOIN t_role  ON t_role.id = t_admin_role.role_id WHERE t_admin_role.admin_id = ' . $adminId);
        if (!$role) {
            return null;
        }
        $roleId = $role['id'];
        $menus = $this->getRows('select a.id,a.url,a.title,a.type,a.order_num,a.display,a.pid 
from  t_role_menu LEFT JOIN t_menu a ON  t_role_menu.menu_id = a.id WHERE  t_role_menu.role_id = '.$roleId.' and a.display=1 order by order_num
');
//        var_dump($menus);exit;
        return $menus;
    }


}