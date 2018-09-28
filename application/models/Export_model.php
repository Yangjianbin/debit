<?php

class Export_model extends Common_model{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 放款报表
     */
    public function export1()
    {
        $startTime = $this->input->get_post('startTime') ? $this->input->get_post('startTime') : date('Y-m-d',time());
        //$endTime = $this->input->get_post('endTime') ? $this->input->get_post('endTime') : '2999-00-00';
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime($startTime)));
        $sql="select a.debitId,b.ContactName,date_format(releaseLoanTime,'%Y-%m-%d') releaseLoanTime,round(fee,0) fee, round(actualMoney,0) actualMoney
                    from IFUserDebitRecord a,IFUserBankInfo b
                    where a.releaseLoanTime >= '$startTime' and a.releaseLoanTime < date_add('$startTime', interval 1 day) 
                    and a.userId = b.userId and a.status in (-2,1,2,3,4,6);";
        $query = $this->query($sql);
        $res = $query->result();
//        $csql = "select count(*)
//                    from IFUserDebitRecord a,IFUserBankInfo b
//                    where a.releaseLoanTime >= $startTime and a.releaseLoanTime < date_add($startTime, interval 1 day)
//                    and a.userId = b.userId and a.status in (-2,1,2,3,4,6);";
//        $cres = $this->getRow($csql);
        return array('data' => $res);
    }

    /**
     * 延期的报表
     */
    public function export2()
    {
        $startTime = $this->input->get_post('startTime') ? $this->input->get_post('startTime') : date('Y-m-d',time());
//        $endTime = $this->input->get_post('endTime') ? $this->input->get_post('endTime') : '2999-00-00';;
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime($startTime)));
        $sql="select a.debitId,b.ContactName,date_format(releaseLoanTime,'%Y-%m-%d') releaseLoanTime,round(fee,0) fee1, round(actualMoney,0) actualMoney
                        ,round(fee,0) fee2,alreadyReturnInterest, returnInterest, alreadyReturnMoney,(alreadyReturnInterest + returnInterest + alreadyReturnMoney) total,userPaybackTime
                    from IFUserDebitRecord a,IFUserBankInfo b,IFUserPayBackDebitRecord c
                    where c.statusTime >= '$startTime' and c.statusTime < date_add('$startTime', interval 1 day) 
                    and a.debitId = c.DebitId and c.Status = 1 and c.type = 2
                    and a.userId = b.userId and a.status in (-2,1,2,3,4,6) and (alreadyReturnInterest + returnInterest + alreadyReturnMoney) > 0";
        $query = $this->query($sql);
        $res = $query->result();
//        $csql = "select count(*) s from IFUserDebitRecord a,IFUsers b,IFUserPayBackDebitRecord c
//where a.statusTime >= '$startTime' and a.statusTime < '$endTime'
//and a.debitId = c.DebitId and c.Status = 0 and c.type = 2
//and a.userId = b.userId and a.status in (-2,1,2,3,4,6);";
//        $cres = $this->getRow($csql);
        return array('data' => $res);
    }

    /**
     * 回款记录
     */
    public function export3()
    {
        $startTime = $this->input->get_post('startTime') ? $this->input->get_post('startTime') : date('Y-m-d',time());
//        $endTime = $this->input->get_post('endTime') ? $this->input->get_post('endTime') : '2999-00-00';;
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime($startTime)));
        $sql="select a.debitId,b.ContactName,date_format(releaseLoanTime,'%Y-%m-%d') releaseLoanTime,round(fee,0) fee1, round(actualMoney,0) actualMoney
                        ,round(fee,0) fee2,alreadyReturnInterest, returnInterest, alreadyReturnMoney,(alreadyReturnInterest + returnInterest + alreadyReturnMoney) total,userPaybackTime
                    from IFUserDebitRecord a,IFUserBankInfo b,IFUserPayBackDebitRecord c
                    where c.statusTime >= '$startTime' and c.statusTime < date_add('$startTime', interval 1 day) 
                    and a.debitId = c.DebitId and c.Status = 1 and c.type = 1
                    and a.userId = b.userId and a.status in (-2,1,2,3,4,6) and (alreadyReturnInterest + returnInterest + alreadyReturnMoney) > 0";
        $query = $this->query($sql);
        $res = $query->result();
//        $csql = "select count(*) s from IFUserDebitRecord a,IFUsers b,IFUserPayBackDebitRecord c
//where a.statusTime >= '$startTime' and a.statusTime < '$endTime'
//and a.debitId = c.DebitId and c.Status = 0 and c.type = 1
//and a.userId = b.userId and a.status in (-2,1,2,3,4,6);";
//        $cres = $this->getRow($csql);
        return array('data' => $res);
    }

    /**
     *坏帐记录
     */
    public function export4()
    {
        $startTime = $this->input->get_post('startTime') ? $this->input->get_post('startTime') : date('Y-m-d',time());
//        $endTime = $this->input->get_post('endTime') ? $this->input->get_post('endTime') : '2999-00-00';;
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime($startTime)));
        $sql="select a.debitId,b.ContactName,date_format(releaseLoanTime,'%Y-%m-%d') releaseLoanTime,round(fee,0) fee, round(actualMoney,0) actualMoney, round(overdueMoney,0) overdueMoney
                    from IFUserDebitRecord a,IFUserBankInfo b
                    where a.releaseLoanTime >= '$startTime' and a.releaseLoanTime < date_add('$startTime', interval 1 day) 
                    and a.userId = b.userId and a.status = 4 and a.overdueDay > 3";
        $query = $this->query($sql);
        $res = $query->result();
//        $csql = "select count(*) s from IFUserDebitRecord a,IFUsers b  where a.status = 4 and a.userId = b.userId and a.overdueDay > 3
//and a.payBackDayTime >= '$startTime' and a.payBackDayTime < '$endTime'";
//        $cres = $this->getRow($csql);
        return array('data' => $res);
    }


}