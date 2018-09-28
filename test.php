<?php
exit;
try {
    $dbh = new PDO('mysql:host=116.62.12.33;dbname=yuetao', 'root', 'yjb123456');
    $yuetao = array();
    $yuetao2 = array();
    foreach ($dbh->query('SELECT unionid,member FROM xx_member_wechat WHERE unionid IS NOT NULL') as $row) {
        $yuetao[$row['unionid']] = $row['member'];
        $yuetao2[$row['member']] = $row['unionid'];
    }

    $dbh2 = new PDO('mysql:host=localhost;dbname=ytsmall', 'root', '123456');

    foreach ($yuetao as $k => $v) {
        foreach ($dbh->query('SELECT parent FROM xx_member WHERE id ='.$v) as $r){
            if (!$r['parent']) {
                break;
            }

            if (!$yuetao2[$r['parent']]) {
                break;
            }

            $punionid = $yuetao2[$r['parent']];

            $data = array(
                'child'=>$k,
                'parent'=>$punionid
            );

            $news = array();
            foreach ($dbh2->query('SELECT id,parent_id,wechat_union_id FROM hjmall_user WHERE wechat_union_id IN (\''.$data['child'].'\',\''.$data['parent'].'\')') as $r2){
                $news[] = $r2;
            }

            if (count($news) < 2) {
                break;
            }

            $childId = 0;
            $parentId = 0;
            foreach ($news as $k=>$v){
                if ($v['wechat_union_id'] == $data['child']) {
                    $childId = $v['id'];
                }
                if ($v['wechat_union_id'] == $data['parent']) {
                    $parentId = $v['id'];
                }
            }

            $updateSql = 'update hjmall_user  set parent_id = '.$parentId.' where id = '.$childId;
            var_dump($updateSql);
            echo "<br/>";
//            exit;
            foreach ($dbh2->query($updateSql) as $row) {
                var_dump($row);
                echo "over<br />";
            }


//            exit('999');


//            var_dump($k, $punionid);
//            exit;
        }

    }


//    $dbh2 = new PDO('mysql:host=localhost;dbname=ytsmall', 'root', '123456');
//    $yuetao = array();
//    foreach ($dbh2->query('SELECT * FROM hjmall_user WHERE unionid IS NOT NULL') as $row) {
//        $yuetao[$row['unionid']] = $row['member'];
////    print_r($row['unionid']);
//    }
//
//    var_dump($yuetao);


    $dbh = null;

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>