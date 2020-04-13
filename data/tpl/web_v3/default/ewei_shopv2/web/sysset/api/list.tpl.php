<?php defined('IN_IA') or exit('Access Denied');?>
<?php  if(is_array($gifts)) { foreach($gifts as $item) { ?>
<tr>
    <td>
        <input type='checkbox'  value="<?php  echo $item['id'];?>"/>
    </td>
    <td class='full' style="overflow-x: hidden">
        <?php if(cv('sysset.api.edit')) { ?>
        <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('sysset/api/change',array('typechange'=>'company','id'=>$item['id']))?>" ><?php  echo $item['company'];?></a>
        <?php  } else { ?>
        <?php  echo $item['company'];?>
        <?php  } ?>
    </td>
    <td>
        <?php if(cv('sysset.api.edit')) { ?>
        <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('sysset/api/change',array('typechange'=>'mobile','id'=>$item['id']))?>" ><?php  echo $item['mobile'];?></a>
        <?php  } else { ?>
        <?php  echo $item['mobile'];?>
        <?php  } ?>
    </td>
    <td>
        <span><?php  echo $item['principal'];?></span>
    </td>
    <td>
        <span><?php  echo $item['apikey'];?></span>
    </td>
    <td>
        <span><?php  echo $item['apisecret'];?></span>
    </td>
    <td  style="overflow:visible;">
        <span class='label <?php  if($item['status']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
        <?php if(cv('sysset.api.edit')) { ?>
        data-toggle='ajaxSwitch'
        data-confirm = "确认是<?php  if($item['status']==1) { ?>禁止<?php  } else { ?>正常<?php  } ?>？"
        data-switch-refresh='true'
        data-switch-value='<?php  echo $item['status'];?>'
        data-switch-value0='0|禁止|label label-default|<?php  echo webUrl('sysset/api/status',array('status'=>1,'id'=>$item['id']))?>'
        data-switch-value1='1|正常|label label-primary|<?php  echo webUrl('sysset/api/status',array('status'=>0,'id'=>$item['id']))?>'
        <?php  } ?>>
        <?php  if($item['status']==1) { ?>正常<?php  } else { ?>禁止<?php  } ?></span>
    </td>
    <td  style="overflow:visible;position:relative;text-align: right;">
        <?php if(cv('sysset.api.edit|sysset.api.view')) { ?>
        <a  class='btn btn-op btn-operation' href="<?php  echo webUrl('sysset/api/edit', array('type'=>$_GPC['type'],'id' => $item['id'],'page'=>$page))?>" title="<?php if(cv('sysset.api.edit')) { ?>编辑<?php  } else { ?>查看<?php  } ?>">
            <span data-toggle="tooltip" data-placement="top" data-original-title="<?php if(cv('sysset.api.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>"><i class="icow icow-bianji2"></i></span>
        </a>
        <?php  } ?>
        <?php if(cv('sale.giftbag.delete1')) { ?>
        <a  class='btn btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('sysset/api/delete1', array('id' => $item['id']))?>" data-confirm='如果此活动存在购买记录，会无法关联到商品, 确认要彻底删除吗?？'>
            <span data-toggle="tooltip" data-placement="top" data-original-title="删除"><i class="icow icow-shanchu1"></i></span>
        </a>
        <?php  } ?>
    </td>
</tr>
<?php  } } ?>

<!--6Z2S5bKb5piT6IGU5LqS5Yqo572R57uc56eR5oqA5pyJ6ZmQ5YWs5Y+454mI5p2D5omA5pyJ-->