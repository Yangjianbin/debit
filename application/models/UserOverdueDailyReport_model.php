<?php

class UserOverdueDailyReport_model extends Common_model{

    var $table = 'IFUserOverdueDailyReport';

    public function __construct(){
        parent::__construct();
    }

    //select DATE_FORMAT(o.OrderDate,'%Y_%u') weeks,count(*) count from orders o group by weeks;
    // str_to_date(‘2017-10-16 15:30:28’,’%Y-%m-%d %H:%i:%s’);
    public function all($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                dateId,
                overdueDebitCount,
                shouldPaybackMoney,
                alreadyPaybackMoney,
                paybackRate,
                alreadyPayInterest,
                alreadyBackTotalMoney,
                totalBackRate
            FROM
                IFUserOverdueDailyReport
            ';
        $sql .= ' order by dateId desc limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT
                count(*) s
            FROM IFUserOverdueDailyReport';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function all2($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\') week,
                sum(overdueDebitCount) overdueDebitCount,
                sum(shouldPaybackMoney) shouldPaybackMoney,
                sum(alreadyPaybackMoney) alreadyPaybackMoney,
                sum(alreadyPayInterest) alreadyPayInterest,
                sum(alreadyBackTotalMoney) alreadyBackTotalMoney
            FROM
                IFUserOverdueDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')
            ';
        $sql .= ' order by dateId desc limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT
	count( * ) s 
FROM
	(
SELECT
	1 
FROM
	IFUserOverdueDailyReport 
GROUP BY
	DATE_FORMAT( str_to_date( dateId, \'%Y%m%d\' ), \'%Y_%u\' ) 
	) a ';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function all3($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y%m\') `month`,
                sum(overdueDebitCount) overdueDebitCount,
                sum(shouldPaybackMoney) shouldPaybackMoney,
                sum(alreadyPaybackMoney) alreadyPaybackMoney,
                sum(alreadyPayInterest) alreadyPayInterest,
                sum(alreadyBackTotalMoney) alreadyBackTotalMoney
            FROM
                IFUserOverdueDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y%m\')
            ';
        $sql .= ' order by dateId desc limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT
                count(*) s
            FROM IFUserOverdueDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }


}