<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/aliyun-oss-php/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class Upload extends CI_Controller
{
    private $accessKeyId = "LTAIdmpYJJABLNHe";
    private $accessKeySecret = "Fxu4UuEBI5baDxM7rccz8a4NbxzEWL";
    private $endpoint = "http://oss-cn-hongkong.aliyuncs.com";
    private $bucket = 'debit';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('oss/alioss');
    }

    public function upload()
    {
        $tmpName = $_FILES['file']['tmp_name'];
        $ext = substr(strrchr($_FILES['file']['name'], '.'), 1);
        $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        $filePath = $tmpName;
        try{
            $res = $ossClient->uploadFile($this->bucket, time() . rand() . '.' . $ext ,$filePath);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        $url = $res['info']['url'];
        $this->successData(array('url'=>$url));
    }

    public function test1()
    {
        $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        $filePath = "/var/www/html/debit/static/img/search.png";
        try{
            $ossClient->uploadFile($this->bucket,'search.png' , $filePath);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    public function test()
    {




        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $listObjectInfo = $ossClient->listObjects('debit');
            $objectList = $listObjectInfo->getObjectList();
            if (!empty($objectList)) {
                foreach ($objectList as $objectInfo) {
                    print($objectInfo->getKey() . "\t" . $objectInfo->getSize() . "\t" . $objectInfo->getLastModified() . "\n");
                }
            }
        } catch (OssException $e) {
            print $e->getMessage();
        }

/*
        $options = array(
            'bucket'=>'debit',
            'object'=>'test',
            'directory'=>'/var/www/html/debit/book.csv'
        );
        $res = $this->alioss->list_object('debit');
        echo json_encode($res);*/

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


}
