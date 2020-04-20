<?php defined('IN_IA') or exit('Access Denied');?><div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        商品详情
    </div>
    <div class="region-goods-right col-sm-10">
        <div id="tab-sale-1" class="tab-pane active">
            <div class="form-group">
                <label class="col-lg control-label">
                    促销方式
                </label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( mce('goods' ,$item) ) { ?>
                    <label for="isdiscount" class="checkbox-inline">
                        <input type="checkbox" name="isdiscount" value="1" id="isdiscount" <?php  if($item['isdiscount']==1 ) { ?>checked="true" <?php  } ?> onclick="showDiscount(this)"/>
                        促销
                    </label>
                    <label for="istime" class="checkbox-inline">
                        <input type="checkbox" name="istime" value="1" id="istime" <?php  if($item['istime']==1 ) { ?>checked="true" <?php  } ?> onclick="showTime(this)" />
                        限时卖
                    </label>
                    <?php  } else { ?>
                    <div class='form-control-static'>
                        <?php  if($item['isdiscount']==1) { ?>促销 <?php  } ?> <?php  if($item['istime']==1) { ?>限时卖<?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>
            <div id="timediv" class="form-group" <?php  if($item['istime']!=1) { ?>style="display:none"
            <?php  } ?>>
            <label class="col-lg control-label">
                限时卖时间
            </label>
            <?php if( mce('goods' ,$item) ) { ?>
            <div class="col-sm-4 col-xs-6">
                <?php  echo tpl_form_field_daterange('saletime', array('starttime'=>date('Y-m-d H:i', $item['timestart']),'endtime'=>date('Y-m-d H:i', $item['timeend'])),true);?>
            </div>
            <?php  } else { ?>
            <div class="col-sm-6 col-xs-6">
                <div class='form-control-static'>
                    <?php  if($item['istime']==1) { ?> <?php  echo date('Y-m-d H:i',$item['timestart'])?> - <?php  echo date('Y-m-d H:i',$item['timeend'])?> <?php  } ?>
                </div>
            </div>
            <?php  } ?>
        </div>
        <div id="isdiscount_time" class="form-group" <?php  if($item['isdiscount']!=1) { ?>style="display:none"
        <?php  } ?>>
        <label class="col-lg control-label">
            促销标题
        </label>
        <div class="col-sm-9 col-xs-12">
            <?php if( mce('goods' ,$item) ) { ?>
            <div class="input-group">
                <input type="text" name="isdiscount_title" maxlength="5" class="form-control"
                       value="<?php  echo $item['isdiscount_title'];?>" />
                <p class="help-block">
                    例如 : 季末清仓,双十一促销,品牌日 还可以输入
                                <span id="textCount">
                                    5
                                </span>
                    个字,不输入默认促销
                </p>
            </div>
            <?php  } else { ?>
            <div class='form-control-static'>
                <?php  echo $item['isdiscount_title'];?> 分
            </div>
            <?php  } ?>
        </div>
        <label class="col-lg control-label">
            促销结束时间
        </label>
        <?php if( mce('goods' ,$item) ) { ?>
        <div class="col-sm-4 col-xs-6">
            <?php echo tpl_form_field_date('isdiscount_time', !empty($item['isdiscount_time']) ? date('Y-m-d H:i',$item['isdiscount_time']) : date('Y-m-d H:i'),true)?>
        </div>
        <?php  } else { ?>
        <div class="col-sm-4 col-xs-6">
            <div class='form-control-static'>
                <?php  if($item['isdiscount_time']==1) { ?> <?php  echo date('Y-m-d H:i',$item['isdiscount_time'])?>}<?php  } ?>
            </div>
        </div>
        <?php  } ?>
    </div>
    <div id="isdiscount_true">
        <div id="isdiscount_discounts_1">
            <div class="isdiscount_discounts-info">
                <div class="col-sm-12">
                    <div class='alert alert-danger'>
                        详细设置促销价格 :
                        <br>
                        如果填写纯数字我们认为单位是元 例如填写 1 也就是促销价1元
                        <br>
                        如果填写百分数,我们认为是打折数 例如填写 90% 则就是打九折
                    </div>
                </div>
            </div>
            <div id='tbisdiscount_discounts' style="">
                <div class="col-sm-12">
                    <div id="isdiscount_discounts" style="padding:0;"><?php  if($item['hasoption']==1) { ?><?php  echo $isdiscount_discounts_html;?><?php  } ?></div>
                    <div id="isdiscount_discounts_default" style="padding:0;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        抵扣设置
    </div>
    <div class="region-goods-right col-sm-10">

        <div class="form-group" style="margin-left:125px;">
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline"><input type="radio" name="deduct_type" value="1" <?php  if($item['deduct_type'] == 1) { ?>checked="true"<?php  } ?>   /> 卡路里抵扣</label>
                <label class="radio-inline"><input type="radio" name="deduct_type" value="2" <?php  if($item['deduct_type'] == 2) { ?>checked="true"<?php  } ?>/>折扣宝抵扣</label>
            </div>
            <div class="col-sm-9 col-xs-12">
                <?php if( mce('goods' ,$item) ) { ?>
                <div class='input-group fixsingle-input-group'>
                            <span class="input-group-addon">
                                最多抵扣
                            </span>
                    <input type="text" name="deduct" value="<?php  echo $item['deduct'];?>" class="form-control"/>
                    <span class="input-group-addon">
                                元
                            </span>
                </div>
                <label class="checkbox-inline" for="manydeduct">
                    <input id="manydeduct" type="checkbox" <?php  if($item['manydeduct']==1 ) { ?>checked="true"<?php  } ?> value="1" name="manydeduct">
                    允许多件累计抵扣
                </label>
                <span class="help-block">
                            如果设置0，则不支持抵扣
                        </span>
                <?php  } else { ?>
                <div class='form-control-static'>
                    <?php  if(empty($item['deduct'])) { ?>不支持卡路里抵扣<?php  } else { ?>最多抵扣 <?php  echo $item['deduct'];?> 元 <?php  if(empty($item['manydeduct'])) { ?>不允许多件累计抵扣<?php  } else { ?>允许多件累计抵扣
                    <?php  } ?><?php  } ?>
                </div>
                <?php  } ?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label">
                余额抵扣
            </label>
            <div class="col-sm-9 col-xs-12">
                <?php if( mce('goods' ,$item) ) { ?>
                <div class='input-group fixsingle-input-group'>
                            <span class="input-group-addon">
                                最多抵扣
                            </span>
                    <input type="text" name="deduct2" value="<?php  echo $item['deduct2'];?>" class="form-control"
                    />
                    <span class="input-group-addon">
                                元
                            </span>
                </div>
                <span class="help-block">
                            如果设置0，则支持全额抵扣，设置-1 不支持余额抵扣
                        </span>
                <?php  } else { ?>
                <div class='form-control-static'>
                    <?php  if(empty($item['deduct2'])) { ?> 支持全额抵扣 <?php  } else if($item['deduct2']==-1) { ?> 不支持余额抵扣<?php  } else { ?> 最多抵扣 <?php  echo $item['deduct2'];?> 元 <?php  } ?>
                </div>
                <?php  } ?>
            </div>
        </div>
    </div>
</div>

<div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        促销方式
    </div>
    <div class="region-goods-right col-sm-10">
        <div id="tab-sale-4" class="tab-pane">
            <div class="form-group">
                <label class="col-lg control-label">
                    单品满件包邮
                </label>
                <div class="col-sm-4">
                    <?php if( mce('goods' ,$item) ) { ?>
                    <div class='input-group'>
                            <span class="input-group-addon">
                                满
                            </span>
                        <input type="text" name="ednum" value="<?php  echo $item['ednum'];?>" class="form-control"
                        />
                            <span class="input-group-addon">
                                件
                            </span>
                    </div>
                        <span class="help-block">
                            如果设置0或空，则不支持满件包邮
                        </span>
                    <?php  } else { ?>
                    <div class='form-control-static'>
                        <?php  if(empty($item['ednum'])) { ?>不支持满件包邮<?php  } else { ?>支持 <?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">
                    单品满额包邮
                </label>
                <div class="col-sm-4">
                    <?php if( mce('goods' ,$item) ) { ?>
                    <div class='input-group'>
                            <span class="input-group-addon">
                                满
                            </span>
                        <input type="text" name="edmoney" value="<?php  echo $item['edmoney'];?>" class="form-control"
                        />
                            <span class="input-group-addon">
                                元
                            </span>
                    </div>
                        <span class="help-block">
                            如果设置0或空，则不支持满额包邮
                        </span>
                    <?php  } else { ?>
                    <div class='form-control-static'>
                        <?php  if(empty($item['edmoney'])) { ?>不支持满额包邮<?php  } else { ?>支持 <?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">不参与单品包邮地区</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( mce('goods' ,$item) ) { ?>
                    <div id="areas" class="form-control-static"><?php  echo $item['edareas'];?></div>
                    <a href="javascript:;" class="btn btn-default" onclick="selectAreas()">添加不参加满包邮的地区</a>
                    <input type="hidden" id='selectedareas' name="edareas" value="<?php  echo $item['edareas'];?>"/>
                    <input type="hidden" id='selectedareas_code' name="edareas_code" value="<?php  echo $item['edareas_code'];?>"/>
                    <span class="help-block">如果设置0或空，则不支持满件包邮</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $set['enoughareas'];?></div>
                    <?php  } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    <?php if( mce('goods' ,$item) ) { ?>
    $(document).ready(function() {
        var Discount = document.getElementById("isdiscount");
        showDiscount(Discount);
    });
    function showTime(obj) {
        if (obj.checked) {
            $('#timediv').show();
            $('#isdiscount_time').hide();
            $('input[name="isdiscount"]').removeAttr('checked');
            $('#isdiscount_true').hide();
        } else {
            $('#timediv').hide();
        }
    }
    function showDiscount(obj) {
        if (obj.checked) {
            $('input[name="istime"]').removeAttr('checked');
            $('#timediv').hide();
            $('#isdiscount_time').show();
            $('#isdiscount_true').show();
        } else {
            $('#isdiscount_time').hide();
            $('#isdiscount_true').hide();
        }
    }
    <?php  } ?>
    function isdiscount_change() {
        var html = '<table class="table table-bordered table-condensed"><thead><tr class="active"><?php  if(is_array($levels)) { foreach($levels as $level) { ?><th><div class=""><div style="padding-bottom:10px;text-align:center;"><?php  echo $level["levelname"];?></div></div></th><?php  } } ?></tr></thead><tbody><tr><?php  if(is_array($levels)) { foreach($levels as $level) { ?><?php  if($level["key"]=="merch") { ?><td><input name="isdiscount_discounts_level_<?php  echo $level["key"];?>_default" type="text" class="form-control isdiscount_discounts_<?php  echo $level["key"];?> isdiscount_discounts_<?php  echo $level["key"];?>_default" value="<?php echo is_array($isdiscount_discounts[$level["key"]]["option0"]) ? '' : $isdiscount_discounts[$level["key"]]["option0"];?>" placeholder="促销价格单位: 元"></td><?php  } else { ?><td><input name="isdiscount_discounts_level_<?php  echo $level["id"];?>_default" type="text" class="form-control isdiscount_discounts_level<?php  echo $level["id"];?> isdiscount_discounts_level<?php  echo $level["id"];?>_default" value="<?php echo is_array($isdiscount_discounts["level".$level["id"]]["option0"]) ? '' : $isdiscount_discounts["level".$level["id"]]["option0"];?>" placeholder="促销价格单位: 元"></td><?php  } ?><?php  } } ?></tr></tbody></table>';
        if ($("#isdiscount_discounts").html()=='')
        {
            $("#isdiscount_discounts_default").html(html);
        }
        else
        {
            $("#isdiscount_discounts_default").html('');
        }
    }

    isdiscount_change();
    <?php if( mce('goods' ,$item) ) { ?>
    <?php  } ?>
</script>
<?php  if(empty($new_area)) { ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('shop/selectareas', TEMPLATE_INCLUDEPATH)) : (include template('shop/selectareas', TEMPLATE_INCLUDEPATH));?>
<?php  } else { ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('shop/selectareasNew', TEMPLATE_INCLUDEPATH)) : (include template('shop/selectareasNew', TEMPLATE_INCLUDEPATH));?>
<?php  } ?>
<!--4000097827-->