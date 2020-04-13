<?php defined('IN_IA') or exit('Access Denied');?>
<div class="form-group">
    <label class="col-lg control-label">会员中心团队分红入口</label>
    <div class="col-sm-9 col-xs-12">
        <div class="row">
            <?php if(cv('dividend.set.edit')) { ?>
            <div class="col-sm-3">
                <label class="radio-inline">
                    <input type="radio" name="data[membershow]" value="1" <?php  if($data['membershow'] ==1) { ?> checked="checked"<?php  } ?> /> 是
                </label>
                <label class="radio-inline">
                    <input type="radio"  name="data[membershow]" value="0" <?php  if(empty($data['membershow'])) { ?> checked="checked"<?php  } ?> /> 否
                </label>
            </div>
            <?php  } else { ?>
            <?php  } ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label">分销中心团队分红入口</label>
    <div class="col-sm-9 col-xs-12">
        <div class="row">
            <?php if(cv('dividend.set.edit')) { ?>
            <div class="col-sm-3">
                <label class="radio-inline">
                    <input type="radio" name="data[commissionshow]" value="1" <?php  if($data['commissionshow'] ==1) { ?> checked="checked"<?php  } ?> /> 是
                </label>
                <label class="radio-inline">
                    <input type="radio"  name="data[commissionshow]" value="0" <?php  if(empty($data['commissionshow'])) { ?> checked="checked"<?php  } ?> /> 否
                </label>
            </div>
            <?php  } else { ?>

            <?php  } ?>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label">申请说明</label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <label class="radio-inline"><input type="radio"  name="data[register_bottom]" value="0" <?php  if(empty($data['register_bottom'])) { ?> checked="checked"<?php  } ?> /> 默认</label>
        <label class="radio-inline"><input type="radio"  name="data[register_bottom]" value="1" <?php  if($data['register_bottom'] ==1) { ?> checked="checked"<?php  } ?> /> 模式1(标题和内容替换)</label>
        <label class="radio-inline"><input type="radio"  name="data[register_bottom]" value="2" <?php  if($data['register_bottom'] ==2) { ?> checked="checked"<?php  } ?> /> 模式2(整体替换)</label>
        <?php  } else { ?>
        <?php  if(empty($data['register_bottom'])) { ?>否<?php  } else { ?>是<?php  } ?>
        <?php  } ?>
        <span class="help-block"></span>
    </div>
</div>

<div class="r-group12" <?php  if(empty($data['register_bottom'])) { ?>style="display: none"<?php  } ?>>
<div class="col-sm-5">
    <img src="../addons/ewei_shopv2/plugin/dividend/static/images/dividend_reg.png" height="100%" width="100%"/>
</div>
</div>


<div class="col-sm-7 r-group1" <?php  if($data['register_bottom']!=1) { ?>style="display: none"<?php  } ?>>

<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
        图中的小图标不可替换
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label">标题1</label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <input type='text' class='form-control' name='data[register_bottom_title1]' value="<?php  echo $data['register_bottom_title1'];?>" />
        <?php  } else { ?>
        <?php  echo $data['register_bottom_title1'];?>
        <?php  } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label">内容1</label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <textarea class='form-control' name="data[register_bottom_content1]" rows="3"><?php  echo $data['register_bottom_content1'];?></textarea>
        <?php  } else { ?>
        <?php  echo $data['register_bottom_content1'];?>
        <?php  } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label">标题2</label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <input type='text' class='form-control' name='data[register_bottom_title2]' value="<?php  echo $data['register_bottom_title2'];?>" />
        <?php  } else { ?>
        <?php  echo $data['register_bottom_title2'];?>
        <?php  } ?>
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label">内容2</label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <textarea class='form-control' name="data[register_bottom_content2]" rows="3"><?php  echo $data['register_bottom_content2'];?></textarea>
        <?php  } else { ?>
        <?php  echo $data['register_bottom_content2'];?>
        <?php  } ?>
    </div>
</div>





</div>

<script>
    $(function () {
        $(":radio[name='data[qrcode]']").on('click',function (e) {
            var $this = $(this);
            var $qrcode = $("#qrcode");
            if($this.val()==0){
                $qrcode.hide();
            }else{
                $qrcode.show();
            }
        })
        $(":radio[name='data[qrcodeshare]']").on("click",function(){
            var shareVal = $(this).val();
            if(shareVal == 1){
                $("#codeShare").show();
            }else{
                $("#codeShare").hide();
            }
        });
    });
</script>
<div class="col-sm-7 r-group2" <?php  if($data['register_bottom']!=2) { ?>style="display: none"<?php  } ?>>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
        <?php if(cv('commission.set.edit')) { ?>
        <?php  echo tpl_ueditor('data[register_bottom_content]',$data['register_bottom_content'],array('height'=>200))?>
        <?php  } else { ?>
        <textarea id='register_bottom_content' style='display:none'><?php  echo $data['register_bottom_content'];?></textarea>
        <a href='javascript:preview_html("#register_bottom_content")' class="btn btn-default">查看内容</a>
        <?php  } ?>
    </div>
</div>
</div>

<script>
    $(function () {
        $(":radio[name='data[qrcode]']").on('click',function (e) {
            var $this = $(this);
            var $qrcode = $("#qrcode");
            if($this.val()==0){
                $qrcode.hide();
            }else{
                $qrcode.show();
            }
        })
        $(":radio[name='data[register_bottom]']").on('click',function (e) {
            var $this = $(this);

            if($this.val()==0){
                $(".r-group12").hide();
                $(".r-group1").hide();
                $(".r-group2").hide();
            } else if($this.val()==1){
                $(".r-group12").show();
                $(".r-group1").show();
                $(".r-group2").hide();
            } else if($this.val()==2){
                $(".r-group12").show();
                $(".r-group1").hide();
                $(".r-group2").show();
            }
        })
    });
</script>
