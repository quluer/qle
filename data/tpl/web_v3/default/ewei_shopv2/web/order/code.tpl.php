<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="UTF-8">
<div class="page-header">当前位置：<span class="text-primary">加速宝订单</span></div>

<div class="page-content">
    
    <?php  if(empty($list)) { ?>
        <div class="panel panel-default">
            <div class="panel-body empty-data">未查询到相关数据</div>
        </div>
    <?php  } else { ?>
        <form action="" method="post">
           
            <table class="table table-responsive table-hover" >
                <thead class="navbar-inner">
                    <tr>
                        <th style="width:6px;" ></th>
                        <th style='width:18px'>id</th>
                        <th style='width:36px'>支付用户信息</th>
                        <th style='width:66px'>订单号</th>
                        <th style='width:28px'>订单金额</th>
                        <th style='width:66px'>收款人信息</th>
                        <th style='width:50px'>支付时间</th>
                        <th style='width:36px'>交易类型</th>
                        <th style='width:50px'>时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                        <tr>
                            <td>
                                <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                            </td>
                            <td>
                                <?php  echo $row['id'];?>
                            </td>
                            <td data-toggle='popover' data-html='true' data-placement='top' data-trigger='hover' data-content='<?php  echo $row['nickname'];?><br/><?php  echo $row['mobile'];?>'><?php  echo $row['nickname'];?><br/><?php  echo $row['mobile'];?></td>
                            <td data-toggle='popover' data-html='true' data-placement='top' data-trigger='hover' data-content='<?php  echo $row['ordersn'];?>'><?php  echo $row["ordersn"];?></td>
                           <td><?php  echo $row["price"];?></td>
                           <td data-toggle='popover' data-html='true' data-placement='top' data-trigger='hover' data-content='店铺名/个人收款人昵称：<?php  echo $row['merch']["merchname"];?><br/>真实姓名：<?php  echo $row['merch']['realname'];?><br/>电话：<?php  echo $row['merch']['mobile'];?>'>店铺名/个人收款人昵称：<?php  echo $row['merch']["merchname"];?><br/>真实姓名：<?php  echo $row['merch']['realname'];?><br/>电话：<?php  echo $row['merch']['mobile'];?></td>
                           <td><?php  echo $row["finishtime"];?></td>
                           <td data-toggle='popover' data-html='true' data-placement='top' data-trigger='hover' data-content='<?php  if($row['merch_type'] == 1) { ?>商家收款码<?php  } else { ?>个人收款码<?php  } ?>'><?php  if($row['merch_type'] == 1) { ?>商家收款码<?php  } else { ?>个人收款码<?php  } ?></td>
                           <td><?php  echo $row["createtime"];?></td>
                        </tr>
                    <?php  } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        
                        <td colspan="7" style="text-align: right">
                            <?php  echo $pager;?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    <?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--NDAwMDA5NzgyNw==-->