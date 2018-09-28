<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
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

    public function notice()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Notice_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/notice', $data);
        }
    }

    public function noticeEdit($id = null)
    {
        $item = $this->Notice_model->where(array('id'=>$id))->get_one();
        $data = array('item' => $item);
        //var_dump($data);exit;
        $this->load->view('admin/notice_edit',$data);
    }

    public function doNoticeEdit()
    {
        $id = $this->input->post('id');
        $content = $this->input->post('content');
        $startTime = $this->input->post('start_time');
        $endTime = $this->input->post('end_time');
        $title = $this->input->post('title');

        $data = array(
            'title'=>$title,
            'content'=>$content,
            'startTime'=>$startTime,
            'endTime'=>$endTime,
            'adminId'=>$this->session->admin->id
        );

        $res = $this->Notice_model->update(array('Id'=>$id), $data);
        $this->success();

    }

    /**
     * 逾期
     */
    public function overdue()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allOverdue($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/overdue', $data);
        }
    }

    /**
     * 逾期
     */
    public function overdue0()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allOverdue0($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/overdue0', $data);
        }
    }

    public function overdueCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item,
            'show_contact'=>true
        );
        $this->load->view('admin/overdue_check', $data);
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
                $description = 'pengajuan disetujui';
            }
//            if ($checkStatus == 2) {
            $aduitType = 1;
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $checkStatus,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'Remark'=>$describe,
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
            $res = $this->Debit_Record_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/loan', $data);
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
                $description = 'informasi audit(transfer dalam proses)';
            }
            $aduitType = 1;
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $checkStatus,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'Remark'=>$describe,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);

            $update = array(
                'Status' => $status,
                'StatusTime' => $now,
                'describe'=>$describe
            );
            $userId = $this->input->post('userId');
            if ($status == 5) {
                $one = $this->Common_model->getOne('select * from IFUserBankInfo where BNICode is not null and  BNICode != \'\' and UserId = '.$userId);
                if ($one) {
                    $update['audit_step'] = 2;
                } else{
                    $update['audit_step'] = 1;
                }
            }

            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);

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
            $res = $this->Debit_Record_model->allAdvances($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/advances', $data);
        }
    }

    /**
     * 放款
     * @param $id
     */
    public function advancesCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/advances_check', $data);
    }

    /**
     * 放款
     */
    public function doAdvances()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $url = $this->input->post('url');
            $aduitType = 3;
            $now = date('Y-m-d H:i:s');
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $status,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'Remark'=>$describe,
                'auditTime' => $now
            );
            if ($status == 1) {
                $data['Description'] = 'Rekening Pengembalian Pinjaman :
Nama Bank : Mandiri
Nomor Rekening Bank : 168-000-128-1722
Atas Nama : PT. Anugerah Digital Niaga
Nama Bank：BCA
Nomor Rekening Bank：1684789999
Atas Nama：PT. Anugerah Digital Niaga
Untuk Pemegang rek BCA tolong melunasi / Perpanjangan sebelum jatuh tempo';
            }
            $this->UserAduitDebitRecord_model->insert($data);

            if ($url) {
                $arr = array(
                    'CertificateType' => 2,
                    'Url' => $url,
                    'TableId' => $debitId,
                    'CertificateUserId' => '',
                    'CreateTime' => date('Y-m-d H:i:s', time())
                );
                $this->IFCertificate_model->insert($arr);
            }

            $one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));

            $payBackDayTime = date('Y-m-d H:i:s', strtotime("$now +" . $one->DebitPeroid . " day"));
            $update = array(
                'Status' => $status,
                'payBackDayTime' => $payBackDayTime,
                'releaseLoanTime' => $now,
                'StatusTime' => $now,
                'describe'=>$describe
            );
            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $message = 'loan paid aduit success';
            if ($status != 1) {
                $message = 'loan paid aduit fail';
            } else{

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
     * 还款
     */
    public function repayment()
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
            $res = $this->Debit_Record_model->allRepayment($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/repayment', $data);
        }
    }

    /**
     * 还款管理
     * @param $id
     */
    public function repaymentCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/repayment_check', $data);
    }


    public function doRepayment()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $returnMoney = doubleval($this->input->post('returnMoney'));
            $aduitType = 2;
            $now = date('Y-m-d H:i:s');
            $s = 2;
            if ($status == 3) {
                $s = 1;
                $description = 'pengembalian dalam proses';
                if($returnMoney <= 0){
                    $this->error('returnMoney must more than zero.');
                }
            }
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $s,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);


            // $one = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));
            //$DebitPeroid = $one->DebitPeroid;
            $record = $this->UserPayBackDebitRecord_model->get_one(array('DebitId' => $debitId, 'type' => 1));
            $createTime = $record->CreateTime;

            $update = array(
                'Status' => $status,
                'StatusTime' => $now,
                'describe'=>$describe
            );
            if ($status == 3) {
                //需求变动此处去了
                $update['userPaybackTime'] = $createTime;
            }

            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $message = 'payback aduit success';
            if ($status != 3) {
                $message = 'payback aduit fail';
            } else{
//                $res = $this->Common_model->query('call p_gen_report_debit_daily(' .  . ')');
            }

            /*$returnData = array(
                'money'=>$returnMoney,
                'UserId'=>$userId,
                'DebitId'=>$debitId,
                'CreateTime'=>date('Y-m-d H:i:s'),
                'Status'=> ($status == 3 ? 1 : -1)
            );
            $this->UserPayBackDebitRecord_model->insert($returnData);*/

            $returnData = array(
//                'money'=>$returnMoney,
                'UserId'=>$userId,
                'DebitId'=>$debitId,
                'type'=>1,
//                'CreateTime'=>date('Y-m-d H:i:s'),
            'Status'=>0
//                'Status'=> ($status == 3 ? 1 : -1)
            );
            $this->UserPayBackDebitRecord_model->update($returnData,array(
                'Status'=> ($status == 3 ? 1 : -1),
                'money'=>$returnMoney,
                'AdminId'=>$this->session->admin->id,
                'StatusTime'=>date('Y-m-d H:i:s')
            ));


            $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);
            $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));

            if($total > $item->DebitMoney){
                $newalreadyReturnMoney = $item->DebitMoney;
                $alreadyReturnInterest = $total - $item->DebitMoney;
            } else{
                $alreadyReturnInterest = 0;
                $newalreadyReturnMoney = $total;
            }

