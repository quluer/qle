<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-sm-2 control-label must">商品APP详情图片</label>

    <div class="col-sm-9 col-xs-12 gimgs">

        <?php if( ce('goods' ,$item) ) { ?>

        <?php  echo tpl_form_field_multi_image2('app_thumbs',$app_list)?>

        <span class="help-block">您可以拖动图片改变其显示顺序 </span>

        <?php  } else { ?>

        <?php  if(is_array($app_list)) { foreach($app_list as $p) { ?>

        <a href='<?php  echo tomedia($p)?>' target='_blank'>

            <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />

        </a>

        <?php  } } ?>

        <?php  } ?>

    </div>

</div>