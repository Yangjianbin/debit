<?php

class DebitDaily_model extends Common_model
{

    var $table = 'IFDebitDailyReport';

    public function __construct()
    {
        parent::__construct();
    }


    public function allDailyReport($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->orderby(array('dateId' => 'desc'))->limit(10, $start)->select();
        $count = $this->where($where)->count();
        return array('data' => $data, 'recordsTotal' => $count, 'recordsFiltered' => $count);
    }

    public function allWeeklyReport($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\') week,
                dateId,
                sum(toDayDebitMoney) toDayDebitMoneys,
                sum(todayShouldRepayMoney) todayShouldRepayMoneys,
                sum(toDayRepayMoney) toDayRepayMoneys,
                sum(todayExtendMoney) todayExtendMoneys,
                sum(toDayOverdueMoney) toDayOverdueMoneys,
                sum(todayOverdueInterest) todayOverdueInterests,
                sum(todayExtendInterest) todayExtendInterests,
                sum(todayTotalIncome) todayTotalIncomes,
                sum(returnTotalMoney) returnTotalMoneys,
                sum(returnDebitMoney) returnDebitMoneys,
                sum(returnInterest) returnInterests
            FROM
                IFDebitDailyReport
            GROUP BY DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')';
        $sql .= ' order by dateId desc  limit '.$start. ', 10';
        $res = $this->getRows($sql);
        $csql = 'SELECT count(*) s from ( SELECT
                1
            FROM IFDebitDailyReport GROUP by DATE_FORMAT(str_to_date(dateId, \'%Y%m%d\'),\'%Y_%u\')) a';
        $cres = $this->getRow($csql);

        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function allMonthlyReport($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                sum(toDayDebitMoney) toDayDebitMoneys,
                sum(todayShouldRepayMoney) todayShouldRepayMoneys,
                sum(toDayRepayMoney) toDayRepayMoneys,
                sum(todayExtendMoney) todayExtendMoneys,
                sum(toDayOverdueMoney) toDayOverdueMoneys,
                sum(todayOverdueInterest) todayOverdueInterests,
                sum(todayExtendInterest) todayExtendInterests,
                sum(todayTotalIncome) todayTotalIncomes,
                sum(returnTotalMoney) returnTotalMoneys,
                sum(returnDebitMoney) returnDebitMoneys,
                sum(returnInterest) returnInterests,
                LEFT (dateId, 6) m
            FROM
                IFDebitDailyReport
            GROUP BY
        m';
        $sql .= ' order by m desc  limit '.$start. ', 10';
        $res = $this->getRows($sql);

        $csql = 'SELECT
                count(*) s
            FROM
                (
                    SELECT
                        id
                    FROM
                        IFDebitDailyReport
                    GROUP BY
                        LEFT (dateId, 6)
                ) tmp';

        $cres = $this->getRow($csql);


        return array('data' => $res, 'recordsTotal' => $cres['s'], 'recordsFiltered' => $cres['s']);
    }

    public function allReport($where = array())
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT
                sum(toDayDebitMoney) toDayDebitMoneys,
                sum(todayShouldRepayMoney) todayShouldRepayMoneys,
                sum(toDayRepayMoney) toDayRepayMoneys,
                sum(todayExtendMoney) todayExtendMoneys,
                sum(toDayOverdueMoney) toDayOverdueMoneys,
                sum(todayOverdueInterest) todayOverdueInterests,
                sum(todayExtendInterest) todayExtendInterests,
                sum(todayTotalIncome) todayTotalIncomes,
                sum(returnTotalMoney) returnTotalMoneys,
                sum(returnDebitMoney) returnDebitMoneys,
                sum(returnInterest) returnInterests
            FROM
                IFDebitDailyReport';
        $res = $this->getRows($sql);
        return array('data' => $res, 'recordsTotal' => count($res), 'recordsFiltered' => count($res));
    }


}
