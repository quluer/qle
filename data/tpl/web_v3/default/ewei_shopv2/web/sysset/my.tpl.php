<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="utf-8"/>
<div class="page-header">当前位置：<span class="text-primary">我的</span></div>

    <div class="page-content">
        <form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data" >
           
            <div class="form-group">
                <label class="col-lg control-label">订单-待付款</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('payment', $data['order']["payment"])?>
                   
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-lg control-label">订单-待发货</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('send', $data['order']["send"])?>
                   
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg control-label">订单-待收货</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('received', $data['order']["received"])?>
                   
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg control-label">订单-退换货</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('evaluated', $data['order']["evaluated"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">订单-待评价</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('comment', $data['order']["comment"])?>
                   
                </div>
            </div>
            
            
            
            <div class="form-group">
                <label class="col-lg control-label">服务-粉丝</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('fans', $data['server']["fans"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-推荐</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('recommend', $data['server']["recommend"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-优惠券</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('coupon', $data['server']["coupon"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-领劵中心</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('coupon_center', $data['server']["coupon_center"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-购物车</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('cart', $data['server']["cart"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-我的关注</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('concern', $data['server']["concern"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-我的足迹</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('track', $data['server']["track"])?>
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">服务-收货地址</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('addr', $data['server']["addr"])?>
                   
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