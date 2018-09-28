<?php

class User_model extends Common_model{

    var $table = 'IFUsers';

    public function __construct(){
        parent::__construct();
    }

    public function all($where = array()){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $data = $this->where($where)->limit(10,$start)->select();
        $count = $this->where($where)->count();
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function export($where = array())
    {
        $sql = 'select 
b.fullName name,
a.userId UserId,
b.IdCard IDCard,
b.residentialAddress Address,
b.phone,
(select c.bankName from IFUserBankInfo c where c.bankId = a.BankId)  bankName,
(select c.BankCode from IFUserBankInfo c where c.bankId = a.BankId)  bankAccount,
debitId loanId,
(select c.Description from IFUserAduitDebitRecord c where c.DebitId = a.debitId order by c.id  limit 1) reson,
case a.Status when -2 then \'reject\' -2   when -1 then  \'reject -1\'  when 0 then \'requesting\' when 1 then \'pass and release\'  when 2 then \'request payback\' 
when 3 then \'already payback\' when 5 then \'pass but release\' when 4 then \'overdue\' when 6 then \'extend\'
 else \'other\' end status,
 (select c.auditTime from IFUserAduitDebitRecord c where c.DebitId = a.debitId and c.aduitType = 1 order by c.id limit 1) auditTime,
 (select count(distinct c.phone) from IFUserContactInfo c,IFUserContacts d where c.userId = d.userId and c.phone = d.phone and c.userId = a.UserId) yesNumber

 from IFUserDebitRecord a, IFUsers b where a.userId = b.userId order by a.DebitId desc limit 100;';
        $query = $this->query($sql);
        $data = $query->result();
        return array('data'=>$data,'recordsTotal'=>count($data),'recordsFiltered'=>count($data));;
    }


    public function blacklist()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT * from t_blacklist a JOIN IFUsers b ON a.user_id = b.UserId';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from t_blacklist a ';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;

        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function badlist()
    {
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $sql = 'SELECT *,c.fullName from t_bad a LEFT JOIN IFUserDebitRecord b ON a.debit_id = b.DebitId LEFT JOIN IFUsers c ON c.UserId = b.UserId';
        $sql .= ' limit ' . $start . ' , 10';
        $query = $this->query($sql);
        $data = $query->result();

        $sql2 = 'SELECT count(*) s from t_bad a ';
        $query2 = $this->query($sql2);
        $count = $query2->result()[0]->s;

        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function userSelect()
    {
        $sql = 'SELECT a.UserId,a.UserName,a.fullName,a.IdCard from IFUsers a';
        $query = $this->query($sql);
        $data = $query->result();
        return $data;
    }




}