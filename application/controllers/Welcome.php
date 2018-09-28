<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
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

    public function session(){
//        var_dump($this->session);
        $this->load->model('User_model');
        $u = $this->User_model->get_one(array('openid'=>'oI2Hsv_GkCUlLnp069Jy2iSq4D5k'));
        //$u->nickname = json_decode($u->nickname);
        var_dump($u);

    }

    public function clean(){
        $this->load->driver('cache',array('adapter'=>'memcached','key_prefix'=>'yd_'));
        $this->cache->clean();
    }

    public function syncBook(){
//        if(preg_match('/^\d/','fdg45454gdgr')){
//            echo 'have';
//        }else{
//            echo 'no';
//        }exit;
//        $this->load->model('Book_model');
//        $this->Book_model->syncBook();
    }

    public function syncBook2(){
        exit;
        setlocale(LC_ALL, 'zh_CN');
        $this->load->model('Book_model');
        header('Content-type:text/html; charset=utf-8');
        $handle = fopen('./book.csv','r');
//        echo mb_detect_encoding($handle);exit;
        $i = 0;
        while ($data = fgetcsv($handle,1000,",")){
            if($i>99){
                $url = iconv('gb2312','utf-8',$data[2]);
                $one = $this->Book_model->get_one(array('cover'=>$url));
                if($one){
                    $down = iconv('gb2312','utf-8',$data[3]);
                    if($down){
                        $arr = preg_split('/(链接(:|：)\s*)|( 密码(:|：)\s*)/',$down);
                        $str = '';
                        if(isset($arr[1])){
                            $str .= ($arr[1].'@');
                        }
                        if(isset($arr[2])){
                            $str .= $arr[2];
                        }
                        $download_links = json_encode(array($str));
                        $this->Book_model->update(array('id'=>$one->id),array('download_links'=>$download_links));
                    }
                }

            }
//            echo iconv('gb2312','utf-8',$data[0]);
//            echo iconv('gb2312','utf-8',$data[1]);
//            echo iconv('gb2312','utf-8',$data[2]);
//            echo iconv('gb2312','utf-8',$data[3]);
            if($i++ >= 5000){
                break;
            }
        }

    }

    public function phpinfo(){
        phpinfo();
    }

    public function index()
    {
	redirect(site_url('admin'));
	/*
        if(is_mobile_request()){
            redirect(site_url('welcome/h5'));
        }
        //查询分类
        $this->load->model('Category_model');
        $category = $this->Category_model->getLeftCategoryByCache();
        $data = array('category'=>$category);
        $data['title'] = '医典114-最全医学书籍资源-免费医学电子书下载';
        $data['keywords'] = '医学电子书下载，免费电子书下载，医学书籍下载，医学书籍购买';
        $data['description'] = '医典114汇集了最全的医学书籍资源，收集了网上最新、最全的医学电子书下载资源、提供最新的医学电子书免费下载服务，以及低折扣的医学书籍购买服务';
        $this->load->view('index',$data);
    */
    }

    public function h5(){
        //查询分类
        $this->load->model('Category_model');
        $category = $this->Category_model->getLeftCategoryByCache();
        //查询最新前4篇
        $this->load->model('Book_model');
        $book = $this->Book_model->where(array('status'=>0))->limit(4)->orderBy(array('create_time'=>'desc'))->select();
//        var_dump($book);exit;
        $data = array('category'=>$category,'book'=>$book);
        $this->load->view('h5/index',$data);
    }

    public function h5_list(){
        if($this->input->is_ajax_request()){
            $this->load->model('Book_model');
            $books = $this->Book_model->all(array('status'=>0));
            $this->successData($books);
        }

        $this->load->view('h5/list',array('name'=>$this->input->get_post('name')));
    }

    public function h5_search(){
        $key = $this->input->get_post('key');
        if($this->input->is_ajax_request()){
            $this->load->model('Book_model');
            $books = $this->Book_model->search(array('status'=>0),$key);
            $this->successData($books);
        }
        $this->load->view('h5/search',array('name'=>$key));
    }

    public function h5_detail($id){
        $this->load->model('Book_model');
        $this->load->model('Category_model');
        $one = $this->Book_model->where(array('id'=>$id))->get_one();
        //var_dump($one);exit;
        $categoryCache = $this->Category_model->getAllCategoryByCache();
        $categoryStr = '';
        foreach (explode(',',$one->category) as $k => $v) {
            foreach ($categoryCache as $kk => $vv) {
                if ($vv['id'] == $v) {
                    $categoryStr .= $vv['name'] . ',';
                }
            }
        }
        $one->categoryStr = $categoryStr;
        $data = array('one'=>$one);
        $this->load->view('h5/detail',$data);
    }

    public function search($q = null){
        $q = urldecode($q);
        $data = array('q'=>$q);
        $this->load->view('search',$data);
    }

    public function book(){
        $this->load->model('Book_model');
        $res = $this->Book_model->all(array('status'=>0));
        //删除下载地址
        if(!empty($res) && !empty($res['data'])){
            foreach ($res['data'] as $k => $v) {
                unset($v['download_links']);
                $res['data'][$k] = $v;
            }
        }
        $this->successData($res);
    }

    public function bookLike(){
        $q = $this->input->get_post('q');
        $this->load->model('Book_model');
        $res = $this->Book_model->allLike(array(),$q);
        $this->successData($res);
    }

    public function detail($id){
        $this->load->model('Book_model');
        $one = $this->Book_model->where(array('id'=>$id))->get_one();
        if(!$one){
            redirect(base_url());
        }
        if($one->status == 1){
            redirect(base_url());
        }
        $this->load->model('Category_model');
        $categoryCache = $this->Category_model->getAllCategoryByCache();
        $categoryStr = '';
        foreach (explode(',',$one->category) as $k => $v) {
            foreach ($categoryCache as $kk => $vv) {
                if ($vv['id'] == $v) {
                    $categoryStr .= $vv['name'] . ',';
                }
            }
        }
        $one->categoryStr = $categoryStr;

        //ad
        $this->load->model('Ad_model');
        $ad = $this->Ad_model->allByCache();
        $data = array('data'=>json_encode($one),'ad'=>$ad);
        if(!$one->download_links){
            $data['hasdownlinks'] = false;
        }
        //登录用户判断 是否下载过
        if($this->session->islogin){
            $user_id = $this->session->user->id;
            $this->load->model('Download_Log_model');
            $hasdown = $this->Download_Log_model->get_one(array('book_id'=>$id, 'user_id' => $user_id));
            //下载过，直接查看
            if ($hasdown) {
                $data['hasdown'] = true;
            }else{
                unset($one->download_links);
            }
        }else{
            unset($one->download_links);
        }
        $data['title'] = '《'.$one->name.'》'.'-最全医学书籍资源-免费医学电子书下载-医典114';
        $data['keywords'] = '《'.$one->name.'》电子书下载，《'.$one->name.'》电子书购买，医学电子书下载，免费电子书下载，医学书籍下载，医学书籍购买';
        $data['description'] = '医典114为您提供《'.$one->name.'》电子书下载和图书购买，医典114汇集了最全的医学书籍资源，收集了网上最新、最全的医学电子书下载资源、提供最新的医学电子书免费下载服务，以及低折扣的医学书籍购买服务';
        $this->load->view('detail',$data);
    }

    public function api1(){
        header('Content-type: application/json');
        echo 'ed":false},{"code":"ZCRJJW","id":896,":"奇幻","parentId":1256,"parsed":false}],"code":"MH","id":1256,"leaf":false,"name":"漫画","parentId":1207,"parsed":false}]}}';
    }

    public function api2(){
        header('Content-type: application/json');
        echo '{"systemDate":1498320328369,"status":{"code":0},it":16,"promotion"","title":"灵魂只能独行"}],"saleId":1900551927,"type":0}]}}';
    }

    /**
     * 获取七牛token
     */
    public function uptoken()
    {
        echo file_get_contents('http://haitao3.isudoo.com/index.php/admin2/base/uptoken');
    }

    /**
     * 分类select
     */
    public function category(){
        $this->load->model('Category_model');
        $res = $this->Category_model->orderby(array())->select();
        $first = array();
        $arr = array();
        foreach ($res as $k => $v) {
            if(!$v['pid']){
                $v['open'] = true;
                $first[] = $v;
            }
        }
        foreach ($first as $k => $v) {
            $arr[] = $v;
            foreach ($res as $kk => $vv) {
                if($vv['pid'] == $v['id']){
                    $vv['name'] = '--' . $vv['name'];
                    $arr[] = $vv;
                }
            }
        }
        $this->successData($arr);
    }

    public function publisher(){
        $this->load->model('Base_model');
        $res = $this->Base_model->orderby(array())->select();
        $this->successData($res);
    }

    public function test()
    {
        //$this->load->driver('cache',array('adapter'=>'memcached','key_prefix'=>'video_'));
        //$this->cache->save('name','yangjianbin',6000);
        //echo $this->cache->get('name');
        $this->load->model('Category_model');
        $res = $this->Category_model->getAllCategoryByCache();
        var_dump($res);

    }

    public function _output($output)
    {
        //echo 'out:' . $this->input->is_ajax_request();
        echo $output;
    }

    public function need()
    {
        echo 'need';
    }
}
