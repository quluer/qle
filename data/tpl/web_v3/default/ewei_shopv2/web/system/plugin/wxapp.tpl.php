<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary">站点小程序设置</span>
</div>
 
<div class="page-content">
    <form class="form-horizontal form-validate" method="post">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#tab_manage">商家管理中心</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_manage">
                <div class="form-group">
                    <label class="col-lg control-label must" style="padding-top: 0;">AppID<br>(小程序ID)</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="mmanage[appid]" class="form-control" value="<?php  echo $set['mmanage']['appid'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label must" style="padding-top: 0;">AppSecret<br>(小程序密钥)</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="mmanage[secret]" class="form-control" value="<?php  echo $set['mmanage']['secret'];?>" />
                    </div>
                </div>
                <div class="form-group splitter"></div>
                <div class="form-group">
                    <label class="col-lg control-label">登录界面logo</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image2('mmanage[logo]', $set['mmanage']['logo'])?>
                        <div class="help-block">建议尺寸100*100</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label">登录界面名称</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="mmanage[name]" class="form-control" value="<?php  echo $set['mmanage']['name'];?>" />
                    </div>
                </div>
                <div class="form-group splitter"></div>
                <div class="form-group">
                    <label class="col-lg control-label">二维码</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image2('mmanage[qrcode]', $set['mmanage']['qrcode'])?>
                        <div class="help-block">上传后，管理员可直接在后台页面使用此二维码登录手机端后台 建议尺寸100*100</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label">小程序状态</label>
                    <div class="col-sm-9 col-xs-12">
                        <label class="radio-inline"><input type="radio" value="1" name="mmanage[open]" <?php  if(!empty($set['mmanage']['open'])) { ?>checked<?php  } ?>> 开启</label>
                        <label class="radio-inline"><input type="radio" value="0" name="mmanage[open]" <?php  if(empty($set['mmanage']['open'])) { ?>checked<?php  } ?>> 关闭</label>
                        <div class="help-block">关闭后将用户将无法登录使用</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="submit" value="提交" class="btn btn-primary" />
                </div>
            </div>
        </div>
    </form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>