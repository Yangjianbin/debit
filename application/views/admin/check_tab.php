<style>
    .tabBar span{
        padding: 0 10px;
    }
</style>
<div id="tab-system" class="HuiTab" style="border:1px solid #eee;padding-bottom:15px;">
    <div class="tabBar cl">
        <span>User Info</span>
        <span>Certificate Info</span>
        <span>Work Info</span>
        <span>Contact Info</span>
        <span class="map">Location</span>
        <span>Bank Info</span>
        <span id="totalTime">Records (<?=count($item->records)?>)</span>
        <span>Audit Records</span>
<!--        --><?php //if(isset($show_contact) && $show_contact):?>
        <span>Contacts Address</span>
<!--        --><?php //endif;?>
        <span>Payback Record</span>
    </div>
    <div class="tabCon">
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Phone Number：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->UserName ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Full Name：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->fullName ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Education：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->education ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Intro：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->Intro ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">IdCard：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->IdCard ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Address：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->residentialAddress ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">ResidentialProvince：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->residentialProvince ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">ResidentialCity：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->residentialCity ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Age：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->age ?>" placeholder="">
            </div>
        </div>



    </div>
    <div class="tabCon">
        <div class="row cl">
            <?php foreach ($item->cert as $k => $v): ?>
                <label class="form-label col-xs-2 col-sm-2"><?= $v->CertificateType == 1 ? 'ID' : 'Selfie' ?>
                    ：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <a target="_blank" href="<?= $v->Url ?>"><img style="max-width: 200px;" src="<?= $v->Url ?>" alt=""/></a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">ID Card：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->IdCard ?>" placeholder="">
            </div>
        </div>
        <br>
    </div>
    <div class="tabCon">
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Work Title：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->typeOfWork ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Monthly Income：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->monthIncome ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Company Name：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->companyName ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Work Province：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->companyProvince ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Work Address：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->companyAddress ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Work Phone No. ：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->companyPhone ?>">
            </div>
        </div>
    </div>
    <div class="tabCon">
        <?php
        //Papa	 Ayah	Bapak	 bokap	 Babeh	Papi	Abi 	dad	daddy 	Father
        //Mama	ibu 	nyokap	emak	mami	mum	mommy	mother 	ema	umi	mamah	bunda
        $father = array('Papa','Ayah','Bapak','bokap','Babeh','Papi','Abi','dad','daddy','Father');
        $mother = array('Mama','ibu','nyokap','emak','mami','mum','mommy','mother','ema','umi','mamah','bunda');
