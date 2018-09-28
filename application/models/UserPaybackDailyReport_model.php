<?php

class UserPaybackDailyReport_model extends Common_model{

    var $table = 'IFUserPaybackDailyReport';

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
                shouldPayBackCount,
                paybackUserCount,
                extendUserCount,
                notPaybackUserCount,
                auditingCount,
                createTime,
                auditingCount,
                overdueUserCount,
                paybackUserCount/shouldPayBackCount,
                extendUserCount/shouldPayBackCount,
                (paybackUserCount + extendUserCount) / shouldPayBackCount
            FROM
                IFUserPaybackDailyReport
            ';
        $sql .= ' order by dateId desc limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT
                count(*) s
            FROM IFUserPaybackDailyReport';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function all2($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\') week,
                sum(shouldPayBackCount) shouldPayBackCount,
                sum(paybackUserCount) paybackUserCount,
                sum(extendUserCount) extendUserCount,
                sum(notPaybackUserCount) notPaybackUserCount,
                min(dateId) dateId
            FROM
                IFUserPaybackDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')
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
	IFUserPaybackDailyReport 
GROUP BY
	DATE_FORMAT( str_to_date( dateId, \'%Y%m%d\' ), \'%Y_%u\' ) 
	) a';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function all3($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y%m\') `month`,
                sum(shouldPayBackCount) shouldPayBackCount,
                sum(paybackUserCount) paybackUserCount,
                sum(extendUserCount) extendUserCount,
                sum(notPaybackUserCount) notPaybackUserCount
            FROM
                IFUserPaybackDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y%m\')
            ';
        $sql .= ' order by dateId desc limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT
                count(*) s
            FROM IFUserPaybackDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')';
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function daypayback_sub($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        if($where['dateId']){
            $date = substr($where['dateId'],0 ,4).'-'.substr($where['dateId'],4 ,2).'-'.substr($where['dateId'],6 ,2);
        } else{
            $date = date('Y-m-d', time());
        }
        $sql = "select 
b.debitId,b.releaseLoanTime,b.paybackDayTime,
case 
	when b.status in (2,6) then 'Auditing' 
	when b.status = 3 then 'Paybacked'
    when b.status = 4 then 'Overdue'
    when b.status = 1 and b.paybackDayTime > date_add('".$date."',interval 1 day) then 'Extend'
    when b.status = 1 and date_format(b.paybackDayTime, '%Y-%m-%d') = '".$date."' then 'Unpayback'
    when b.status = -2 then '申请还款未通过'
end status,
(select count(1) from IFUserPayBackDebitRecord c where c.debitId = b.debitId and c.type= 2) extendTimes,
(select statustime from IFUserPayBackDebitRecord c where c.debitId = b.debitId and c.type= 2 order by id desc limit 1) lastExtendTime
from (
	select debitId from IFUserDebitRecord a 
	where a.releaseLoanTime >= date_add('".$date."',interval -7 day) 
		and a.releaseLoanTime < date_add('".$date."',interval -6 day) 
		and a.status in (-2,2,4,1,3,6)
	union all
	select debitId from IFUserPayBackDebitRecord a 
	where a.statusTime >= date_add('".$date."',interval -7 day) 
		and a.statusTime < date_add('".$date."',interval -6 day) 
		and a.status = 1
		and a.type = 2
		and a.money > 0) as tab,IFUserDebitRecord b
where tab.debitId = b.debitId
    order by tab.DebitId desc ";
        $sql .= '  limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = "select count(*) s from (
	select debitId from IFUserDebitRecord a 
	where a.releaseLoanTime >= date_add('".$date."',interval -7 day) 
		and a.releaseLoanTime < date_add('".$date."',interval -6 day) 
		and a.status in (-2,2,4,1,3,6)
	union all
	select debitId from IFUserPayBackDebitRecord a 
	where a.statusTime >= date_add('".$date."',interval -7 day) 
		and a.statusTime < date_add('".$date."',interval -6 day) 
		and a.status = 1
		and a.type = 2
		and a.money > 0) as tab,IFUserDebitRecord b
where tab.debitId = b.debitId";
        $cres = $this->getRow($csql);
        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }


}