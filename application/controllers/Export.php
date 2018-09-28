<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Debit_Record_model');
        $this->load->model('UserAduitDebitRecord_model');
        $this->load->model('IFCertificate_model');
        $this->load->model('Common_model');
        $this->load->model('DebitDaily_model');
        $this->load->model('NewUserDailyReport_model');
        $this->load->model('UserPaybackDailyReport_model');
        $this->load->model('UserOverdueDailyReport_model');
        $this->load->model('Export_model');
    }

    public function index()
    {
        //总放贷笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (-2,1,2,3,4,6)";
        $zongfangkuanbishu = $this->Common_model->getRow($sql);
        $zongfangkuanbishu = $zongfangkuanbishu['s'];

        //已放款笔数
        $sql ="SELECT
	count(*) s
FROM
	IFUserDebitRecord c
WHERE
	`DebitId` IN (
		SELECT
			DebitId
		FROM
			(
				SELECT
					DebitId,
					AduitType,
					max(Id)
				FROM
					IFUserAduitDebitRecord
				WHERE
					`Status` = 1
				AND AduitType = 3
				GROUP BY
					DebitId
			) a
	) 
AND c.`Status` = 1";
        $yifangkuanbishu = $this->Common_model->getRow($sql);
        $yifangkuanbishu = $yifangkuanbishu['s'];

        //还款申请笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (2)";
        $huankuanshengqibishu = $this->Common_model->getRow($sql);
        $huankuanshengqibishu = $huankuanshengqibishu['s'];

        //还款笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (3)";
        $huankuanbishu = $this->Common_model->getRow($sql);
        $huankuanbishu = $huankuanbishu['s'];

        //申请延期笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (6)";
        $shenqingyanqibishu = $this->Common_model->getRow($sql);
        $shenqingyanqibishu = $shenqingyanqibishu['s'];

        //还款不通过笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (-2)";
        $huankuanbutongguobishu = $this->Common_model->getRow($sql);
        $huankuanbutongguobishu = $huankuanbutongguobishu['s'];

        //已逾期笔数
        $sql = "select count(*) s from IFUserDebitRecord WHERE Status IN (4)";
        $yuqibishu = $this->Common_model->getRow($sql);
        $yuqibishu = $yuqibishu['s'];

        //延期笔数
        $sql ="SELECT
	count(*) s
FROM
	IFUserDebitRecord c
WHERE
	`DebitId` IN (
		SELECT
			DebitId
		FROM
			(
				SELECT
					DebitId,
					AduitType,
					max(Id)
				FROM
					IFUserAduitDebitRecord
				WHERE
					`Status` = 1
				AND AduitType = 6
				GROUP BY
					DebitId
			) a
	) 
AND c.`Status` = 1";
        $yanqibishu = $this->Common_model->getRow($sql);
        $yanqibishu = $yanqibishu['s'];


        //重复延期通过次数
        $sql = "SELECT
	count(*) s
FROM
	IFUserDebitRecord a
WHERE
	`DebitId` IN (
		SELECT
			DebitId
		FROM
			IFUserAduitDebitRecord
		WHERE
			`Status` = 1
		AND AduitType = 6
		GROUP BY
			DebitId
		HAVING
			count(DebitId) > 1
	)
AND a.`Status` = 1";
        $chonfuyanqitongguo = $this->Common_model->getRow($sql);
        $chonfuyanqitongguo = $chonfuyanqitongguo['s'];

        //延期人数（含未到期人数）
        $sql = "SELECT
	count(*) s
FROM
	IFUserDebitRecord a
LEFT JOIN (
	SELECT
		DebitId,
		AduitType,
		max(Id)
	FROM
		IFUserAduitDebitRecord
	GROUP BY
		DebitId
) b ON a.DebitId = b.DebitId WHERE a.`Status` in (-2,1,2,4,6)";
        $yanqirenshu = $this->Common_model->getRow($sql);
        $yanqirenshu = $yanqirenshu['s'];

        //总成本
        $zongchengben = intval($zongfangkuanbishu) * 1500000;
        //收款
        $shoukuan = intval($huankuanbishu) * 2000000 + intval($yanqibishu) * 500000;
        //待收款
        $daishoukuan = intval($yanqirenshu) * 2000000;

        //待收款（借款未还）
        $sql = "SELECT
	count(*) s
FROM
	IFUserDebitRecord c
WHERE
	`DebitId` IN (
		SELECT
			DebitId
		FROM
			(
				SELECT
					DebitId,
					AduitType,
					max(Id)
				FROM
					IFUserAduitDebitRecord
				WHERE
					`Status` = 1
				AND AduitType = 3
				GROUP BY
					DebitId
			) a
	) 
AND c.`Status` = 1";
        $query = $this->Common_model->getRow($sql);
        $daishoukuan_jiekuanweihuan = $query['s'] * 2000000;

        //待收款(延期未还)
        $sql = "SELECT
	count(*) s
FROM
	IFUserDebitRecord c
WHERE
	`DebitId` IN (
		SELECT
			DebitId
		FROM
			(
				SELECT
					DebitId,
					AduitType,
					max(Id)
				FROM
					IFUserAduitDebitRecord
				WHERE
					`Status` = 1
				AND AduitType = 6
				GROUP BY
					DebitId
			) a
	) 
AND c.`Status` = 1";
        $query = $this->Common_model->getRow($sql);
        $daishoukuan_yanqiweihuan = $query['s'] * 2000000;
        //待收款（逾期未还）
        $daishoukuan_yuqiweihuan = $yuqibishu * 2000000;
        //待收款（还款申请未通过）
        $daishoukuan_huankuanweitongguo = $huankuanbutongguobishu * 2000000;

        //待收款（还款申请中）
        $daishoukuan_huankuanshenqingzhong = $huankuanshengqibishu * 2000000;


        //毛利（亏损）
        $maoli = $shoukuan - $zongchengben;

        //回款率
        $huikuanlv = $shoukuan / $zongchengben;

        //还款率
        $huankuanlv = intval($huankuanbishu) / intval($zongfangkuanbishu);

        //延期率
        $yanqilv = intval($yanqibishu) / intval($zongfangkuanbishu);

        //重复延期率
        $chongfuyanqilv = intval($chonfuyanqitongguo) / intval($zongfangkuanbishu);

        $data = array(
//            '总放贷笔数' => $zongfangdairenshu,
//            '还款笔数' => $huankuangrenshu,
//            '延期通过次数' => $yanqitongguo,
            '重复延期通过次数' => $chonfuyanqitongguo,
            '延期人数（含未到期人数）'=>$yanqirenshu,
            '收款' => $shoukuan,
            '待收款' => $daishoukuan,
            '总成本' => $zongchengben,
            '毛利（亏损）' => $maoli,
            '回款率' => $huikuanlv,
            '还款率' => $huankuanlv,
            '延期率' => $yanqilv,
            '重复延期率' => $chongfuyanqilv
        );
        $data1 = array(
            'zongfangkuanbishu'=>$zongfangkuanbishu,
            'yifangkuanbishu'=>$yifangkuanbishu,
            'huankuanbishu'=>$huankuanbishu,
            'shenqingyanqibishu'=>$shenqingyanqibishu,
            'huankuanshengqibishu'=>$huankuanshengqibishu,
//            'zongfangdairenshu'=>$zongfangdairenshu,
            'daishoukuan_huankuanweitongguo'=>$daishoukuan_huankuanweitongguo,
            'daishoukuan_huankuanshenqingzhong'=>$daishoukuan_huankuanshenqingzhong,
            'yuqibishu'=>$yuqibishu,
            'huankuanbutongguobishu'=>$huankuanbutongguobishu,
//            'huankuangrenshu' => $huankuangrenshu,
//            'yanqitongguo' => $yanqitongguo,
            'yanqirenshu'=>$yanqirenshu,
            'chonfuyanqitongguo' => $chonfuyanqitongguo,
            'shoukuan' => $shoukuan,
            'daishoukuan' => $daishoukuan,
            'zongchengben' => $zongchengben,
            'maoli' => $maoli,
            'huikuanlv' => 100 * sprintf("%.4f", $huikuanlv) . '%',
            'huankuanlv' => 100 * sprintf('%.4f',$huankuanlv). '%',
            'yanqilv' => 100 * sprintf('%.4f',$yanqilv). '%',
            'chongfuyanqilv' => 100 * sprintf('%.4f',$chongfuyanqilv). '%',
            'yanqibishu'=>$yanqibishu,
            'daishoukuan_jiekuanweihuan'=>$daishoukuan_jiekuanweihuan,
            'daishoukuan_yanqiweihuan'=>$daishoukuan_yanqiweihuan,
            'daishoukuan_yuqiweihuan'=>$daishoukuan_yuqiweihuan
        );
        $this->load->view('admin/export', $data1);
//        print_r($data);
    }

    public function daily()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $where = array();
            $res = $this->DebitDaily_model->allDailyReport($where);
            echo json_encode($res);
        } else {
            $data = array();
//            $res = $this->DebitDaily_model->allDailyReport();
//            var_dump($res);exit;
            $this->load->view('admin/export_daily', $data);
        }
    }

    public function weekly()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get_post('status');
            $where = array();
            $res = $this->DebitDaily_model->allWeeklyReport($where);
            echo json_encode($res);
        } else {
            $data = array();
//            $res = $this->DebitDaily_model->allDailyReport();
//            var_dump($res);exit;
            $this->load->view('admin/export_weekly', $data);
        }
    }

    public function monthly()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->DebitDaily_model->allMonthlyReport($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_monthly', $data);
        }
    }


    public function total()
    {
        $where = array();
        $res = $this->DebitDaily_model->allReport($where);
        echo json_encode($res);
    }

    public function newDaily()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->NewUserDailyReport_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_new_daily', $data);
        }
    }

    public function reconciliation()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $debitId = $this->input->get_post('debitid');
            if ($debitId) {
                $where['IFUserDebitRecord.DebitId'] = $debitId;
            }
            $userid = $this->input->get_post('userid');
            if ($userid) {
                $where['IFUserDebitRecord.UserId'] = $userid;
            }
            $type = $this->input->get_post('type');
            if ($type) {
                $where['type'] = $type;
            }

            $startTime = $this->input->get_post('startTime');
            if ($startTime) {
                $where['IFUserPayBackDebitRecord.StatusTime >= '] = $startTime;
            }

            $endTime = $this->input->get_post('endTime');
            if ($endTime) {
                $where['IFUserPayBackDebitRecord.StatusTime <= '] = $endTime;
            }

            $releaseLoanTimeStart = $this->input->get_post('releaseLoanTimeStart');
            if ($releaseLoanTimeStart) {
                $where['releaseLoanTime >= '] = $releaseLoanTimeStart;
            }
            $releaseLoanTimeEnd = $this->input->get_post('releaseLoanTimeEnd');
            if ($releaseLoanTimeEnd) {
                $where['releaseLoanTime <= '] = $releaseLoanTimeEnd;
            }

            $this->load->model('UserPayBackDebitRecord_model');
            $res = $this->UserPayBackDebitRecord_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/reconciliation', $data);
        }
    }

    public function overdue(){
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
        if ($paid) {
            $sql .= ' and a.alreadyReturnMoney + a.alreadyReturnInterest > 0 ';
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
        $sql .= ' order by redStar desc, greenStar desc,a.DebitId desc limit 100';
        $query = $this->Common_model->query($sql);
        $res = $query->result();

        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['fullName']);
            array_push($item, $v['payBackDayTime']);
            array_push($item, $v['overdueDay']);
            array_push($item, $v['overdueMoney']);
            array_push($item, $v['AlreadyReturnMoney']);
            array_push($item, $v['Phone']);
            array_push($item, $v['releaseLoanTime']);
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'Name',
            'PayPack Day Time',
            'Overdue Days',
            'OverdueMoney',
            'AlreadyReturnMoney',
            'Mobile Number',
            'Release Time'
        );
        export_excel($data, $title, 'overdue');
    }

    public function loan()
    {
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
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc limit 100';
        $query = $this->Common_model->query($sql);
        $res = $query->result();

        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['Star']);
            array_push($item, $v['fullName']);
            array_push($item, $v['Phone']);
            array_push($item, $v['CreateTime']);
            array_push($item, $this->config->item('statusEnum')[$v['Status']]);
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'Star',
            'Name',
            'Mobile Number',
            'Apply Time',
            'status'
        );
        export_excel($data, $title, 'loan');
    }

    public function released()
    {
//        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
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
        $startTime = $this->input->get_post('start_time');
        $endTime = $this->input->get_post('end_time');
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
//        echo $sql;exit;
        if (!empty($where)) {
            //TODO
        }
        $query = $this->Common_model->query($sql);
        $res = $query->result();
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['Star']);
            array_push($item, $v['fullName']);
            array_push($item, $v['Phone']);
            array_push($item, $v['CreateTime']);
            array_push($item, $v['releaseLoanTime']);
            array_push($item, $v['payBackDayTime']);
            array_push($item, $this->config->item('statusEnum')[$v['Status']]);
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'Star',
            'Name',
            'Mobile Number',
            'Apply Time',
            'Mobile Number',
            'Payback date',
            'status'
        );
        export_excel($data, $title, 'released');
    }

    public function daypayback(){
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserPaybackDailyReport_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_daypayback', $data);
        }
    }

    public function weekpayback()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserPaybackDailyReport_model->all2($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_weekpayback', $data);
        }
    }

    public function monthpayback()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserPaybackDailyReport_model->all3($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_monthpayback', $data);
        }
    }

    public function dayoverdue()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserOverdueDailyReport_model->all($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_dayoverdue', $data);
        }
    }

    public function weekoverdue()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserOverdueDailyReport_model->all2($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_weekoverdue', $data);
        }
    }

    public function monthoverdue()
    {
        if ($this->input->is_ajax_request()) {
            $where = array();
            $res = $this->UserOverdueDailyReport_model->all3($where);
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('admin/export_monthoverdue', $data);
        }
    }

    public function badlist()
    {
        $sql = 'SELECT * from t_bad a JOIN IFUserDebitRecord b ON a.debit_id = b.DebitId JOIN IFUsers u ON u.UserId = b.UserId';
        $query = $this->Common_model->query($sql);
        $res = $query->result();
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['UserId']);
            array_push($item, $v['UserName']);
            array_push($item, $v['Phone']);
            array_push($item, $v['DebitMoney']);
            array_push($item, $v['payBackMoney']);
            array_push($item, $v['CreateTime']);
            array_push($item, $v['overdueMoney']);
            array_push($item, $v['overdueDay']);
            array_push($data, $item);
        }
        $title = array(
            'User ID',
            'UserName',
            'Phone',
            'DebitMoney',
            'payBackMoney',
            'Create Time',
            'overdueMoney',
            'overdueDay'
        );
        export_excel($data, $title, 'badlist');
    }

    public function daypayback_sub($dateId = null)
    {
        if ($this->input->is_ajax_request()) {
            $where = array('dateId'=>$dateId);
            $res = $this->UserPaybackDailyReport_model->daypayback_sub($where);
            echo json_encode($res);
        } else {
            $data = array('dateId'=>$dateId);
            $this->load->view('admin/export_daypayback_sub', $data);
        }
    }

    public function export_export1()
    {
        if ($this->input->is_ajax_request()) {
            $res1 = $this->Export_model->export1();
            $res2 = $this->Export_model->export2();
            $res3 = $this->Export_model->export3();
            $res4 = $this->Export_model->export4();
            $data = array(
                'res1'=>$res1,
                'res2'=>$res2,
                'res3'=>$res3,
                'res4'=>$res4
            );
            echo json_encode($data);
        } else {
            $data = array();
            $this->load->view('export/export_export1', $data);
        }
    }

    public function export_export2()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->Export_model->export2();
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('export/export_export2', $data);
        }
    }

    public function export_export3()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->Export_model->export3();
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('export/export_export3', $data);
        }
    }

    public function export_export4()
    {
        if ($this->input->is_ajax_request()) {
            $res = $this->Export_model->export4();
            echo json_encode($res);
        } else {
            $data = array();
            $this->load->view('export/export_export4', $data);
        }
    }

    public function task()
    {
        $status = $this->input->get_post('status');
        //获取查询type类别

        $adminId = $this->session->admin->id;
//        $one = $this->Common_model->getRow('select * from IFAuditTasks WHERE adminId = '. $adminId);

        $in = ' where t.adminId = '. $adminId .' ';

        $sql = 'select b.*,a.*,b.Status userStatus,t.taskType,t.taskId,t.status,t_admin.username username,t.remark  from IFAuditTasks t  left JOIN IFUserDebitRecord a
         ON t.debitId = a.DebitId left JOIN IFUsers b ON a.UserId = b.UserId LEFT JOIN t_admin on t_admin.id = t.adminId';
        $sql .= $in;

        $debitid = $this->input->get_post('debitid');
        $userid = $this->input->get_post('userid');
        $username = $this->input->get_post('username');
        $taskType = null;

        if($status!=null){
            $sql .= ' and t.status = '. $status;
        }
        if ($taskType) {
            $sql .= ' and t.taskType = '. $taskType;
        }
        if ($debitid) {
            $sql .= ' and a.DebitId = ' . $debitid;
        }

        if ($username) {
            $sql .= ' and b.UserName = \'' . $username . '\'';
        }

        if ($userid) {
            $sql .= ' and a.UserId = ' . $userid;
        }

        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc ';
        $query = $this->Common_model->query($sql);
        $res = $query->result();
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['facebookId']);
            array_push($item, $v['redStar']);
            array_push($item, $v['fullName']);
            array_push($item, $v['Phone']);
            array_push($item, $v['CreateTime']);
            array_push($item, $v['remark']);
            array_push($item, $v['Status']);
            array_push($item, $v['freeze'] == 1 ? 'Freezed' : '');
            array_push($item, $v['status'] == 1 ? 'DONE' : 'UN DONE');
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'facebookId',
            'Star',
            'Name',
            'Mobile Number',
            'Apply Time',
            'Remark',
            'status',
            'freezeStatus',
            'Audit Status'
        );
        export_excel($data, $title, 'task');
    }

    public function alltask()
    {
        $status = $this->input->get_post('status');
        $adminId2 = $this->input->get_post('adminId');
        $admin = $this->input->get_post('admin');
        $in = ' where 1 = 1 ';
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
//        $taskType = $this->input->get_post('taskType');
        $taskType = null;
        $taskType = 1;

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
            $sql .= ' and a.CreateTime >= \'' . $startTime . '\'';
        }
        if ($endTime) {
            $sql .= ' and a.CreateTime <= \'' . $endTime . '\'';
        }


        if (!empty($where)) {
            //TODO
        }
        $sql .= ' order by redStar desc, greenStar desc,DebitId desc ';
//        echo $sql;exit;
        $query = $this->Common_model->query($sql);
        $res = $query->result();
        $data = array();
        foreach ($res as $k => $v) {
            $item = array();
            $v = (array)$v;
            array_push($item, $v['DebitId']);
            array_push($item, $v['UserId']);
            array_push($item, $v['facebookId']);
            array_push($item, $v['redStar']);
            array_push($item, $v['fullName']);
            array_push($item, $v['Phone']);
            array_push($item, $v['CreateTime']);
            array_push($item, $v['status'] == 1 ? 'DONE' : 'UN DONE');
            array_push($item, $v['overdueDay']);
            array_push($item, $v['overdueMoney']);
            array_push($item, $v['username']);
            array_push($data, $item);
        }
        $title = array(
            'Loan Reference No.',
            'User ID',
            'facebookId',
            'Star',
            'Name',
            'Mobile Number',
            'Apply Time',
            'Audit Status',
            'Overdue Days',
            'Overdue money amount',
            'Assign Admin'
        );
        export_excel($data, $title, 'alltask');
    }




}
