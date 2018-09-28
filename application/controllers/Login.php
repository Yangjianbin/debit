<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('User_model');
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

    /**
     * 后台登录
     */
    public function index()
    {
        $ip = $this->input->ip_address();
        $ips = array(
            '183.157.67.184','103.76.15.130'
        );
        if (!in_array($ip, $ips)) {
           // exit('IP NOT PERMISSION');
        }
        if ($this->input->is_ajax_request()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $obj = $this->Admin_model->where(array('username' => $username))->get_one();
            if ($obj) {
                if ($obj->username == $username && $obj->password == md5($password)) {
                    //save session
                    $this->session->islogin = true;
                    $this->session->admin = $obj;
                    $this->session->role = $this->Admin_model->getRole($obj->id);
                    $this->session->menu = $this->Admin_model->getMenu($obj->id);
                    $this->success();
                }
            }
            $this->error();
        } else {
            if ($this->session->islogin) {
                redirect('admin');
            }
            $this->load->view('admin/login');
        }
    }

    /**
     * 微信登录
     */
    public function wx(){

        $code = $this->input->get('code');
        $appid = $this->config->item('appid');
        if (!$code) {
            redirect('https://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.urlencode(current_url()).'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect');
        }else{
            $access_token = $this->getAccessToken();
            if (!$access_token) {
                $appsecret = $this->config->item('appsecret');

                //用token获取access_token和openid
                $res = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code');
                $res = json_decode($res);
                if($res->access_token){
                    //放到缓存
                    $access_token = $res->access_token;
                    //$this->setAccessToken($res->access_token);
                    $openid = $res->openid;
                    // 根据openid access_token查询个人信息
                    /*"openid":"OPENID","nickname":"NICKNAME","sex":1,"headimgurl": "http://wx.qlogo.",*/
                    $userinfo = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid);
                    $userinfo = json_decode($userinfo);
                    if($userinfo){
                        //根据openid查询本地数据库是否注册，没有则注册，有则更新
                        $user = $this->User_model->where(array('openid' => $openid))->get_one();
                        $now = date('Y-m-d H:i:s',time());
                        if(!$user){
                            $data = array(
                                'nickname'=>json_encode($userinfo->nickname),
                                'openid'=>$openid,
                                'headimgurl'=>$userinfo->headimgurl,
                                'create_time'=>$now,
                                'last_login_time'=>$now
                            );
                            $this->User_model->insert($data);
                        }else{
                            $data = array(
                                'headimgurl'=>$userinfo->headimgurl,
                                'nickname'=>json_encode($userinfo->nickname)
                            );
                            $this->User_model->where(array('openid'=>$openid))->update(array('id'=>$user->id),$data);
                        }
                        //构建session，定位到首页
                        $this->session->islogin = true;
                        $u = $this->User_model->get_one(array('openid'=>$openid));
                        $u->nickname = json_decode($u->nickname);
                        $this->session->user = $u;
                        redirect(base_url());
                    }
                }
            }
        }
        echo 'wx login page';
    }

    private function getAccessToken(){
        $this->load->driver('cache',array('adapter'=>'memcached','key_prefix'=>'yd_'));
        return $this->cache->get('access_token');
    }

    /**
     * 微信登录test
     */
    public function wxtest(){
        $this->session->islogin = true;
        $this->session->user = (object)array('nickname' => 'yangjianbin', 'id' => 1000004,'score'=>100);
    }

    public function changepwd()
    {
        if (!$this->session->isadmin) {
            $this->error();
        }
        if ($this->input->is_ajax_request()) {
            $newpassword = $this->input->post('newpassword');
            if (!$newpassword) {
                $this->error('新密码不为空');
            }
            $data = array('password' => md5($newpassword));
            $this->Admin_model->update(array('id' => $this->session->admin->id),$data);
            $this->success();
        }
        $this->error();
    }


    public function auth()
    {
        echo 'you have no auth';
    }

    public function test()
    {
        $this->session->isadmin = true;
        $this->session->user = (object)array('username' => 'test', 'id' => 1);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->success();
    }

}