//            var_dump()
            $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                'alreadyReturnMoney'=>$newalreadyReturnMoney,
                'alreadyReturnInterest'=>$alreadyReturnInterest
            ));

            //还款通过,调用过程
            if($status == 3){
                $res = $this->Common_model->query('call p_gen_report_debit_daily(' . $item->releaseLoanTime . ')');
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
     * 延期
     */
    public function extend()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allExtend($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/extend', $data);
        }
    }

    /**
     * 延期管理
     * @param $id
     */
    public function extendCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/extend_check', $data);
    }

    public function doExtend()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $returnMoney = $this->input->post('returnMoney');
            $aduitType = 6;
            $s = 2;
            if ($status == 1) {
                $s = 1;
                $description = 'perpanjangan dalam proses';
                if($returnMoney <= 0){
                    $this->error('returnMoney must more than zero.');
                }
            }
            $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));
            if ($item->Status != 6) {
                $this->error('can\'t repeat audit again.');
            }
            $now = date('Y-m-d H:i:s');
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $s,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'Remark'=>$describe,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);

            $message = 'extend aduit success';
            if ($status != 1) {
                $message = 'extend aduit fail';
            }
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $push = array(
                'userId' => $userId,
                'message' => $message,
                'createTime' => $now,
                'statusTime' => $now,
                'status' => 0,
                'registrationId' => $registrationId
            );
            $this->PushMessage_model->insert($push);

            //同意延期
            if($status == 1){
                // start
                $returnData = array(
                    'UserId'=>$userId,
                    'DebitId'=>$debitId,
                    'type'=>2,
                    'Status'=>0
                );
                $one1 = $this->UserPayBackDebitRecord_model->getRow('select id from IFUserPayBackDebitRecord WHERE UserId = '.$userId.' and DebitId = '.$debitId.' and type=2 and Status=0 order by id desc limit 1');
                $returnData = array(
                    'id'=>$one1['id']
                );
                $this->UserPayBackDebitRecord_model->update($returnData,array(
                    'Status'=> 1,
                    'money'=>$returnMoney,
                    'AdminId'=>$this->session->admin->id,
                    'StatusTime'=>date('Y-m-d H:i:s')
                ));

                $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);


                if($total > $item->DebitMoney){
                    $newalreadyReturnMoney = $item->DebitMoney;
                    $alreadyReturnInterest = $total - $item->DebitMoney;
                } else{
                    $alreadyReturnInterest = 0;
                    $newalreadyReturnMoney = $total;
                }

                $olddate = $item->payBackDayTime;
                if(!$olddate) {
                    $d = $now;
                }else if ($now > $olddate) {
                    $d = $now;
                } else{
                    $d = $olddate;
                }
//            }
//                if (!$olddate) {
//                    $olddate = $now;
//                }
                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$d +7 day"));

                $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                    'alreadyReturnMoney'=>$newalreadyReturnMoney,
                    'alreadyReturnInterest'=>$alreadyReturnInterest,
                    'Status' => $status,
                    'StatusTime' => $now,
                    'payBackDayTime' => $payBackDayTime,
                    'describe'=>$describe,
                    'overdueDay'=>0,
                    'overdueMoney'=>0
                ));
                $this->load->model('UserDebitOverdueRecord_model');
                $this->UserDebitOverdueRecord_model->update(array('debitId'=>$debitId),array('clearStatus'=>1),null,99);

                //end
                //延期通过,调用过程
                $res = $this->Common_model->query('call p_gen_report_debit_daily(' . $item->releaseLoanTime . ')');

            }else{
                //不同意延期
                $returnData = array(
                    'UserId'=>$userId,
                    'DebitId'=>$debitId,
                    'type'=>2,
                    'Status'=>0
                );
                $one1 = $this->UserPayBackDebitRecord_model->getRow('select id from IFUserPayBackDebitRecord WHERE UserId = '.$userId.' and DebitId = '.$debitId.' and type=2 and Status=0 order by id desc limit 1');
                $returnData = array(
                    'id'=>$one1['id']
                );
                $this->UserPayBackDebitRecord_model->update($returnData,array(
                    'Status'=> -1,
                    'money'=>$returnMoney,
                    'AdminId'=>$this->session->admin->id,
                    'StatusTime'=>date('Y-m-d H:i:s')
                ));

                $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);
