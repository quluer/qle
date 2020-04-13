<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="UTF-8">
<div class="page-header">当前位置：<span class="text-primary">热点关注管理</span></div>

<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal  ">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="sysset.question" />

        
    </form>

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
                       <th class='th-sortable' data-sort-name='title'>提问者</th>
                        <th class='th-sortable' data-sort-name='title'>问题描述</th>
                          <th class='th-sortable' data-sort-name='title'>故障时间</th>
                      <th class='th-sortable' data-sort-name='title'>状态</th>
                        <th style="width: 65px;">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                        <tr>
                            <td>
                                <input type='checkbox'   value="<?php  echo $row['id'];?>"/>
                            </td>
                            <td><?php  echo $row['nickname'];?></td>
                            <td><?php  echo $row['content'];?></td>
                            <td><?php  echo $row['time'];?></td>
                             <td>
                             <?php  if($row["answer"]=="") { ?>
                             待处理
                             <?php  } else { ?>已处理
                             <?php  } ?>
                             </td>
                            <td style="text-align:left;">
                               
                                   
                                    <a href="<?php  echo webUrl('sysset/question/detail',array('id' => $row['id']))?>" class="btn btn-op btn-operation" >
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="详情">
                                                <i class='icow icow-bianji2'></i>
                                            </span>
                                    </a>
                                    
                                    
                                    <a data-toggle='ajaxRemove' href="<?php  echo webUrl('sysset/question/delete', array('id' => $row['id']))?>"class="btn btn-op btn-operation" data-confirm='确认要删除此信息吗?'>
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="删除">
                                               <i class='icow icow-shanchu1'></i>
                                            </span>
                                    </a>
                               
                            </td>
                        </tr>
                    <?php  } } ?>
                </tbody>
                <tfoot>
                    <tr>
                        
                        <td colspan="3" style="text-align: right">
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