//        var_dump($item->records);exit;
        ?>
        <?php foreach ($item->contact as $k => $v): ?>
            <div class="row cl">
                <label class="form-label col-xs-2 col-sm-2">Contact Type：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <input type="text" class="input-text" readonly value="<?= $this->config->item('contact')[$k]?>" placeholder="">
                </div>
                <label class="form-label col-xs-2 col-sm-2">Name：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <input type="text" class="input-text" readonly value="<?= $v->relationUserName ?>">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-2 col-sm-2">Phone：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <input type="text" class="input-text" readonly value="<?= $v->phone ?>" placeholder="">
                </div>

                <label class="form-label col-xs-2 col-sm-2">Has in the contacts：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <input type="text" class="input-text" readonly value="<?= in_array($v->phone, $item->contacts) ? 'YES('.$item->contactName[$v->phone].')' : 'NO'?>">
                </div>
            </div>
            <br>
        <?php endforeach; ?>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">similarly People In Contacts：</label>
            <div class="formControls col-xs-9 col-sm-9">
                <?php
                foreach ($item->contactName as $k=>$v){
                    foreach ($father as $value){
                        if($value == $v){
                            echo $v . ' : ' . $k . ';&nbsp;&nbsp;&nbsp;';
                        }
                    }
                    foreach ($mother as $value){
                        if($value == $v){
                            echo $v . ' : ' . $k . ';&nbsp;&nbsp;&nbsp;';
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3 col-sm-3">similarly People In Records：</label>
            <div class="formControls col-xs-9 col-sm-9">
                <?php
                foreach ($item->records as $k=>$v){
                    foreach ($father as $value){
                        if($value == $v->name){
                            echo $v->name . ' : ' . $v->phone . ';&nbsp;&nbsp;&nbsp;';
                        }
                    }
                    foreach ($mother as $value){
                        if($value == $v->name){
                            echo $v->name . ' : ' . $v->phone . ';&nbsp;&nbsp;&nbsp;';
                        }
                    }
                }
                ?>
            </div>
        </div>

    </div>
    <div class="tabCon">
        <div class="map_wrap">
            <div id="map_canvas"></div>
        </div>
    </div>
    <div class="tabCon">
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Bank：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->bank->BankName ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Branch：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->bank->SubBankName ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Account：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->bank->BankCode ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Contact Mobile：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->bank->Contact ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Contact：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->bank->ContactName ?>" placeholder="">
            </div>
        </div>
    </div>
    <div class="tabCon" style="max-height: 200px;overflow: scroll">
            <?php $totalTime = 0;?>
        <?php
            $arr = array();
            foreach ($item->contact as $k=>$v){
                array_push($arr, $v->phone);
            }
        ?>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Name</label>
            <label class="form-label col-xs-3 col-sm-3">Phone</label>
            <label class="form-label col-xs-3 col-sm-3">CallTime</label>
            <label class="form-label col-xs-2 col-sm-2">Total Count</label>
            <label class="form-label col-xs-2 col-sm-2">Total Duration</label>
        </div>
        <?php foreach ($item->records as $k=>$v):?>
            <?php
//                $totalTime += floatval($v->duration);
            ?>
            <div class="row cl">
                <label class="form-label col-xs-2 col-sm-2"><?= $v->name ?></label>
                <label <?php if(in_array($v->phone,$arr)):?>style="color:red;"<?php endif;?> class="form-label col-xs-3 col-sm-3"><?= $v->phone ?></label>
                <label class="form-label col-xs-3 col-sm-3"><?= $v->callTime ?></label>
                <label class="form-label col-xs-2 col-sm-2"><?= $v->c ?></label>
                <label class="form-label col-xs-2 col-sm-2"><?= $v->s ?></label>
            </div>
        <?php endforeach;?>
    </div>
    <div class="tabCon" style="max-height: 200px;overflow: scroll">
        <?php foreach ($item->audits as $k=>$v):?>
            <div class="row cl">
                <label class="form-label col-xs-2 col-sm-2">AduitType：
                    <?php if($v->AduitType == 1):?>
                        Loan Applied Audit
                    <?php elseif($v->AduitType == 2):?>
<!--                        还款申请审核-->
                        Loan Paid Apply Audit
                    <?php elseif($v->AduitType == 3):?>
                        Loan Paid Audit
                    <?php elseif($v->AduitType == 4):?>
                        User Apply forloan
                    <?php elseif($v->AduitType == 5):?>
                        User Applied Payback
                    <?php elseif($v->AduitType == 6):?>
<!--                        用户申请延期-->
                        Extend Aduit
                    <?php endif;?>
                </label>
                <label class="form-label col-xs-5 col-sm-5">Description：<?= $v->Description ?></label>
                <label class="form-label col-xs-2 col-sm-2">auditTime：<?= $v->auditTime ?></label>
                <label class="form-label col-xs-2 col-sm-2">Status：<?= $v->Status == 1 ? 'pass' : 'Unpass' ?></label>

            </div>
        <?php endforeach;?>
    </div>
    <div class="tabCon" style="max-height: 200px;overflow: scroll">
    <?php if(isset($show_contact) && $show_contact):?>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Name</label>
            <label class="form-label col-xs-3 col-sm-3">Phone</label>
        </div>
        <?php foreach ($item->contactName as $k=>$v):?>
            <div class="row cl">
                <label class="form-label col-xs-3 col-sm-3"><?= $v ?></label>
                <label class="form-label col-xs-2 col-sm-2"><?= $k ?></label>
            </div>
        <?php endforeach;?>
    <?php endif;?>
    </div>
    <div class="tabCon" style="max-height: 200px;overflow: scroll">
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">PayBackDebitMoney</label>
            <label class="form-label col-xs-3 col-sm-3">type</label>
            <label class="form-label col-xs-3 col-sm-3">CreateTime</label>
            <label class="form-label col-xs-3 col-sm-3">Status</label>
        </div>
        <?php if($item->paybackRecord):?>
            <?php foreach ($item->paybackRecord as $k=>$v):?>
                <?php if($v->money > 0):?>
                    <div class="row cl">
                        <label class="form-label col-xs-2 col-sm-2"><?= $v->money ?></label>
                        <label class="form-label col-xs-3 col-sm-3"><?= $v->type == 1 ? 'Payback' : $v->type == 2 ? 'Extend' : $v->type == 3 ? 'Duitku Extend' : 'Extend Payback' ?></label>
                        <label class="form-label col-xs-3 col-sm-3"><?= $k->CreateTime ?></label>
                        <label class="form-label col-xs-3 col-sm-3"><?= $k->Status == -2 ? '通过Duitku支付，未回调' : $k->Status == -1 ? '未通过' : $k->Status == 1 ? '审核中' : '已通过' ?></label>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>

<script>
    window.onload = function (ev) {
        $(function () {
            //var totalTime = "<?//=$totalTime?>//";
            // $('#totalTime').text($('#totalTime').text() + ' (' +totalTime + 's)');
        })
        loadMapScenario();
    }
</script>