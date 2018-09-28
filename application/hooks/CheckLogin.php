<?php

class CheckLogin
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**     * 权限认证     */
    public function check()
    {
        //访问管理后台
        if (preg_match("/ucenter|vadmin|admin|base|export|auth/i", uri_string())) {
            // no login
            if (!$this->CI->session->islogin) {
                redirect('login');
                return;
            }
            // no auth
            /*if(!$this->CI->session->isadmin){
                redirect('login');
                return;
            }*/
        }
        //访问用户中心
        if (preg_match('/ucenter/i', uri_string())) {
//            if (!$this->CI->session->islogin) {
//                redirect('login/wx');
//                return;
//            }
//            //如果是管理员
//            if ($this->CI->session->isadmin) {
//                redirect('admin');
//            }
        }
    }
}