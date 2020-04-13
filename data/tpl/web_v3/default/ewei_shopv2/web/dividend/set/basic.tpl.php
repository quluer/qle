<?php defined('IN_IA') or exit('Access Denied');?><?php if(cv('dividend.set.edit')) { ?>
<div class="form-group">
	<label class="col-lg control-label">重新初始化</label>
	<div class="col-sm-9 col-xs-12">
		<input type="button" name="submit_cancel" data-toggle='refresh' data-href="<?php  echo webUrl('dividend/refresh')?>" value="初始化" class="btn btn-default">
		<span class="help-block">用于重新初始化团队分红关系树，请谨慎点击</span>
	</div>
</div>
<?php  } ?>
<div class="form-group">
    <label class="col-lg control-label">团队分红</label>
    <div class="col-sm-8">
    	<?php if(cv('dividend.set.edit')) { ?>
			<label class="radio-inline"><input type="radio"  name="data[open]" value="0" <?php  if($data['open'] ==0) { ?> checked="checked"<?php  } ?> /> 关闭</label>
			<label class="radio-inline"><input type="radio"  name="data[open]" value="1" <?php  if($data['open'] ==1) { ?> checked="checked"<?php  } ?> /> 开启</label>
		<?php  } else { ?>
			<?php  if($data['open'] ==0) { ?>关闭<?php  } ?>
			<?php  if($data['open'] ==1) { ?>开启<?php  } ?>
		<?php  } ?>
    </div>
</div>
<div class="form-group" >
	<label class="col-lg control-label">分红比例</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<input type="text" name="data[ratio]" class="form-control" value="<?php  echo $data['ratio'];?>" maxlength="6">
		<?php  } else { ?>
		<?php  echo $data['ratio'];?>
		<?php  } ?>
		<span class="help-block">用于计算每笔订单的分红金额 单位:%</span>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label">成为队长条件</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<label class="radio-inline"><input type="radio"  name="data[condition]" value="0" <?php  if($data['condition'] ==0) { ?> checked="checked"<?php  } ?> onclick="changeCondition(0)" /> 申请</label>
		<label class="radio-inline"><input type="radio"  name="data[condition]" value="1" <?php  if($data['condition'] ==1) { ?> checked="checked"<?php  } ?> onclick="changeCondition(1)" /> 下线人数</label>
		<label class="radio-inline"><input type="radio"  name="data[condition]" value="2" <?php  if($data['condition'] ==2) { ?> checked="checked"<?php  } ?> onclick="changeCondition(2)" /> 下线分销商数</label>
		<label class="radio-inline"><input type="radio"  name="data[condition]" value="3" <?php  if($data['condition'] ==3) { ?> checked="checked"<?php  } ?> onclick="changeCondition(3)" /> 获得佣金总额</label>
		<label class="radio-inline"><input type="radio"  name="data[condition]" value="4" <?php  if($data['condition'] ==4) { ?> checked="checked"<?php  } ?> onclick="changeCondition(4)" /> 已经提现佣金总额</label>
		<?php  } else { ?>
		<?php  if($data['condition'] ==0) { ?>关闭<?php  } else { ?>开启<?php  } ?>
		<?php  } ?>
	</div>
</div>

<div class="form-group" style="display: <?php  if($data['condition'] == 1) { ?>block<?php  } else { ?>none<?php  } ?>" id="downline">
	<label class="col-lg control-label">下线人数</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<input type="text" name="data[downline]" class="form-control" value="<?php  echo $data['downline'];?>" maxlength="6">
		<?php  } else { ?>
		<?php  if($data['downline'] ==0) { ?><?php  echo $data['downline'];?><?php  } ?>
		<?php  } ?>
		<span>一级下线人数</span>
	</div>

</div>

<div class="form-group" style="display:<?php  if($data['condition'] == 2) { ?> block<?php  } else { ?>none<?php  } ?>" id="commissiondownline">
	<label class="col-lg control-label">下线分销商数</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<input type="text" name="data[commissiondownline]" class="form-control" value="<?php  echo $data['commissiondownline'];?>" maxlength="6">
		<?php  } else { ?>
		<?php  if($data['commissiondownline'] ==0) { ?><?php  echo $data['commissiondownline'];?><?php  } ?>
		<?php  } ?>
		<span>一级下线分销商数</span>
	</div>
</div>

<div class="form-group" style="display: <?php  if($data['condition'] == 3) { ?> block<?php  } else { ?>none<?php  } ?>" id="total_commission">
	<label class="col-lg control-label">获得佣金总额</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<input type="text" name="data[total_commission]" class="form-control" value="<?php  echo $data['total_commission'];?>" maxlength="6">
		<?php  } else { ?>
		<?php  if($data['total_commission'] ==0) { ?><?php  echo $data['total_commission'];?><?php  } ?>
		<?php  } ?>
		<span>累计佣金总额</span>
	</div>
</div>

<div class="form-group" style="display: <?php  if($data['condition'] == 4) { ?> block<?php  } else { ?>none<?php  } ?>" id="cash_commission">
	<label class="col-lg control-label">已经提现佣金总额</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<input type="text" name="data[cash_commission]" class="form-control" value="<?php  echo $data['cash_commission'];?>" maxlength="6">
		<?php  } else { ?>
		<?php  if($data['cash_commission'] ==0) { ?><?php  echo $data['cash_commission'];?><?php  } ?>
		<?php  } ?>
		<span>提现佣金总额</span>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label">申请协议</label>
	<div class="col-sm-9 col-xs-12">
		<?php if(cv('dividend.set.edit')) { ?>
		<label class="radio-inline"><input type="radio"  name="data[open_protocols]" value="0" <?php  if($data['open_protocols'] =='0') { ?> checked="checked"<?php  } ?> /> 隐藏</label>
		<label class="radio-inline"><input type="radio"  name="data[open_protocols]" value="1" <?php  if($data['open_protocols'] =='1') { ?> checked="checked"<?php  } ?> /> 显示</label>
		<?php  } else { ?>
		<?php  if($data['open_protocol'] ==0) { ?>隐藏<?php  } else { ?>显示<?php  } ?>
		<?php  } ?>
	</div>
</div>