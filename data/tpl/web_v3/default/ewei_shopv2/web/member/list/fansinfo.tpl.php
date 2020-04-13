<?php defined('IN_IA') or exit('Access Denied');?><style>
    .recharge_info{
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        justify-content: space-around;
        margin-bottom: 10px;
    }
    .recharge_info>div{
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        border:1px solid #efefef;
        margin: 0 10px;
        padding:10px 22px;
        line-height: 25px;
        color: #333;
    }
</style>
<form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">会员充值</h4>
            </div>
            <div class="modal-body">
                <br/><br/>
                <div class="form-group">
                    <label class="col-sm-3 control-label">粉丝总量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['agentallcount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">直推粉丝总量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['agentcount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">店主数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['shopkeeperallcount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">直推店主数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['shopkeepercount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">星选达人数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['starshineallcount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">直推星选达人数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['starshinecount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">健康达人数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['healthyallcount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label mustl">直推健康达人数量</label>
                    <div class="col-sm-8 col-xs-12">
                        <input type="text" name="num" disabled="disabled" class="form-control" value="<?php  echo $agentinfo['healthycount'];?>" data-rule-number='true' data-rule-required='true' data-rule-min='0.01' />
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>

</form>
<!--OTEzNzAyMDIzNTAzMjQyOTE0-->