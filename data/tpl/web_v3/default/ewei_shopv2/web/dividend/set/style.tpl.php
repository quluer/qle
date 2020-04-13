<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-lg control-label">注册面头部图片</label>
    <div class="col-sm-9 col-xs-12">
    	<?php if(cv('dividend.set.edit')) { ?>
			<?php  echo tpl_form_field_image('data[regbg]',$data['regbg'],'../addons/ewei_shopv2/plugin/dividend/static/images/banner.jpg')?>
		<?php  } else { ?>
			<?php  if(empty($data['regbg'])) { ?>
				<img src="../addons/ewei_shopv2/plugin/dividend/static/images/banner.jpg" onerror="this.src='../addons/ewei_shopv2/plugin/dividend/static/images/banner.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" width="150">
			<?php  } else { ?>
				<img src="<?php  echo tomedia($data['regbg'])?>" onerror="this.src='../addons/ewei_shopv2/plugin/dividend/static/images/banner.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" width="150">
			<?php  } ?>
		<?php  } ?>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">团队分红名称</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[agent]" class="form-control" value="<?php echo empty($data['texts']['agent'])?'队长':$data['texts']['agent']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['agent'])?'团队分红':$data['texts']['agent']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>

<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">分红中心</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[center]" class="form-control" value="<?php echo empty($data['texts']['center'])?'分红中心':$data['texts']['center']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['center'])?'分红中心':$data['texts']['center']?></div>
	    <?php  } ?>	
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">成为队长</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[become]" class="form-control" value="<?php echo empty($data['texts']['become'])?'成为队长':$data['texts']['become']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['become'])?'成为队长':$data['texts']['become']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">提现</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[withdraw]" class="form-control" value="<?php echo empty($data['texts']['withdraw'])?'提现':$data['texts']['withdraw']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['withdraw'])?'提现':$data['texts']['withdraw']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend]" class="form-control" value="<?php echo empty($data['texts']['dividend'])?'分红':$data['texts']['dividend']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend'])?'分红':$data['texts']['dividend']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">团队分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend1]" class="form-control" value="<?php echo empty($data['texts']['dividend1'])?'团队分红':$data['texts']['dividend1']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend1'])?'团队分红':$data['texts']['dividend1']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">累计分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_total]" class="form-control" value="<?php echo empty($data['texts']['dividend_total'])?'累计分红':$data['texts']['dividend_total']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_total'])?'累计分红':$data['texts']['dividend_total']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">可提现分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_ok]" class="form-control" value="<?php echo empty($data['texts']['dividend_ok'])?'可提现分红':$data['texts']['dividend_ok']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_ok'])?'可提现分红':$data['texts']['dividend_ok']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">已申请分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_apply]" class="form-control" value="<?php echo empty($data['texts']['dividend_apply'])?'已申请分红':$data['texts']['dividend_apply']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_apply'])?'已申请分红':$data['texts']['dividend_apply']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">待打款分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_check]" class="form-control" value="<?php echo empty($data['texts']['dividend_check'])?'待打款分红':$data['texts']['dividend_check']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_check'])?'待打款分红':$data['texts']['dividend_check']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">未结算分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_lock]" class="form-control" value="<?php echo empty($data['texts']['dividend_lock'])?'未结算分红':$data['texts']['dividend_lock']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_lock'])?'未结算分红':$data['texts']['dividend_lock']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
	<label class="col-lg control-label"></label>
	<div class="col-sm-9 col-xs-12">
		<div class="input-group">
			<div class="input-group-addon">待收货分红</div>
			<?php if(cv('dividend.set.edit')) { ?>
			<input type="text" name="texts[dividend_wait]" class="form-control" value="<?php echo empty($data['texts']['dividend_wait'])?'待收货分红':$data['texts']['dividend_wait']?>"  />
			<?php  } else { ?>
			<div class="form-control valid"><?php echo empty($data['texts']['dividend_wait'])?'待收货分红':$data['texts']['dividend_wait']?></div>
			<?php  } ?>
		</div>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label"></label>
	<div class="col-sm-9 col-xs-12">
		<div class="input-group">
			<div class="input-group-addon">无效分红</div>
			<?php if(cv('dividend.set.edit')) { ?>
			<input type="text" name="texts[dividend_fail]" class="form-control" value="<?php echo empty($data['texts']['dividend_fail'])?'无效分红':$data['texts']['dividend_fail']?>"  />
			<?php  } else { ?>
			<div class="form-control valid"><?php echo empty($data['texts']['dividend_fail'])?'无效分红':$data['texts']['dividend_fail']?></div>
			<?php  } ?>
		</div>
	</div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">成功提现分红</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_pay]" class="form-control" value="<?php echo empty($data['texts']['dividend_pay'])?'成功提现分红':$data['texts']['dividend_pay']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_pay'])?'成功提现分红':$data['texts']['dividend_pay']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
        <div class="input-group">
            <div class="input-group-addon">扣除提现手续费</div>
            <?php if(cv('dividend.set.edit')) { ?>
            <input type="text" name="texts[dividend_charge]" class="form-control" value="<?php echo empty($data['texts']['dividend_charge'])?'扣除提现手续费':$data['texts']['dividend_charge']?>"  />
            <?php  } else { ?>
            <div class="form-control valid"><?php echo empty($data['texts']['dividend_charge'])?'成功提现佣金':$data['texts']['dividend_charge']?></div>
            <?php  } ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">分红明细</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    	<input type="text" name="texts[dividend_detail]" class="form-control" value="<?php echo empty($data['texts']['dividend_detail'])?'分红明细':$data['texts']['dividend_detail']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['dividend_detail'])?'分红明细':$data['texts']['dividend_detail']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <div class="col-sm-9 col-xs-12">
	<div class="input-group">
	    <div class="input-group-addon">团队订单</div>
	    <?php if(cv('dividend.set.edit')) { ?>
	    <input type="text" name="texts[order]" class="form-control" value="<?php echo empty($data['texts']['order'])?'团队订单':$data['texts']['order']?>"  />
	    <?php  } else { ?>
	    	<div class="form-control valid"><?php echo empty($data['texts']['order'])?'团队订单':$data['texts']['order']?></div>
	    <?php  } ?>
	</div>
    </div>
</div>


<div class="form-group">
	<label class="col-lg control-label"></label>
	<div class="col-sm-9 col-xs-12">
		<div class="input-group">
			<div class="input-group-addon">元</div>
			<?php if(cv('dividend.set.edit')) { ?>
			<input type="text" name="texts[yuan]" class="form-control" value="<?php echo empty($data['texts']['yuan'])?'元':$data['texts']['yuan']?>"  />
			<?php  } else { ?>
			<div class="form-control valid"><?php echo empty($data['texts']['yuan'])?'元':$data['texts']['yuan']?></div>
			<?php  } ?>
		</div>
	</div>
</div>