//                $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));

                if($total > $item->DebitMoney){
                    $newalreadyReturnMoney = $item->DebitMoney;
                    $alreadyReturnInterest = $total - $item->DebitMoney;
                } else{
                    $alreadyReturnInterest = 0;
                    $newalreadyReturnMoney = $total;
                }

//                $olddate = $item->payBackDayTime;
//                if (!$olddate) {
//                    $olddate = $now;
//                }
//                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$olddate +7 day"));

                $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                    'alreadyReturnMoney'=>$newalreadyReturnMoney,
                    'alreadyReturnInterest'=>$alreadyReturnInterest,
//                    'Status' => $status,
                    'StatusTime' => $now,
//                    'payBackDayTime' => $payBackDayTime,
//                    'describe'=>$describe
                ));
            }

            if ($status == 1) {

                /*$one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));
                $olddate = $one->payBackDayTime;
                if (!$olddate) {
                    $olddate = $now;
                }
                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$olddate +7 day"));
                $update = array(
                    'Status' => $status,
                    'StatusTime' => $now,
                    'payBackDayTime' => $payBackDayTime,
                    'describe'=>$describe
                );
                $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);*/
            } else {
                //TODO 存储过程
                $res = $this->Common_model->query('call p_debit_extendfailure(' . $debitId . ')');
//                var_dump($res->result());exit;
            }

            $this->success();
        }
        $this->error();
    }

    /**
     * 已放款列表
     */
    public function released()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allReleased($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/released', $data);
        }
    }

    /**
     * 已放款列表
     */
    public function reminders()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->reminders($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/reminders', $data);
        }
    }

    /**
     * 图表
     */
    public function chart()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->chart($where);
            $res2 = $this->Debit_Record_model->chart2($where);
            $res3 = $this->Debit_Record_model->chart3($where);
            $data = array(
                'res' => $res,
                'res2' => $res2,
                'res3' => $res3
            );
            echo json_encode($data);
            exit;
        } else {
            $data = array();
            $this->load->view('admin/chart', $data);
        }
    }

    /**
     * 用户
     */
    public function user()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $this->load->model('User_model');
            $res = $this->User_model->export($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/user', $data);
        }
    }

    /**
     * 报表
     */
    public function daily()
    {
        if ($this->input->is_ajax_request()) {

            $startTime = $this->input->get('start_time');
            $endTime = $this->input->get('end_time');


            // 注册用户数
            $sql = "select count(*) s from IFUsers WHERE 1=1 ";
            if ($startTime) {
                $sql .= " AND RegTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND RegTime <= '" . $endTime . " '";
            }

            $result1 = $this->Common_model->getRow($sql);

            //新增用户数
            $sql = "select count(*) s from IFUsers WHERE RegTime >= '" . date('Y-m-d') . "'";
            $result2 = $this->Common_model->getRow($sql);

            //审核通过数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1";
            if ($startTime) {
                $sql .= " AND CreateTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND CreateTime <= '" . $endTime . " '";
            }
            $result3 = $this->Common_model->getRow($sql);

            //新增审核通过数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND  CreateTime>= '" . date('Y-m-d') . "'";
            $result4 = $this->Common_model->getRow($sql);

            //放款数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND payBackDayTime != null";
            if ($startTime) {
                $sql .= " AND CreateTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND CreateTime <= '" . $endTime . " '";
            }
            $result5 = $this->Common_model->getRow($sql);

            //新增放款数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND payBackDayTime != null AND CreateTime >= '" . date('Y-m-d') . "'";
            $result6 = $this->Common_model->getRow($sql);

            //未审核通过数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = -1";
            if ($startTime) {
                $sql .= " AND CreateTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND CreateTime <= '" . $endTime . " '";
            }
            $result7 = $this->Common_model->getRow($sql);

//        新增未审核通过数
            $sql = "select count(*) s from IFUserDebitRecord  WHERE Status = -1 AND CreateTime >= '" . date('Y-m-d') . "'";
            $result8 = $this->Common_model->getRow($sql);

            //还款数
            $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 3";
            if ($startTime) {
                $sql .= " AND CreateTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND CreateTime <= '" . $endTime . " '";
            }
            $result9 = $this->Common_model->getRow($sql);

            //新增还款数
            $sql = "select count(*) s from IFUserDebitRecord  WHERE Status = 3 AND CreateTime >= '" . date('Y-m-d') . "'";
            $result10 = $this->Common_model->getRow($sql);

            //延期数
            $sql = "select count(*) s from IFUserDebitRecord a JOIN IFUserAduitDebitRecord b ON a.DebitId = b.DebitId WHERE a.Status = 3  AND b.Status=1 AND b.AduitType=6";
            if ($startTime) {
                $sql .= " AND CreateTime >= '" . $startTime . " '";
            }
            if ($endTime) {
                $sql .= " AND CreateTime <= '" . $endTime . " '";
            }
            $result11 = $this->Common_model->getRow($sql);

            //新增延期数
            $sql = "select count(*) s from IFUserDebitRecord a JOIN IFUserAduitDebitRecord b ON a.DebitId = b.DebitId WHERE a.Status = 3 AND b.auditTime >= " . date('Y-m-d') . " AND b.Status=1 AND b.AduitType=6";
            $result12 = $this->Common_model->getRow($sql);
            $data = array(
                array('result1' => $result1['s'],
                    'result2' => $result2['s'],
                    'result3' => $result3['s'],
                    'result4' => $result4['s'],
                    'result5' => $result5['s'],
                    'result6' => $result6['s'],
                    'result7' => $result7['s'],
                    'result8' => $result8['s'],
                    'result9' => $result9['s'],
                    'result10' => $result10['s'],
                    'result11' => $result11['s'],
                    'result12' => $result12['s'])
            );

            $res = array('data' => $data, 'recordsTotal' => 1, 'recordsFiltered' => 1);
            //坏账数
            echo json_encode($res);
            //新增坏账数

        } else {
            $data = array();
            $this->load->view('admin/daily', $data);
        }
    }

    public function market()
    {
        if ($this->input->is_ajax_request()) {
            $sql = "select UserId,year(now())-year(a.birthday) age,Sex,residentialCity,typeOfWork,companyAddress,residentialAddress from IFUsers a WHERE 1=1 ";
            $result1 = $this->Common_model->getRows($sql);
            $sql2 = "SELECT UserId,count(`Status`) c from IFUserDebitRecord WHERE `Status` = 3 GROUP BY UserId ";
            $sql3 = "SELECT UserId,count(`Status`) c from IFUserDebitRecord WHERE `Status` = -2 GROUP BY UserId ";

            $result2 = $this->Common_model->getRows($sql2);
            $arr2 = array();
            foreach ($result2 as $k => $v) {
                $arr2[$v['UserId']] = $v['c'];
            }

            $result3 = $this->Common_model->getRows($sql3);
            $arr3 = array();
            foreach ($result3 as $k => $v) {
                $arr3[$v['UserId']] = $v['c'];
            }

            $data = array();
            foreach ($result1 as $k => $v) {
                $arr1 = $v;
                $arr1['pass'] = $arr2[$v['UserId']];
                $arr1['unpass'] = $arr3[$v['UserId']];
                array_push($data, $arr1);
            }
            $res = array('data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data));
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/market', $data);
        }
    }

    public function loanReport()
    {
        if ($this->input->is_ajax_request()) {
            $startTime = $this->input->get('start_time');
            $endTime = $this->input->get('end_time');

            $sql = " SELECT
                u.*,
                bank.*,
                year(now())-year(u.birthday) age,
                record.*, (
                    SELECT
                        count(DISTINCT c.phone)
                    FROM
                        IFUserContactInfo c,
                        IFUserContacts d
                    WHERE
                        c.userId = d.userId
                    AND c.phone = d.phone
                    AND c.userId =record.UserId
                ) yes
              FROM
	        IFUserDebitRecord record LEFT JOIN IFUsers u ON record.UserId = u.UserId LEFT JOIN IFUserBankInfo bank ON bank.UserId = record.UserId ";
            if ($startTime && $endTime) {
                $sql .= " where record.CreateTime >= '" . $startTime . "' AND record.CreateTime <='" . $endTime . "'";
            }
            $sql.=' limit 100';
//            var_dump($sql);exit;

            $data = $this->Common_model->getRows($sql);

            $res = array('data' => $data, 'recordsTotal' => count($data), 'recordsFiltered' => count($data));
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/loan_report', $data);
        }
    }


    public function export1()
    {
        $startTime = $this->input->get('start_time');
        $endTime = $this->input->get('end_time');


        // 注册用户数
        $sql = "select count(*) s from IFUsers WHERE 1=1 ";
        if ($startTime) {
            $sql .= " AND RegTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND RegTime <= '" . $endTime . " '";
        }

        $result1 = $this->Common_model->getRow($sql);

        //新增用户数
        $sql = "select count(*) s from IFUsers WHERE RegTime >= '" . date('Y-m-d') . "'";
        $result2 = $this->Common_model->getRow($sql);

        //审核通过数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1";
        if ($startTime) {
            $sql .= " AND CreateTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND CreateTime <= '" . $endTime . " '";
        }
        $result3 = $this->Common_model->getRow($sql);

        //新增审核通过数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND  CreateTime>= '" . date('Y-m-d') . "'";
        $result4 = $this->Common_model->getRow($sql);

        //放款数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND payBackDayTime != null";
        if ($startTime) {
            $sql .= " AND CreateTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND CreateTime <= '" . $endTime . " '";
        }
        $result5 = $this->Common_model->getRow($sql);

        //新增放款数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 1 AND payBackDayTime != null AND CreateTime >= '" . date('Y-m-d') . "'";
        $result6 = $this->Common_model->getRow($sql);

        //未审核通过数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = -1";
        if ($startTime) {
            $sql .= " AND CreateTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND CreateTime <= '" . $endTime . " '";
        }
        $result7 = $this->Common_model->getRow($sql);

//        新增未审核通过数
        $sql = "select count(*) s from IFUserDebitRecord  WHERE Status = -1 AND CreateTime >= '" . date('Y-m-d') . "'";
        $result8 = $this->Common_model->getRow($sql);

        //还款数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status = 3";
        if ($startTime) {
            $sql .= " AND CreateTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND CreateTime <= '" . $endTime . " '";
        }
        $result9 = $this->Common_model->getRow($sql);

        //新增还款数
        $sql = "select count(*) s from IFUserDebitRecord  WHERE Status = 3 AND CreateTime >= '" . date('Y-m-d') . "'";
        $result10 = $this->Common_model->getRow($sql);

        //延期数
        $sql = "select count(*) s from IFUserDebitRecord a JOIN IFUserAduitDebitRecord b ON a.DebitId = b.DebitId WHERE a.Status = 3  AND b.Status=1 AND b.AduitType=6";
        if ($startTime) {
            $sql .= " AND CreateTime >= '" . $startTime . " '";
        }
        if ($endTime) {
            $sql .= " AND CreateTime <= '" . $endTime . " '";
        }
        $result11 = $this->Common_model->getRow($sql);

        //新增延期数
        $sql = "select count(*) s from IFUserDebitRecord a JOIN IFUserAduitDebitRecord b ON a.DebitId = b.DebitId WHERE a.Status = 3 AND b.auditTime >= " . date('Y-m-d') . " AND b.Status=1 AND b.AduitType=6";
        $result12 = $this->Common_model->getRow($sql);
        $data = array(
            'Register No.' => $result1['s'],
            'New Added No.' => $result2['s'],
            'Loan Approval No.' => $result3['s'],
            'New Added Approval No.' => $result4['s'],
            'Loan Paid No.' => $result5['s'],
            'New Added Loan Paid No.' => $result6['s'],
            'Unpass NO.' => $result7['s'],
            'New Added Upass No.' => $result8['s'],
            'Payback No.' => $result9['s'],
            'New Added Payback' => $result10['s'],
            'Extend No.' => $result11['s'],
            'New Added Extend' => $result12['s']
        );
//        $res = array('data' => $data, 'recordsTotal' => 1, 'recordsFiltered' => 1);
        //坏账数
        export_excel(array($data), array_keys($data), 'daily');
    }

    public function export2()
    {
        $sql = "select UserId,year(now())-year(a.birthday) age,Sex,residentialCity,typeOfWork,companyAddress,residentialAddress from IFUsers a WHERE 1=1 ";
        $result1 = $this->Common_model->getRows($sql);
        $sql2 = "SELECT UserId,count(`Status`) c from IFUserDebitRecord WHERE `Status` = 3 GROUP BY UserId ";
        $sql3 = "SELECT UserId,count(`Status`) c from IFUserDebitRecord WHERE `Status` = -2 GROUP BY UserId ";

        $result2 = $this->Common_model->getRows($sql2);
        $arr2 = array();
        foreach ($result2 as $k => $v) {
            $arr2[$v['UserId']] = $v['c'];
        }

        $result3 = $this->Common_model->getRows($sql3);
        $arr3 = array();
        foreach ($result3 as $k => $v) {
            $arr3[$v['UserId']] = $v['c'];
        }

        $data = array();
        foreach ($result1 as $k => $v) {
            $arr1 = $v;
            $arr1['pass'] = $arr2[$v['UserId']];
            $arr1['unpass'] = $arr3[$v['UserId']];
            array_push($data, $arr1);
        }

        $title = array(
            'UserId', 'Age', 'Gender', 'City', 'Work title', 'Work Address', 'Home Address', 'pass', 'unpass'
        );
//        $res = array('data' => $data, 'recordsTotal' => 1, 'recordsFiltered' => 1);
        //坏账数
        export_excel($data, $title, 'market');

    }


    public function export3()
    {
        $startTime = $this->input->get('start_time');
        $endTime = $this->input->get('end_time');

        $sql = " SELECT
                u.*,
                bank.*,
                year(now())-year(u.birthday) age,
                record.*, (
                    SELECT
                        count(DISTINCT c.phone)
                    FROM
                        IFUserContactInfo c,
                        IFUserContacts d
                    WHERE
                        c.userId = d.userId
                    AND c.phone = d.phone
                    AND c.userId =record.UserId
                ) yes
              FROM
	        IFUserDebitRecord record LEFT JOIN IFUsers u ON record.UserId = u.UserId LEFT JOIN IFUserBankInfo bank ON bank.UserId = record.UserId";
        if ($startTime && $endTime) {
            $sql .= " where record.CreateTime >= '" . $startTime . "' AND record.CreateTime <='" . $endTime . "'";
        }

        $res = $this->Common_model->getRows($sql);
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            array_push($item, $v['CreateTime']);
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, 'ID:'.$v['IdCard']);
            array_push($item, $v['fullName']);
            array_push($item, $v['age']);
            array_push($item, $v['residentialAddress']);
            array_push($item, $v['residentialCity']);
            array_push($item, $v['residentialProvince']);
            array_push($item, $v['Phone']);
            array_push($item, $v['BankName']);
            array_push($item, $v['BankCode']);
            array_push($item, '');
            array_push($item, $this->config->item('statusEnum')[$v['Status']]);

            array_push($item, $v['yes']);
            array_push($item, '');
            array_push($item, '');
            array_push($data, $item);
        }
        $title = array(
            'Date',
            'Loan Reference ID',
            'User ID',
            'ID card',
            'Name',
            'Age',
            'Residential Address',
            'City',
            'Province',
            'Phone Number',
            'Bank Name',
            'Bank Account Number',
            'Reason',
            'Status',
            'Yes No.',
            'No. of Loan',
            'Loan Process'
        );
        export_excel($data, $title, 'loanRecord');
    }

    public function export4()
    {
        $startTime = $this->input->get('start_time');
        $endTime = $this->input->get('end_time');

        $sql = "select b.*,a.*,b.Status as userStatus,c.BankName,c.BankCode  from IFUserDebitRecord a left 
JOIN IFUsers b ON a.UserId = b.UserId left JOIN IFUserBankInfo c ON a.BankId = c.BankId 
 where a.Status in (5) and audit_step = 1 ";
        if ($startTime && $endTime) {
            $sql .= " and  a.StatusTime >= '" . $startTime . "' AND a.StatusTime <='" . $endTime . "'";
        }
        $sql.=' order by StatusTime ';

        $res = $this->Common_model->getRows($sql);
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['fullName']);
            array_push($item, $v['BankName']);
            array_push($item, $v['Phone']);
            array_push($item, "'".$v['BankCode']);
            array_push($item, $v['StatusTime']);
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'Name',
            'Bank Name',
            'Mobile Number',
            'Bank Account',
            'Approval Time'
        );
        export_excel($data, $title, 'loadPaid');
    }

    public function paybackuser()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allPaybackuser($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/paybackuser', $data);
        }
    }

    public function paybacked()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            $res = $this->Debit_Record_model->allRepayment($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/paybacked', $data);
        }
    }

    public function paybackedCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/paybacked_check', $data);
    }

    public function blacklist()
    {
        $this->load->model('User_model');
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            $res = $this->User_model->blacklist($where);
            echo json_encode($res);
        } else {
            $data = array();
            $users = $this->User_model->userSelect();
            $data['users'] = $users;
            $this->load->view('admin/blacklist', $data);
        }
    }

    public function addBlacklist($userId = null)
    {
        if (!$userId) {
            $this->error('用户名不为空');
        }
        $row = $this->Common_model->getRow('select * from t_blacklist WHERE user_id = '.$userId);
        if($row){
            $this->error('已经添加过了');
        }
        //insert($data, $table = '', $return = false)
        $this->Common_model->insert(array('user_id'=>$userId,'create_time'=>date('Y-m-d H:i:s')), 't_blacklist');
        $this->success();
    }

    public function removeBlacklist($userId = null)
    {
        if (!$userId) {
            $this->error('用户名不为空');
        }
        //function delete($where, $table = '', $limit = 1)
        $this->Common_model->delete(array('user_id'=>$userId),'t_blacklist');
        $this->success();
    }

    public function badlist()
    {
        $this->load->model('User_model');
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $where = array();
            if ($status !== '') {
                $where['status'] = $status;
            }
            $res = $this->User_model->badlist($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/badlist', $data);
        }
    }

    public function addBad($debitId = null)
    {
        if (!$debitId) {
            $this->error('用户名不为空');
        }
        $row = $this->Common_model->getRow('select * from t_bad WHERE debit_id = '.$debitId);
        if($row){
            $this->error('已经添加过了');
        }
        $res = $this->Common_model->insert(array('debit_id'=>$debitId,'create_time'=>date('Y-m-d H:i:s')), 't_bad');
        $this->success();
    }

    public function removeBad($debitId = null)
    {
        if (!$debitId) {
            $this->error('参数不为空');
        }
        //function delete($where, $table = '', $limit = 1)
        $this->Common_model->delete(array('debit_id'=>$debitId),'t_bad');
        $this->success();
    }

    public function auditRecord($userId = null){
        $data = $this->UserAduitDebitRecord_model->all2(array('userId'=>$userId));
        $this->load->view('admin/audit_record', array('data'=>$data));
    }

    public function uploadCert()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->Debit_Record_model->allUploadCert(array());
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/upload_cert', $data);
        }
    }

    public function uploadCertCheck($id)
    {
        $row = $this->Common_model->getRow('select * from IFUserPayBackDebitRecord where status = 0 and DebitId = ' . $id);
        if ($row) {
            echo 'Auditing Record is not empty,you can\'t upload again.';exit;
        }
        $this->load->view('admin/upload_cert_check', array('id'=>$id));
    }

    public function doUploadCert()
    {
        $debitId = $this->input->get_post('debitId');
        $type = $this->input->get_post('type');
        $certificateUrl = $this->input->get_post('url');
        $one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));
        $now = date('Y-m-d H:i:s');
        $data = array(
            'type'=>$type,
            'DebitId'=>$debitId,
            'Status'=>0,
            'CreateTime'=>$now,
            'certificateUrl'=>$certificateUrl,
            'UserId'=> $one->UserId
        );
