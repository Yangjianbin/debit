<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vadmin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Debit_Record_model');
        $this->load->model('UserAduitDebitRecord_model');
        $this->load->model('IFCertificate_model');
        $this->load->model('Common_model');
        $this->load->model('PushMessage_model');
        $this->load->model('UserPayBackDebitRecord_model');
        $this->load->model('Notice_model');

    }

    public function test()
    {
        echo $this->session->admin->id;
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

    public function index()
    {
        $url = '';
        foreach ($this->session->menu as $k => $v) {
            if ($v['pid']) {
                $url = $v['url'];
                break;
            }
        }
        redirect(site_url($url));
    }

    /**
     * 贷款-预审
     */
    public function preloan()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $invalid = $this->input->get_post('invalid');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            if ($invalid !== '') {
                $where['invalid'] = $invalid;
            }
            $res = $this->Debit_Record_model->preloan($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/preloan', $data);
        }
    }

    public function doPreloan()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $checkStatus = $this->input->post('checkStatus');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $now = date('Y-m-d H:i:s');
            if ($checkStatus == -1) {
                $checkStatus = 2;
                $status = -1;
            } else {
                $checkStatus = 1;
                $status = 0;
                $description = 'pre aduit pass';
            }
//            if ($checkStatus == 2) {
            $aduitType = 1;
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $checkStatus,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);
//            }


            $update = array(
                'Status' => $status,
                'StatusTime' => $now,
                'describe'=>$describe
            );
            if ($status == 0) {
                $update['audit_step'] = 1;
            }

            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $message = 'loan aduit success';
            if ($status == -1) {
                $message = 'loan aduit fail';
            }
            $push = array(
                'userId' => $userId,
                'message' => $message,
                'createTime' => $now,
                'statusTime' => $now,
                'status' => 0,
                'registrationId' => $registrationId
            );
            $this->PushMessage_model->insert($push);
            $this->success();
        }
        $this->error();
    }

    public function preloanCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item,
            'show_contact'=>true
        );
        $this->load->view('admin/preloan_check', $data);
    }

    /**
     * 贷款
     */
    public function loan()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $invalid = $this->input->get_post('invalid');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            if ($invalid !== '') {
                $where['invalid'] = $invalid;
            }
            $res = $this->Debit_Record_model->vall($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/vloan', $data);
        }
    }

    /**
     * 贷款
     */
    public function doCheck()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $checkStatus = $this->input->post('checkStatus');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $status = 0;
            $now = date('Y-m-d H:i:s');
            if ($checkStatus == -1) {
                $checkStatus = 2;
                $status = -1;
            } else {
                $checkStatus = 1;
                $status = 5;
                $description = 'Information audit through(Pending money)';
            }
            $aduitType = 1;
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $checkStatus,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);

            $update = array(
                'Status' => $status,
                'StatusTime' => $now,
                'describe'=>$describe
            );

            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $message = 'loan aduit success';
            if ($status == -1) {
                $message = 'loan aduit fail';
            }
            $push = array(
                'userId' => $userId,
                'message' => $message,
                'createTime' => $now,
                'statusTime' => $now,
                'status' => 0,
                'registrationId' => $registrationId
            );
            $this->PushMessage_model->insert($push);
            $this->success();
        }
        $this->error();
    }

    /**
     * 贷款
     * @param $id
     */
    public function loanCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item,
            'show_contact'=>true
        );
        $this->load->view('admin/loan_check', $data);
    }

    /**
     * 放款
     */
    public function advances()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $invalid = $this->input->get_post('invalid');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            if ($invalid !== '') {
                $where['invalid'] = $invalid;
            }
            $res = $this->Debit_Record_model->vallAdvances($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/vadvances', $data);
        }
    }


}
