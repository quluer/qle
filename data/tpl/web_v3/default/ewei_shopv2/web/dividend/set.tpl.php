<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style>
    .col-lger {
        float: left;
        padding-right: 10px;
        padding-left: 10px;
        position: relative;
        width: 212px;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">基础设置</span>
</div>

<div class="page-content">
    <form id="setform"  <?php if(cv('dividend.set.edit')) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate">

        <input type="hidden" id="tab" name="tab" value="#tab_basic" />
        <div class="tabs-container>
         <div class="tabs-left">
         <ul class="nav nav-tabs" id="myTab">
            <li  <?php  if(empty($_GPC['tab']) || $_GPC['tab']=='basic') { ?>class="active"<?php  } ?>><a href="#tab_basic">基本</a></li>
            <li <?php  if($_GPC['tab']=='money') { ?>class="active"<?php  } ?> ><a href="#tab_money">结算</a></li>
            <li <?php  if($_GPC['tab']=='center') { ?>class="active"<?php  } ?> ><a href="#tab_center">分红中心</a></li>
            <li <?php  if($_GPC['tab']=='style') { ?>class="active"<?php  } ?> ><a href="#tab_style">样式/文字</a></li>
            <li  <?php  if($_GPC['tab']=='protocol') { ?>class="active"<?php  } ?>><a href="#tab_protocol">申请协议</a></li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane   <?php  if(empty($_GPC['tab']) || $_GPC['tab']=='basic') { ?>active<?php  } ?>" id="tab_basic"><div class="panel-body"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dividend/set/basic', TEMPLATE_INCLUDEPATH)) : (include template('dividend/set/basic', TEMPLATE_INCLUDEPATH));?></div></div>
            <div class="tab-pane <?php  if($_GPC['tab']=='money') { ?>active<?php  } ?>" id="tab_money"> <div class="panel-body"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dividend/set/money', TEMPLATE_INCLUDEPATH)) : (include template('dividend/set/money', TEMPLATE_INCLUDEPATH));?></div></div>
            <div class="tab-pane <?php  if($_GPC['tab']=='center') { ?>active<?php  } ?>" id="tab_center"> <div class="panel-body"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dividend/set/center', TEMPLATE_INCLUDEPATH)) : (include template('dividend/set/center', TEMPLATE_INCLUDEPATH));?></div></div>
            <div class="tab-pane <?php  if($_GPC['tab']=='style') { ?>active<?php  } ?>" id="tab_style"> <div class="panel-body"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dividend/set/style', TEMPLATE_INCLUDEPATH)) : (include template('dividend/set/style', TEMPLATE_INCLUDEPATH));?></div></div>
            <div class="tab-pane <?php  if($_GPC['tab']=='protocol') { ?>active<?php  } ?>" id="tab_protocol"> <div class="panel-body"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dividend/set/protocol', TEMPLATE_INCLUDEPATH)) : (include template('dividend/set/protocol', TEMPLATE_INCLUDEPATH));?></div></div>
        </div>
        </div>
    <?php if(cv('dividend.set.edit')) { ?>
        <div class="form-group">
        <label class="col-lg control-label"></label>
        <div class="col-sm-9 col-xs-12">
            <input type="submit"  value="提交" class="btn btn-primary" />
        </div>
        </div>
    <?php  } ?>

    </form>
</div>
<script language='javascript'>
        require(['bootstrap'], function () {
            $('#myTab a').click(function (e) {
                $('#tab').val($(this).attr('href'));
                e.preventDefault();
                $(this).tab('show');
            })
        });
        function showBecome(obj) {
            var $this = $(obj);
            $('.become').hide();
            $('.becomeconsume').hide();

            if ($this.val() == '1') {
                $('.protocol-group').show();
            } else {
                $('.protocol-group').hide();
            }

            if ($this.val() == '2') {
                $('.become2').show();
                $('.becomeconsume').show();
            } else if ($this.val() == '3') {
                $('.become3').show();
                $('.becomeconsume').show();
            } else if ($this.val() == '4') {
                $('.become4').show();
                $('.becomeconsume').show();
            }

        }
        $('#cashother').click(function () {
            if ($(this).prop('checked')) {
                $(".cashother-group").show();
            }
            else {
                $(".cashother-group").hide();
            }
        })

        $(document).on("click", '[name="data[cansee]"]',function(){
            var cansee = $('[name="data[cansee]"]:checked').val();
            if (cansee ==1){
                $("#seetitle").attr("style","");
            }else{
                $("#seetitle").attr("style","display: none");
            }
        })

        $('form').submit(function () {
            var become_child = $(":radio[name='data[become_child]']:checked").val();
            if (become_child == '1' || become_child == '2') {
                if ($(":radio[name='data[become]']:checked").val() == '0') {
                    $('form').attr('stop', 1), tip.msgbox.err('成为下线条件选择了首次下单/首次付款，成为分销商条件不能选择无条件!');

                    return false;
                }
            }
            $('form').removeAttr('stop');
            return true;
        })

    function changeCondition(val){
        if(val == 0){
            $('#downline').hide();
            $('#commissiondownline').hide();
            $('#total_commission').hide();
            $('#cash_commission').hide();
        }else if(val == 1){
            $('#downline').show();
            $('#commissiondownline').hide();
            $('#total_commission').hide();
            $('#cash_commission').hide();
        }else if(val == 2){
            $('#downline').hide();
            $('#commissiondownline').show();
            $('#total_commission').hide();
            $('#cash_commission').hide();
        }else if(val == 3){
            $('#downline').hide();
            $('#commissiondownline').hide();
            $('#total_commission').show();
            $('#cash_commission').hide();
        }else if(val == 4){
            $('#downline').hide();
            $('#commissiondownline').hide();
            $('#total_commission').hide();
            $('#cash_commission').show();
        }
    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>