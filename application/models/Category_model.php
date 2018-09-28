<?php

class Category_model extends Common_model{

    var $table = 't_category';

    public function __construct(){
        parent::__construct();
        $this->load->driver('cache',array('adapter'=>'memcached','key_prefix'=>'yd_'));
    }

    /**
     * 获取所有分类，缓存中没有就数据库取
     */
    public function getAllCategoryByCache(){
        $category = $this->cache->get('category');
        if(!$category){
            $category = $this->select();
            $this->cache->save('category', $category, 24 * 3600);
        }
        return $category;
    }

    public function cleanCache(){
        $this->cache->delete('category');
        $this->cache->delete('left_category');
    }

    public function getLeftCategoryByCache(){
        $first = $this->cache->get('left_category');
        if(!$first){
            $category = $this->select();
            $first = array();
            foreach ($category as $k => $v) {
                if(!$v['pid']){//一级栏目
                    $first[] = $v;
                }
            }
            foreach ($first as $k=>$v){
                $child = array();
                foreach ($category as $kk=>$vv){
                    if($v['id'] == $vv['pid']){
                        $child[] = $vv;
                    }
                }
                $first[$k]['child'] = $child;
            }
            $this->cache->save('left_category', $first, 24 * 3600);
        }
        return $first;
    }


}