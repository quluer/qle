<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style>
    .popover{
        width:170px;
        font-size:12px;
        line-height: 21px;
        color: #0d0706;
    }
    .popover span{
        color: #b9b9b9;
    }
    .nickname{
        display: inline-block;
        max-width:150px;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }


    .tooltip-inner{
        border:none;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">队长管理 </span>
</div>
<div class="page-content">
   <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="ewei_shopv2" />
            <input type="hidden" name="do" value="web" />
            <input type="hidden" name="r" value="dividend.agent" />
        <div class="page-toolbar m-b-sm m-t-sm">
            <span class="pull-left"><?php  echo tpl_daterange('time', array('sm'=>true, 'placeholder'=>'成为队长时间'),true);?></span>
            <div class="input-group">
                        <div class="input-group-select">
                            <select name='status' class='form-control' style="width:80px;"  >
                                <option value=''>状态</option>
                                <option value='0' <?php  if($_GPC['status']=='0') { ?>selected<?php  } ?>>未审核</option>
                                <option value='1' <?php  if($_GPC['status']=='1') { ?>selected<?php  } ?>>已审核</option>
                            </select>
                        </div>

                          <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="昵称/姓名/手机号"/>
                         <span class="input-group-btn">
                            <button class="btn  btn-primary" type="submit"> 搜索</button>
                             <?php if(cv('dividend.agent.export')) { ?>
                            <button type="submit" name="export" value="1" class="btn btn-success ">导出</button>
                            <?php  } ?>
                        </span>
                </div>
        </div>
  </form>
<?php  if(count($list)>0) { ?>
        <div class='page-table-header'>
            <input type="checkbox"/>
            <div class="btn-group">
                <?php if(cv('dividend.agent.edit')) { ?>
                <a class='btn btn-default btn-sm btn-op btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>1))?>"  data-confirm='确认要审核通过?'>
                    <i class="icow icow-shenhetongguo"></i>审核通过
                </a>
                <a class='btn btn-default btn-sm btn-op btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>0))?>" data-confirm='确认要取消审核?'>
                    <i class="icow icow-yiquxiao"></i>取消审核</a>
                <?php  } ?>

                <?php if(cv('dividend.agent.edit')) { ?>
                <a class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-confirm="确认要初始化?" data-href="<?php  echo webUrl('dividend/agent/init')?>">
                    <i class="icow icow-yongxinyonghu"></i> 初始化团队
                </a>
                <?php  } ?>

                <?php if(cv('dividend.agent.delete')) { ?>
                <a class="btn btn-default btn-sm btn-op btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('dividend/agent/delete')?>">
                    <i class='icow icow-shanchu1'></i> 删除
                </a>
                <?php  } ?>
            </div>
        </div>
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
            <tr>
                <th style="width:25px;"></th>
                <th style='width:100px;'>粉丝</th>
                <th style='width:110px;'>姓名<br/>手机号码</th>
                <th style='width:80px;'>累计分红<br/>打款分红</th>
                <th style='width:80px;'>团员数量</th>
                <th style='width:90px;'>注册时间</br>审核时间</th>
                <th style='width:70px;'>状态</th>
                <th style='width:70px;'>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $row) { ?>
           <tr >
                <td>
                    <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                </td>
               
                <td style="overflow: visible">
                    <div  style="display: flex"  rel="pop" data-content="
                    <span>ID: </span><?php  echo $row['id'];?> </br>

                            <span>状态:</span>  <?php  if($row['isblack']==1) { ?>黑名单<?php  } else { ?>正常<?php  } ?>
					   ">
                        <?php if(cv('member.list.view')) { ?>
                        <a href="<?php  echo webUrl('member/list/detail',array('id' => $row['id']));?>" title='查看会员信息' target='_blank' style="display: flex">
                            <?php  if(!empty($row['avatar'])) { ?>
                            <img class="radius50" src='<?php  echo tomedia($row['avatar'])?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' onerror="this.src='../addons/ewei_shopv2/static/images/noface.png'"/>
                            <?php  } ?>
                        <span style="display: flex;flex-direction: column;justify-content: center;align-items: flex-start;padding-left: 5px">
                            <span class="nickname">
                                <?php  if(strexists($row['openid'],'sns_wa')) { ?><i class="icow icow-xiaochengxu" style="color: #7586db;vertical-align: middle;" data-toggle="tooltip" data-placement="top" title="" data-original-title="来源: 小程序"></i><?php  } ?>
                                <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>
                            </span>
                        </span>
                        </a>
                        <?php  } else { ?>
                        <?php  if(!empty($row['avatar'])) { ?>
                        <img class="radius50" src='<?php  echo tomedia($row['avatar'])?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' onerror="this.src='../addons/ewei_shopv2/static/images/noface.png'"/>
                        <?php  } ?>
                        <span style="display: flex;flex-direction: column;justify-content: center;align-items: flex-start;padding-left: 5px">
                            <span class="nickname">
                                <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>
                            </span>
                        </span>

                        <?php  } ?>
                    </div>
                </td>

                <td><?php  echo $row['realname'];?> <br/> <?php  echo $row['mobile'];?></td>

             
                <td><span class="text-danger"><?php  echo $row['dividend_total'];?></span><br/><span class="text-warning"><?php  echo $row['dividend_pay'];?></span></td>
                <td >

                    <?php if(cv('dividend.agent.user')) { ?>
                    <a  href="<?php  echo webUrl('dividend/agent/user',array('id' => $row['id']));?>"  target='_blank' data-toggle='popover' data-placement='right' data-html="true" data-trigger='hover' data-content='查看下级团员'>
                        <?php  echo $row['groupscount'];?>
                    </a>
                    <?php  } else { ?>
                        <?php  echo $row['groupscount'];?>
                    <?php  } ?>
                        
                    </td>
                       <td>
                           <?php  echo date('Y-m-d',$row['createtime'])?> <?php  echo date('H:i',$row['createtime'])?> <br/>
                           <?php  if(!empty($row['headstime'])) { ?>
                           <?php  echo date('Y-m-d',$row['headstime'])?> <?php  echo date('H:i',$row['headstime'])?>
                           <?php  } else { ?>
                           -
                           <?php  } ?>
                       </td>

                <td>
             
                   
                    <span class='label <?php  if($row['headsstatus']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
										  <?php if(cv('dividend.agent.check')) { ?>
										  data-toggle='ajaxSwitch' 
                                                                                                                                                                                                          data-confirm ='确认要<?php  if($row['headsstatus']==1) { ?>取消审核<?php  } else { ?>审核通过<?php  } ?>?'
										  data-switch-value='<?php  echo $row['headsstatus'];?>'
										  data-switch-value0='0|未审核|label label-default|<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>1,'id'=>$row['id']))?>'
										  data-switch-value1='1|已审核|label label-primary|<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>0,'id'=>$row['id']))?>'
										  <?php  } ?>
										>
										  <?php  if($row['headsstatus']==1) { ?>已审核<?php  } else { ?>未审核<?php  } ?></span>

                     </td>

             
        
                <td  style="overflow:visible;">
                            <?php if(cv('order.list')) { ?>
                                <a class="btn  btn-op btn-operation" href="<?php  echo webUrl('order/list',array('headsid' => $row['id']));?>"  target='_blank'>
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="团员订单">
                                        <i class='icow icow-tuiguang'></i>
                                    </span>
                                </a>
                            <?php  } ?>
                            <?php if(cv('dividend.agent.delete')) { ?>
                                    <a class="btn  btn-op btn-operation" data-toggle='ajaxRemove' href="<?php  echo webUrl('dividend/agent/delete',array('id' => $row['id']));?>"  data-confirm="确定要删除该队长吗？">
                                       <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除队长">
                                               <i class='icow icow-shanchu1'></i>
                                            </span>
                                    </a>
                            <?php  } ?>
                            <?php if(cv('finance.recharge.credit1|finance.recharge.credit2')) { ?>
                                <a class="btn  btn-op btn-operation" data-toggle="ajaxModal" href="<?php  echo webUrl('finance/recharge', array('type'=>'credit1','id'=>$row['id']))?>" >
                                    <span data-toggle="tooltip" data-placement="top" title="" data-original-title="充值">
                                       <i class='icow icow-31'></i>
                                    </span>
                                </a>
                            <?php  } ?>
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td colspan="3">
                        <div class="btn-group">
                            <?php if(cv('dividend.agent.edit')) { ?>
                            <a class='btn btn-default btn-sm btn-op btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>1))?>"  data-confirm='确认要审核通过?'>
                                <i class="icow icow-shenhetongguo"></i>审核通过
                            </a>
                            <a class='btn btn-default btn-sm btn-op btn-operation'  data-toggle='batch' data-href="<?php  echo webUrl('dividend/agent/check',array('headsstatus'=>0))?>" data-confirm='确认要取消审核?'>
                                <i class="icow icow-yiquxiao"></i>取消审核</a>
                            <?php  } ?>
                            <?php if(cv('dividend.agent.delete')) { ?>
                            <a class="btn btn-default btn-sm btn-op btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要删除?" data-href="<?php  echo webUrl('dividend/agent/delete')?>">
                                <i class='icow icow-shanchu1'></i> 删除
                            </a>
                            <?php  } ?>
                        </div>
                    </td>
                    <td colspan="4" class="text-right"><?php  echo $pager;?></td>
                </tr>
            </tfoot>
        </table>
        <?php  } else { ?>
            <div class='panel panel-default'>
                <div class='panel-body' style='text-align: center;padding:30px;'>
                     暂时没有任何队长!
                </div>
            </div>
            <?php  } ?>
</div>
    <script language="javascript">
			  

 
			require(['bootstrap'],function(){
        $("[rel=pop]").popover({
            trigger:'manual',
            placement : 'right',
            title : $(this).data('title'),
            html: 'true', 
            content : $(this).data('content'),
            animation: false
        }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show"); 
                    $(this).siblings(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide")
                        }
                    }, 100);
                });
 
	 
	   });
 
			   
</script> 
 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>