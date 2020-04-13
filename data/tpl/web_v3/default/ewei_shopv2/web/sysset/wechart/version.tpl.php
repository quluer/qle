<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">当前位置：<span class="text-primary">小程序显示设置</span></div>

  <div class="page-content">
      <form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data" >
          <div class="form-group">
              <label class="col-lg control-label">门店显示</label>
              <div class="col-sm-9 col-xs-12">
                  <label class="radio radio-inline">
                      <input type="radio" name="storeshow" value="1" <?php  if($setting['value']==1) { ?>checked<?php  } ?>/> 开启
                  </label>
                  <label class="radio radio-inline">
                      <input type="radio" name="storeshow" value="0" <?php  if($setting['value']==0) { ?>checked<?php  } ?>/> 关闭
                  </label>
              </div>
          </div>

          <div class="form-group">
              <label class="col-lg control-label">商品分享</label>
              <div class="col-sm-9 col-xs-12">
                  <label class="radio radio-inline">
                      <input type="radio" name="goodsshare" value="1" <?php  if($goodsshare['value']==1) { ?>checked<?php  } ?>/> 开启
                  </label>
                  <label class="radio radio-inline">
                      <input type="radio" name="goodsshare" value="0" <?php  if($goodsshare['value']==0) { ?>checked<?php  } ?>/> 关闭
                  </label>
              </div>
          </div>

          <div class="form-group">
              <label class="col-lg control-label">RVC明细</label>
              <div class="col-sm-9 col-xs-12">
                  <label class="radio radio-inline">
                      <input type="radio" name="RVC" value="1" <?php  if($RVC['value']==1) { ?>checked<?php  } ?>/> 开启
                  </label>
                  <label class="radio radio-inline">
                      <input type="radio" name="RVC" value="0" <?php  if($RVC['value']==0) { ?>checked<?php  } ?>/> 关闭
                  </label>
              </div>
          </div>

          <div class="form-group">
              <label class="col-lg control-label"></label>
              <div class="col-sm-9 col-xs-12">
                  <input type="submit" value="提交" class="btn btn-primary"  />
              </div>
          </div>

      </form>
  </div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>     
