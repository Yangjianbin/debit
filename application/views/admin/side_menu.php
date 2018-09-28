<?php
//var_dump(current_url());exit; //http://debit.isudoo.com/index.php/admin/repayment
//exit;
$menu = $this->session->menu;
//var_dump($menu);exit;
/*$menu = array(
    (object)array('name' => '贷款管理',
        'child' => (object)array(
            (object)array('name' => '贷款审核', 'url' => site_url('admin/loan'),'role'=>array(0,1)),
            (object)array('name' => '放款管理', 'url' => site_url('admin/advances'),'role'=>array(0,2)),
            (object)array('name' => '还款管理', 'url' => site_url('admin/repayment'),'role'=>array(0,2)),
            (object)array('name' => '逾期管理', 'url' => site_url('admin/overdue'),'role'=>array(0,2))
        )
    )
);*/
?>
<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
        <?php if ($this->session->islogin && count($menu) > 0): ?>
            <?php foreach ($menu as $k => $v): ?>
                <?php if($v['pid'] || $v['pid'] != 0):?>
                <?php continue;?>
                <?php endif;?>
                <dl class="menu-article">
                    <dt>
                        <i class="Hui-iconfont">&#xe616;</i><?= $v['title'] ?><i class="Hui-iconfont menu_dropdown-arrow">
                            &#xe6d5;</i>
                    </dt>
                    <dd style="">
                        <ul>
                            <?php foreach ($menu as $kk => $vv): ?>
                                <?php if($vv['pid'] == $v['id']):?>
                                <li class="<?= site_url($vv['url']) == current_url() ? 'current' : '' ?>">
                                    <a href="<?= site_url($vv['url']) ?>"><?= $vv['title'] ?></a>
                                </li>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </ul>
                    </dd>
                </dl>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</aside>
<div class="dislpayArrow hidden-xs">
    <a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
