<?php defined('IN_IA') or exit('Access Denied');?><!--  
<div class="fui-navbar">
    <a href="<?php  echo mobileUrl('merchmanage')?>" class="external nav-item <?php  if($_W['routes']=='merchmanage') { ?>active<?php  } ?>">
        <span class="icon icon-home"></span>
        <span class="label">工作台</span>
    </a>

   
    <a href="<?php  echo mobileUrl('merchmanage/goods')?>" class="external nav-item <?php  if($_W['controller']=='goods') { ?>active<?php  } ?>">
        <span class="icon icon-goods"></span>
        <span class="label">商品</span>
    </a>

    <a href="<?php  echo mobileUrl('merchmanage/order', array('status'=>1))?>" class="external nav-item <?php  if($_W['controller']=='order') { ?>active<?php  } ?>">
        <span class="icon icon-rejectedorder"></span>
        <span class="label">订单</span>
    </a>

    <a href="<?php  echo mobileUrl('merchmanage/apply/manage')?>" class="external nav-item <?php  if($_W['controller']=='apply') { ?>active<?php  } ?>">
        <span class="icon icon-home"></span>
        <span class="label">结算</span>
    </a>
    

    <a href="<?php  echo mobileUrl('merchmanage/shop')?>" class="external nav-item <?php  if($_W['controller']=='shop') { ?>active<?php  } ?>">
        <span class="icon icon-set"></span>
        <span class="label">设置</span>
    </a>
</div>
-->
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/footer.css">
<div class="footer_box">
            <div class="footernav <?php  if($_W['routes']=='merchmanage') { ?>selected<?php  } ?>" onclick="location.href='<?php  echo mobileUrl('merchmanage')?>'">
                <div class="nav-img01"></div>
                <span class="nav-text">工作台</span>
            </div>
            <div class="footernav <?php  if($_W['controller']=='statistics') { ?>selected<?php  } ?>" onclick="location.href='<?php  echo mobileUrl('merchmanage/statistics')?>'">
                <div class="nav-img02"></div>
                <span class="nav-text">数据</span>
            </div>
            <div class="footernav <?php  if($_W['controller']=='shop') { ?>selected<?php  } ?>" onclick="location.href='<?php  echo mobileUrl('merchmanage/shop')?>'">
                <div class="nav-img03"></div>
                <span class="nav-text">店铺</span>
            </div>
</div>