//        var_dump($data);exit;
        $ret = $this->UserPayBackDebitRecord_model->insert($data);
//        var_dump($ret);exit;

        $type2 = $type == 1 ? 2 : 6;
        $this->Debit_Record_model->update(array('DebitId'=>$debitId),array(
            'Status'=> $type2,
            'StatusTime'=>$now
        ));

        $this->success();
    }

    /**
     * 延期2
     */
    public function duextend()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allDuextend($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/duextend', $data);
        }
    }

    public function duextendCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/duextend_check', $data);
    }

    public function doDuextend()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $returnMoney = $this->input->post('returnMoney');
            $aduitType = 6;
            $s = 2;
            if ($status == 1) {
                $s = 1;
                $description = 'perpanjangan dalam proses';
                if($returnMoney <= 0){
//                    $this->error('returnMoney must more than zero.');
                }
            }
            $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));
            if ($item->Status != 6) {
                $this->error('can\'t repeat audit again.');
            }
            $now = date('Y-m-d H:i:s');
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $s,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'Remark'=>$describe,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);

            $message = 'extend aduit success';
            if ($status != 1) {
                $message = 'extend aduit fail';
            }
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $push = array(
                'userId' => $userId,
                'message' => $message,
                'createTime' => $now,
                'statusTime' => $now,
                'status' => 0,
                'registrationId' => $registrationId
            );
            $this->PushMessage_model->insert($push);

            //同意延期
            if($status == 1){
                // start
                $returnData = array(
                    'UserId'=>$userId,
                    'DebitId'=>$debitId,
                    'type'=>2,
                    'Status'=>0
                );
                $one1 = $this->UserPayBackDebitRecord_model->getRow('select id from IFUserPayBackDebitRecord WHERE UserId = '.$userId.' and DebitId = '.$debitId.' and type=2 and Status=0 order by id desc limit 1');
                $returnData = array(
                    'id'=>$one1['id']
                );
                $this->UserPayBackDebitRecord_model->update($returnData,array(
                    'Status'=> 1,
//                    'money'=>$returnMoney,
                    'AdminId'=>$this->session->admin->id,
                    'StatusTime'=>date('Y-m-d H:i:s')
                ));

                $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);


                if($total > $item->DebitMoney){
                    $newalreadyReturnMoney = $item->DebitMoney;
                    $alreadyReturnInterest = $total - $item->DebitMoney;
                } else{
                    $alreadyReturnInterest = 0;
                    $newalreadyReturnMoney = $total;
                }

                $olddate = $item->payBackDayTime;
                if(!$olddate) {
                    $d = $now;
                }else if ($now > $olddate) {
                    $d = $now;
                } else{
                    $d = $olddate;
                }
