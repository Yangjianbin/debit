<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Role_model');
        $this->load->model('Menu_model');
        $this->load->model('Common_model');
    }

    private function error($msg = '操作失败')
    {
        $status = 500;
        $data = array('status' => $status, 'msg' => $msg);
        echo json_encode($data);
        exit;
    }

    private function success($msg = '操作成功', $d = null)
    {
        $status = 200;
        $data = array('status' => $status, 'msg' => $msg);
        if ($d) {
            $data['data'] = $d;
        }
        echo json_encode($data);
        exit;
    }

    private function successData($d = null)
    {
        $status = 200;
        $data = array('status' => $status, 'msg' => '操作成功');
        if ($d) {
            $data['data'] = $d;
        }
        echo json_encode($data);
        exit;
    }

    public function admin()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Admin_model->all($where);
            echo json_encode($res);
        } else {
            $roles = $this->Role_model->allSelect();
            $data = array(
                'roles' => $roles
            );
            $this->load->view('admin/auth/admin', $data);
        }
    }

    public function adminAdd()
    {
        if ($this->input->is_ajax_request()) {
            $username = $this->input->post('username');
            $realname = $this->input->post('realname');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $role = $this->input->post('role');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'username' => $username,
                'realname' => $realname,
                'password' => md5($password),
                'phone' => $phone,
                'email' => $email,
                'gmt_create' => $now
            );
            $id = $this->Admin_model->insert($data, 't_admin', true);

            $adminRole = $this->Admin_model->getOne('select * from t_admin_role WHERE admin_id = ' . $id);
            if ($adminRole) {
                //update
                $this->Admin_model->update(array('admin_id' => $id), array('role_id' => $role), 't_admin_role');
            } else {
                //insert
                $this->Admin_model->insert(array('admin_id' => $id, 'role_id' => $role), $table = 't_admin_role');
            }

            $this->success();
        } else {
            $roles = $this->Role_model->allSelect();
            $data = array(
                'roles' => $roles
            );
            $this->load->view('admin/auth/admin_add', $data);
        }
    }

    public function adminEdit($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $username = $this->input->post('username');
            $realname = $this->input->post('realname');
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $role = $this->input->post('role');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'username' => $username,
                'realname' => $realname,
                'phone' => $phone,
                'email' => $email,
                'gmt_modify' => $now
            );
            if ($password) {
                $data['password'] = md5($password);
            }
            $this->Admin_model->update(array('id' => $id), $data);
            $adminRole = $this->Admin_model->getOne('select * from t_admin_role WHERE admin_id = ' . $id);
            if ($adminRole) {
                //update
                $this->Admin_model->update(array('admin_id' => $id), array('role_id' => $role), 't_admin_role');
            } else {
                //insert
                $this->Admin_model->insert(array('admin_id' => $id, 'role_id' => $role), $table = 't_admin_role');
            }

            $this->success();
        } else {
            $item = $this->Admin_model->get_one(array('id' => $id));
            $roles = $this->Role_model->allSelect();
            $data = array(
                'item' => $item,
                'roles' => $roles
            );
            $adminRole = $this->Admin_model->getOne('select role_id from t_admin_role WHERE admin_id = ' . $item->id);
            $item->roleId = $adminRole ? $adminRole : '';
            $this->load->view('admin/auth/admin_edit', $data);
        }
    }

    public function adminDelete($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->Admin_model->delete(array('id' => $id));
            $this->Common_model->delete(array('admin_id' => $id), 't_admin_role', 99);
            $this->success();
        }
    }

    public function adminStop($id)
    {
        if ($this->input->is_ajax_request()) {
            $data = array(
                'status' => 0,
                'gmt_modify' => date('Y-m-d H:i:s')
            );
            $this->Admin_model->update(array('id' => $id), $data);
            $this->success();
        }
    }

    public function adminStart($id)
    {
        if ($this->input->is_ajax_request()) {
            $data = array(
                'status' => 1,
                'gmt_modify' => date('Y-m-d H:i:s')
            );
            $this->Admin_model->update(array('id' => $id), $data);
            $this->success();
        }
    }

    public function menu()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Menu_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/auth/menu', $data);
        }
    }

    public function menuAdd()
    {
        if ($this->input->is_ajax_request()) {
            $title = $this->input->post('title');
            $display = $this->input->post('display');
            $url = $this->input->post('url');
            $pid = $this->input->post('pid');
            $type = $this->input->post('type');
            $order_num = $this->input->post('order_num');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'title' => $title,
                'display' => $display,
                'url' => $url,
                'pid' => $pid,
                'type' => $type,
                'order_num' => $order_num,
                'gmt_create' => $now
            );
            $this->Menu_model->insert($data);
            $this->success();
        } else {
            $meuns = $this->Menu_model->allSelect();
//            var_dump($meuns);exit;
            $data = array(
                'menus' => $meuns
            );
            $this->load->view('admin/auth/menu_add', $data);
        }
    }

    public function menuEdit($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            $display = $this->input->post('display');
            $url = $this->input->post('url');
            $pid = $this->input->post('pid');
            $type = $this->input->post('type');
            $order_num = $this->input->post('order_num');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'title' => $title,
                'display' => $display,
                'url' => $url,
                'pid' => $pid,
                'type' => $type,
                'order_num' => $order_num,
                'gmt_modify' => $now
            );
            $this->Menu_model->update(array('id' => $id), $data);
            $this->success();
        } else {
            $item = $this->Menu_model->get_one(array('id' => $id));
            $meuns = $this->Menu_model->allSelect();
            $data = array(
                'item' => $item,
                'menus' => $meuns
            );
            $this->load->view('admin/auth/menu_edit', $data);
        }
    }

    public function menuDelete($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->Menu_model->delete(array('id' => $id));
            $this->success();
        }
    }

    public function role()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Role_model->all($where);
            echo json_encode($res);
        } else {
            $this->load->view('admin/auth/role');
        }
    }

    public function roleAdd()
    {
        if ($this->input->is_ajax_request()) {
            $rolename = $this->input->post('rolename');
            $menu = $this->input->post('menu');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'rolename' => $rolename,
                'gmt_create' => $now
            );
            $id = $this->Role_model->insert($data, 't_role', true);
            if (count($menu) > 0) {
                $data = array();
                foreach ($menu as $v) {
                    $arr = array(
                        'role_id' => $id,
                        'menu_id' => $v
                    );
                    $data[] = $arr;
                }
                $this->Common_model->insert_batch($data, 't_role_menu');
            }
            $this->success();
        } else {
            $menus = $this->Menu_model->allSelect();
            $data = array(
                'menus' => $menus
            );
            $this->load->view('admin/auth/role_add', $data);
        }
    }

    public function roleEdit($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $rolename = $this->input->post('rolename');
            $id = $this->input->post('id');
            $menu = $this->input->post('menu');
            $now = date('Y-m-d H:i:s', time());
            $data = array(
                'rolename' => $rolename,
                'gmt_modify' => $now
            );
            $this->Role_model->update(array('id' => $id), $data);
            $this->Common_model->delete(array('role_id' => $id), 't_role_menu', 99);
            if (count($menu) > 0) {
                $data = array();
                foreach ($menu as $v) {
                    $arr = array(
                        'role_id' => $id,
                        'menu_id' => $v
                    );
                    $data[] = $arr;
                }
                $this->Common_model->insert_batch($data, 't_role_menu');
            }
            $this->success();
        } else {
            $item = $this->Role_model->get_one(array('id' => $id));
            $menus = $this->Menu_model->allSelect();
            $sql = 'select menu_id from t_role_menu WHERE role_id =' . $item->id;
            $roleMenu = $this->Common_model->getRows($sql);
            foreach ($menus as $k => $v) {
                foreach ($roleMenu as $kk => $vv) {
                    if ($v['id'] == $vv['menu_id']) {
                        $v['checked'] = true;
                        $menus[$k] = $v;
                        break;
                    }
                    $v['checked'] = false;
                    $menus[$k] = $v;
                }
            }
//            var_dump($menus);exit;
            $data = array(
                'menus' => $menus,
                'item' => $item
            );
            $this->load->view('admin/auth/role_edit', $data);
        }
    }

    public function roleDelete($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->Role_model->delete(array('id' => $id));
            $this->success();
        }
    }


}
