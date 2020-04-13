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
                        <th style="width:25px;" ></th>
                        <th style='width:50px'>id</th>
                        <th style='width:50px'>用户</th>
                        <th style='width:50px'>订单金额</th>
                      <th style='width:50px'>加速日期</th>
                      <th style='width:50px'>每天可兑换</th>
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
                            <td><?php  echo $row['nickname'];?></td>
                            <td><?php  echo $row["money"];?></td>
                           <td><?php  echo $row["accelerate_day"];?></td>
                           <td><?php  echo $row["duihuan"];?></td>
                           <td><?php  echo $row["create_time"];?></td>
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