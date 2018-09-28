<?php

class Book_model extends Common_model{

    var $table = 't_book';

    public function __construct(){
        parent::__construct();
    }

    public function syncBook(){
        $this->trans_start();
        $this->db->where(array('id > '=>140));
        $this->db->limit(10000);
        $this->db->from($this->table);
        $query = $this->db->get();
        $result = $query->result();
        $this->load->model('Category_model');
        $categoryCache = $this->Category_model->getAllCategoryByCache();
        foreach ($result as $k => $v) {
            if(preg_match('/^\d/',$v->category)){
                continue;
            }
            $arr = explode(',',$v->category);
           // var_dump($arr);exit;
            $category = array();
            // $arr --->  [分类1,分类2]
            foreach ($arr as $kk=>$vv){
                if(!$vv){
                    continue;
                }
                $name = trim($vv);
                foreach ($categoryCache as $item =>$value){
                    if($value['name'] == $name){
                        array_push($category,$value['id']);
                        $insert = array('b_id'=>$v->id,'c_id'=>$value['id']);
                        $this->db->insert('t_book_category',$insert);
                        break;
                    }
                }
            }
            if (!empty($category)) {
                $categoryIds = implode(',',$category);
                $this->db->where(array('id'=>$v->id));
                $this->db->update($this->table,array('category'=>$categoryIds));
            }
        }
        $this->trans_complete();

    }

    public function allManage($where = array(), $q = null){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 10;
        $orderBy = array();
        $order = $this->input->get_post('order');
        if($order){
            $orderBy[$order] = 'desc';
        }
        $orderBy['create_time'] = 'desc';
        $like = array();
        if($q){
            if(preg_match('/\d+/',$q)){
                $like['isbn'] = $q;
            }else{
                $like['name'] = $q;
            }
        }
        $category = $this->input->get_post('category');
        if($category){
            $where['t_book_category.c_id'] = $category;
            $this->db->select('distinct(t_book.id), t_book.name,t_book.cover, t_book.publisher,t_book.category,t_book.isbn,t_book.publish_time');
            $this->db->or_like($like);
            $this->db->where($where);

            foreach ($orderBy as $k => $v) {
                $this->db->order_by($k, $v);
            }
            $this->db->from('t_book');
            $this->db->join('t_book_category','t_book.id = t_book_category.b_id');
            $db = clone($this->db);
            $count = $this->db->count_all_results();
//            var_dump($this->db->last_query());exit;
            $this->db = $db;
            $this->db->limit($pageSize,$start);
            $query = $this->db->get();
            $data = $query->result_array();
//            var_dump($this->db->last_query());exit;
//            'select t_b.* from t_book_category t_bc where t_bc.c_id =  ' . $category . ' join t_book t_b on t_b.id = t_bc.b_id';
        }else{
            $data = $this->where($where)->orderby($orderBy)->or_like($like)->limit($pageSize,$start)->select();
            $count = $this->where($where)->or_like($like)->count();

        }
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function search($where = array(),$q = null){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 10;
        $orderBy = array();
        $order = $this->input->get_post('order');
        if($order){
            $orderBy[$order] = 'desc';
        }
        $orderBy['create_time'] = 'desc';
        $like = array();
        if($q){
            $like['name'] = $q;
            $like['isbn'] = $q;
        }
        $category = $this->input->get_post('category');
        if($category){
            $where['t_book_category.c_id'] = $category;
            $this->db->distinct();
            $this->db->select('t_book.*');
            $this->db->like($like);
            $this->db->where($where);
            $this->db->limit($pageSize,$start);
            foreach ($orderBy as $k => $v) {
                $this->db->order_by($k, $v);
            }
            $this->db->from('t_book');
            $this->db->join('t_book_category','t_book.id = t_book_category.b_id');
//            $count = $this->db->count_all_results();
            $count = 0;
            $query = $this->db->get();
            $data = $query->result_array();
//            var_dump($this->db->last_query());exit;
//            'select t_b.* from t_book_category t_bc where t_bc.c_id =  ' . $category . ' join t_book t_b on t_b.id = t_bc.b_id';
        }else{
            $data = $this->where($where)->orderby($orderBy)->or_like($like)->limit($pageSize,$start)->select();
//            var_dump($this->db->last_query());exit;
            $count = $this->where($where)->or_like($like)->count();

        }
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function all($where = array(),$q = null){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 10;
        $orderBy = array();
        $order = $this->input->get_post('order');
        if($order){
            $orderBy[$order] = 'desc';
        }
        $orderBy['create_time'] = 'desc';
        $like = array();
        if($q){
            $like['name'] = $q;
            $like['isbn'] = $q;
        }
        $category = $this->input->get_post('category');
        if($category){
            $where['t_book_category.c_id'] = $category;
            $this->db->distinct();
            $this->db->select('t_book.*');
            $this->db->like($like);
            $this->db->where($where);
            $this->db->limit($pageSize,$start);
            foreach ($orderBy as $k => $v) {
                $this->db->order_by($k, $v);
            }
            $this->db->from('t_book');
            $this->db->join('t_book_category','t_book.id = t_book_category.b_id');
//            $count = $this->db->count_all_results();
            $count = 0;
            $query = $this->db->get();
            $data = $query->result_array();
//            var_dump($this->db->last_query());exit;
//            'select t_b.* from t_book_category t_bc where t_bc.c_id =  ' . $category . ' join t_book t_b on t_b.id = t_bc.b_id';
        }else{
            $data = $this->where($where)->orderby($orderBy)->limit($pageSize,$start)->select();
            $count = $this->where($where)->count();

        }
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }

    public function allLike($where = array(),$q = null){
        $start = $this->input->get_post('start') ? $this->input->get_post('start') : 0;
        $pageSize = $this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 10;
        $orderBy = array();
        $order = $this->input->get_post('order');
        if($order){
            $orderBy[$order] = 'desc';
        }
        $orderBy['create_time'] = 'desc';
        $like = array();
        if($q){
            $like['name'] = $q;
            $like['isbn'] = $q;
        }
        $category = $this->input->get_post('category');
        if($category){
            $where['t_book_category.c_id'] = $category;
            $this->db->select('t_book.*');
            $this->db->or_like($like);
            $this->db->where($where);
            $this->db->limit($pageSize,$start);
            foreach ($orderBy as $k => $v) {
                $this->db->order_by($k, $v);
            }
            $this->db->from('t_book');
            $this->db->join('t_book_category','t_book.id = t_book_category.b_id');
//            $count = $this->db->count_all_results();
            $count = 0;
            $query = $this->db->get();
            $data = $query->result();
//            var_dump($this->db->last_query());exit;
//            'select t_b.* from t_book_category t_bc where t_bc.c_id =  ' . $category . ' join t_book t_b on t_b.id = t_bc.b_id';
        }else{
            //$this->db->like($like);
//            $data = $this->where($where)->orderby($orderBy)->limit($pageSize,$start)->select();
//            var_dump($this->db->last_query());exit;
//
//            $count = $this->where($where)->count();

            $this->db->from('t_book');
            $this->db->or_like($like);
            $this->db->where($where);
            foreach ($orderBy as $k => $v) {
                $this->db->order_by($k, $v);
            }
            $this->db->limit($pageSize, $start);
            $query = $this->db->get();
            $data = $query->result();
            $count = 0;

//            var_dump($this->db->last_query());exit;

        }
        return array('data'=>$data,'recordsTotal'=>$count,'recordsFiltered'=>$count);
    }


}