//            }
//                if (!$olddate) {
//                    $olddate = $now;
//                }
                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$d +7 day"));

                $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                    'alreadyReturnMoney'=>$newalreadyReturnMoney,
                    'alreadyReturnInterest'=>$alreadyReturnInterest,
                    'Status' => $status,
                    'StatusTime' => $now,
                    'payBackDayTime' => $payBackDayTime,
                    'describe'=>$describe,
                    'overdueDay'=>0,
                    'overdueMoney'=>0
                ));
                $this->load->model('UserDebitOverdueRecord_model');
                $this->UserDebitOverdueRecord_model->update(array('debitId'=>$debitId),array('clearStatus'=>1),null,99);

                //end
                //延期通过,调用过程
                $res = $this->Common_model->query('call p_gen_report_debit_daily(' . $item->releaseLoanTime . ')');

            }else{
                //不同意延期
                $returnData = array(
                    'UserId'=>$userId,
                    'DebitId'=>$debitId,
                    'type'=>3,
                    'Status'=>0
                );
                $one1 = $this->UserPayBackDebitRecord_model->getRow('select id from IFUserPayBackDebitRecord WHERE UserId = '.$userId.' and DebitId = '.$debitId.' and type=3 and Status=0 order by id desc limit 1');
                $returnData = array(
                    'id'=>$one1['id']
                );
                $this->UserPayBackDebitRecord_model->update($returnData,array(
                    'Status'=> -1,
//                    'money'=>$returnMoney,
                    'AdminId'=>$this->session->admin->id,
                    'StatusTime'=>date('Y-m-d H:i:s')
                ));

                $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);
