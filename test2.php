<?php
exit;
try {
    $dbh = new PDO('mysql:host=116.62.12.33;dbname=yuetao', 'root', 'yjb123456');
    $dbh2 = new PDO('mysql:host=localhost;dbname=ytsmall', 'root', '123456');
    foreach ($dbh->query('SELECT unionid,balance,coin FROM xx_member_wechat a JOIN xx_member b ON a.member = b.id  WHERE unionid IS NOT NULL') as $row) {
        $unionid = $row['unionid'];
        $coin = intval($row['coin']);
        $b = $row['balance'];
        $sql = 'update hjmall_user set integral=integral+'.$coin .' , price=price+'.$b.' where wechat_union_id =\''.$unionid . '\'';
//        var_dump($sql);exit;
        foreach ($dbh2->query($sql) as $r2){
            var_dump($r2);
        }

        echo 'over';
    }



    $dbh = null;

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>