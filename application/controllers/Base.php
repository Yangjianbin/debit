<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Book_model');
//        $this->load->model('User_model');
//        $this->load->model('Score_Log_model');
//        $this->load->model('Download_Log_model');
//        $this->load->model('Redeem_model');
//        $this->load->model('Redeem_Log_model');
        $this->load->model('Category_model');
        $this->load->model('Base_model');
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
    private function successData($d = null){
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
        $this->load->view('admin/redeem');
    }

    /**
     * 分类管理
     */
    public function category(){
        $query = $this->Common_model->query('select count(1) as sum,c_id from t_book_category group by c_id ');
        $sums = array();
        foreach ($query->result() as $k=>$v){
            $sums[$v->c_id] = $v->sum;
        }
//        var_dump($sums);exit;
        $data = array('sums'=>json_encode($sums));
        $this->load->view('admin/category',$data);
    }

    public function delCategory(){
        $id = $this->input->get_post('id');
        if(!$id){
            $this->error();
        }
        $this->Common_model->trans_start();
        $this->Common_model->delete(array('id'=>$id),'t_category');
        $query = $this->Common_model->query('select c_id,b_id,category from t_book_category join t_book on t_book.id = t_book_category.b_id  where  c_id = '.$id );
//        var_dump($query->result());exit;
        $this->load->model('Book_model');
        foreach ($query->result() as $k=>$v) {
            $categoryStr = $v->category;
            if($categoryStr){
                $categoryArr = explode(',',$categoryStr);
                unset($categoryArr[array_search($id,$categoryArr)]);
                $category = implode(',',$categoryArr);
                $this->Book_model->update(array('id'=>$v->b_id),array('category'=>$category));
            }
        }
        $this->Common_model->query('delete from t_book_category where c_id = '.$id);
        $this->Common_model->trans_complete();
        $this->Category_model->cleanCache();
        $this->success();
    }

    /**
     * 添加分类
     */
    public function categoryAdd(){
        $pid = $this->input->post('pid');
        $name = $this->input->post('name');
        if (!$name) {
            $this->error();
        }
        $has = $this->Category_model->where(array('name' => $name))->get_one();
        if($has){
            $this->error('分类不能重复');
        }
        $data = array(
            'pid'=>$pid,
            'name'=>$name
        );
        $this->Category_model->insert($data);
        $this->Category_model->cleanCache();
        $this->success();
    }

    /**
     * 出版社管理
     */
    public function publisher(){
        $this->load->view('admin/publisher');
    }

    /**
     * 删除出版社
     */
    public function publisherDel(){
        $id = $this->input->post('id');
        if (!$id) {
            $this->error();
        }
        $this->Base_model->delete(array('id'=>$id));
        $this->success();
    }

    /**
     * ad管理
     */
    public function ad(){
        if($this->input->is_ajax_request()){
            $this->load->model('Ad_model');
            $res = $this->Ad_model->all();
            echo json_encode($res);
        }else{
            $this->load->view('admin/ad');
        }
    }

    public function adUpdate(){
        if($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            if (!$id) {
                $this->error();
            }
            $image = $this->input->post('image');
            $link = $this->input->post('link');
            $desc = $this->input->post('desc');
            $data = array(
                'image'=>$image,
                'desc'=>$desc,
                'link'=>$link
            );
            $this->load->model('Ad_model');
            $this->Ad_model->update(array('id'=>$id),$data);
            $this->Ad_model->cleanCache();
            $this->success();
        }
        $this->error();

    }


}