//                $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));

                if($total > $item->DebitMoney){
                    $newalreadyReturnMoney = $item->DebitMoney;
                    $alreadyReturnInterest = $total - $item->DebitMoney;
                } else{
                    $alreadyReturnInterest = 0;
                    $newalreadyReturnMoney = $total;
                }

//                $olddate = $item->payBackDayTime;
//                if (!$olddate) {
//                    $olddate = $now;
//                }
//                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$olddate +7 day"));

                $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                    'alreadyReturnMoney'=>$newalreadyReturnMoney,
                    'alreadyReturnInterest'=>$alreadyReturnInterest,
//                    'Status' => $status,
                    'StatusTime' => $now,
//                    'payBackDayTime' => $payBackDayTime,
//                    'describe'=>$describe
                ));
            }

            if ($status == 1) {

                /*$one = $this->Debit_Record_model->get_one(array('DebitId' => $debitId));
                $olddate = $one->payBackDayTime;
                if (!$olddate) {
                    $olddate = $now;
                }
                $payBackDayTime = date('Y-m-d H:i:s', strtotime("$olddate +7 day"));
                $update = array(
                    'Status' => $status,
                    'StatusTime' => $now,
                    'payBackDayTime' => $payBackDayTime,
                    'describe'=>$describe
                );
                $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);*/
            } else {
                //TODO 存储过程
                $res = $this->Common_model->query('call p_debit_extendfailure(' . $debitId . ')');
//                var_dump($res->result());exit;
            }

            $this->success();
        }
        $this->error();
    }

    public function durepayment()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->allDurepayment($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/durepayment', $data);
        }
    }

    public function durepaymentCheck($id)
    {
        $item = $this->Debit_Record_model->get_record($id);
        $data = array(
            'item' => $item
        );
        $this->load->view('admin/durepayment_check', $data);
    }

    public function doDurepayment()
    {
        if ($this->input->is_ajax_request()) {
            $debitId = $this->input->post('debitId');
            $status = $this->input->post('status');
            $description = $this->input->post('description');
            $describe = $this->input->post('describe');
            $returnMoney = doubleval($this->input->post('returnMoney'));
            $aduitType = 2;
            $now = date('Y-m-d H:i:s');
            $s = 2;
            if ($status == 3) {
                $s = 1;
                $description = 'pengembalian dalam proses';
                if($returnMoney <= 0){
//                    $this->error('returnMoney must more than zero.');
                }
            }
            $data = array(
                'AduitType' => $aduitType,
                'DebitId' => $debitId,
                'Status' => $s,
                'Description' => $description,
                'AdminId' => $this->session->admin->id,
                'auditTime' => $now
            );
            $this->UserAduitDebitRecord_model->insert($data);


            // $one = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));
            //$DebitPeroid = $one->DebitPeroid;
            $record = $this->UserPayBackDebitRecord_model->get_one(array('DebitId' => $debitId, 'type' => 4));
            $createTime = $record->CreateTime;

            $update = array(
                'Status' => $status,
                'StatusTime' => $now,
                'describe'=>$describe
            );
            if ($status == 3) {
                //需求变动此处去了
                $update['userPaybackTime'] = $createTime;
            }

            $this->Debit_Record_model->update(array('DebitId' => $debitId), $update);
            $userId = $this->input->post('userId');
            $registrationId = $this->input->post('registrationId');
            $message = 'payback aduit success';
            if ($status != 3) {
                $message = 'payback aduit fail';
            } else{
//                $res = $this->Common_model->query('call p_gen_report_debit_daily(' .  . ')');
            }

            /*$returnData = array(
                'money'=>$returnMoney,
                'UserId'=>$userId,
                'DebitId'=>$debitId,
                'CreateTime'=>date('Y-m-d H:i:s'),
                'Status'=> ($status == 3 ? 1 : -1)
            );
            $this->UserPayBackDebitRecord_model->insert($returnData);*/

            $returnData = array(
//                'money'=>$returnMoney,
                'UserId'=>$userId,
                'DebitId'=>$debitId,
                'type'=>4,
//                'CreateTime'=>date('Y-m-d H:i:s'),
                'Status'=>0
//                'Status'=> ($status == 3 ? 1 : -1)
            );
            $this->UserPayBackDebitRecord_model->update($returnData,array(
                'Status'=> ($status == 3 ? 1 : -1),
//                'money'=>$returnMoney,
                'AdminId'=>$this->session->admin->id,
                'StatusTime'=>date('Y-m-d H:i:s')
            ));


            $total = $this->UserPayBackDebitRecord_model->sumTotalMoney($debitId);
            $item = $this->Debit_Record_model->get_one(array('DebitId'=>$debitId));

            if($total > $item->DebitMoney){
                $newalreadyReturnMoney = $item->DebitMoney;
                $alreadyReturnInterest = $total - $item->DebitMoney;
            } else{
                $alreadyReturnInterest = 0;
                $newalreadyReturnMoney = $total;
            }

//            var_dump()
            $this->Debit_Record_model->update(array('DebitId' => $debitId), array(
                'alreadyReturnMoney'=>$newalreadyReturnMoney,
                'alreadyReturnInterest'=>$alreadyReturnInterest
            ));

            //还款通过,调用过程
            if($status == 3){
                $res = $this->Common_model->query('call p_gen_report_debit_daily(' . $item->releaseLoanTime . ')');
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

    public function reminder_latelypayback()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->latelypayback($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/reminder_latelypayback', $data);
        }
    }

    public function overdue0_latelypayback()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->Debit_Record_model->overdue0_latelypayback($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/overdue0_latelypayback', $data);
        }
    }

}
