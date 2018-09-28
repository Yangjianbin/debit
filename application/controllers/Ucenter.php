<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ucenter extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AuditTask_model');
        $this->load->model('AuditTaskAdmins_model');
        $this->load->model('Admin_model');
        $this->load->model('Debit_Record_model');
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
    private function successData($d = null){
        $status = 200;
        $data = array('status' => $status, 'msg' => '操作成功');
        if ($d) {
            $data['data'] = $d;
        }
        echo json_encode($data);
        exit;
    }

    public function task()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->all2(array(), false);
            echo json_encode($res);
        } else {
            $this->load->view('ucenter/task');
        }
    }

    public function overduetodaytask()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->all2((array('overdue'=>0)), false);
            echo json_encode($res);
        } else {
            $users = $this->Admin_model->allSelect();
            $data = array('users'=>$users);
            $this->load->view('ucenter/overduetodaytask', $data);
        }
    }

    public function done($id = null)
    {
        if (!$id) {
            $this->error('任务id不为空');
        }
        $this->AuditTask_model->update(array('taskId'=>$id), array('status'=>1,'auditTime'=>date('Y-m-d H:i:s')));
        $this->success();
    }

    public function pending($id = null)
    {
        if (!$id) {
            $this->error('任务id不为空');
        }
        $this->AuditTask_model->update(array('taskId'=>$id), array('status'=>2,'auditTime'=>date('Y-m-d H:i:s')));
        $this->success();
    }

    public function alltask()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->all(array('taskType'=>1), true);
            echo json_encode($res);
        } else {
            $this->load->view('ucenter/alltask');
        }
    }

    public function alltask2()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->all(array('taskType'=>2), true);
            echo json_encode($res);
        } else {
            $this->load->view('ucenter/alltask2');
        }
    }

    public function alltask3()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->all(array('taskType'=>3), true);
            echo json_encode($res);
        } else {
            $users = $this->Admin_model->allSelect();
            $data = array('users'=>$users);
            $this->load->view('ucenter/alltask3', $data);
        }
    }

    public function taskmgradd()
    {
        if ($this->input->is_ajax_request()) {
            $adminId = $this->input->post('adminId');
            $taskType = $this->input->post('taskType');
            $data = array(
                'adminId' => $adminId,
                'taskType' => $taskType
            );
            $this->AuditTaskAdmins_model->insert($data);
            $this->success();
        } else {
            $users = $this->Admin_model->allSelect();
            $data = array(
                'users' => $users
            );
            $this->load->view('ucenter/taskmgr_add', $data);
        }
    }

    public function taskmgrdel($id){
        if (!$id) {
            $this->error('param can\'t be empty');
        }
        $this->Common_model->delete(array('id'=>$id),'IFAuditTaskAdmins');
        $this->success();
    }

    public function taskmgr()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTaskAdmins_model->all(array());
            echo json_encode($res);
        } else {
            $this->load->view('ucenter/task_mgr');
        }
    }

    public function taskassign()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->AuditTask_model->allTask(array());
            echo json_encode($res);
        } else {
            $this->load->view('ucenter/taskassign');
        }
    }


    public function taskassignedit($id)
    {
        if ($this->input->is_ajax_request()) {
            $adminId = $this->input->post('adminId');
            $id = $this->input->post('id');
            $data = array(
                'adminId' => $adminId
            );
            $this->AuditTask_model->update(array('taskId'=>$id), $data);
            $this->success();
        } else {
            $users = $this->AuditTaskAdmins_model->allSelect();
            $data = array(
                'users' => $users,
                'id'=>$id
            );
            $this->load->view('ucenter/taskassignedit', $data);
        }
    }

    public function batchtaskassignedit($ids)
    {
        if ($this->input->is_ajax_request()) {
            $adminId = $this->input->post('adminId');
            $ids = $this->input->post('ids');
            $data = array(
                'adminId' => $adminId
            );
            $ids = trim($ids, '-');
            if (!$ids) {
                $this->error();
            }
            $ids = str_replace("-",",",$ids);
//            var_dump(explode('-', $ids));exit;
            $sql = 'update IFAuditTasks set adminId = '.$adminId .' where taskId in ('.$ids.')';
//            echo $sql;exit;
            $handle = $this->AuditTask_model->query($sql);
//            $handle->updateBatch(array(), $data);
            $this->success();
        } else {
            $users = $this->AuditTaskAdmins_model->allSelect();
            $data = array(
                'users' => $users,
                'ids'=>$ids
            );
            $this->load->view('ucenter/batchtaskassignedit', $data);
        }
    }


    public function addremark($taskId)
    {
        if ($this->input->is_ajax_request()) {
//            $taskId = $this->input->post('taskId');
            $remark = $this->input->post('remark');

            $data = $this->AuditTask_model->get_one(array('taskId' => $taskId));
            $his = json_decode($data->remark_his);
            if (!$his) {
                $his = array();
            }
            array_push($his,array(
                'remark' => $remark,
                'createTime' => date('Y-m-d H:i:s')
            ));

            $data = array(
                'remark' => $remark,
                'remark_his'=>json_encode($his)
            );

            $this->AuditTask_model->update(array('taskId'=>$taskId), $data);
            $this->success();
        }
        $this->error('param can\'t be empty');
    }

    public function addFreeze($debitId = null, $day)
    {
        if (!$debitId) {
            $this->error('参数不为空');
        }
        $one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));
        if($one->freeze == 1){
            $this->error('已经冻结');
        }
        $now = date('Y-m-d H:i:s');
        $update = array(
            'freezeTime'=>$now,
            'freeze'=>1,
            'unfreezeTime'=>date('Y-m-d H:i:s',strtotime('+'.$day.' day'))
        );
        $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
        $this->success();
    }

    public function removeFreeze($debitId = null)
    {
        if (!$debitId) {
            $this->error('参数不为空');
        }
        $one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));
        if($one->freeze != 1){
            $this->error('已经解冻');
        }
        $now = date('Y-m-d H:i:s');
        $update = array(
            'freezeTime'=>$now,
            'freeze'=>0
        );
        $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
        $this->success();
    }

    public function remarkHis($taskId = null)
    {
        $data = $this->AuditTask_model->get_one(array('taskId' => $taskId));
        $data = json_decode($data->remark_his);
        $this->load->view('ucenter/record_his', array('data'=>$data));
    }






}
