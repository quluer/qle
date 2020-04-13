<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="UTF-8">
<div class="page-header">当前位置：<span class="text-primary">助力海报</span></div>

<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal  ">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="sysset.share_help" />

        <div class="page-toolbar row m-b-sm m-t-sm">
            <div class="col-md-4">
                <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('sysset/addshare')?>"><i class='fa fa-plus'></i> 添加</a>
                
            </div>

        </div>
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
                        <th style='width:50px'>ID</th>
                         <th class='th-sortable' data-sort-name='title'>标题</th>
                        <th class='th-sortable' data-sort-name='title'>banner</th>
                       
                         <th class='th-sortable' data-sort-name='title'>缩略图</th>
                      
                        <th style="width: 105px;">操作</th>
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
                             <td><?php  echo $row['title'];?></td>
                            <td><img src="<?php  echo $row['thumb'];?>" style="width:50px;height:50px;"></td>
                           
                            <td><img src="<?php  echo $row['image'];?>" style="width:50px;height:50px;"></td>
                           
                            <td style="text-align:left;">
                               
                                   
                                    <a href="<?php  echo webUrl('sysset/editshare',array('id' => $row['id']))?>" class="btn btn-op btn-operation" >
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="修改">
                                                <i class='icow icow-bianji2'></i>
                                            </span>
                                    </a>
                                   
                                    <a data-toggle='ajaxRemove' href="<?php  echo webUrl('sysset/deleteshare', array('id' => $row['id']))?>"class="btn btn-op btn-operation" data-confirm='确认要删除此信息吗?'>
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
                        
                        <td colspan="6" style="text-align: right">
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