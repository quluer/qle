<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
 <meta charset="utf-8">
<div class="page-header">
    当前位置：<span class="text-primary">软件许可及服务协议</span>
</div>

<div class="page-content">
    <div class="page-sub-toolbar">
        
    </div>
    <form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data" onsubmit='return formcheck()'>
        
      
       <div class="form-group">
            <label class="col-lg control-label">软件许可及服务协议</label>
            <div class="col-sm-9 col-xs-12">
                <?php  echo tpl_ueditor('detail',$notice['content'])?>
              
            </div>
        </div>


       
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9 col-xs-12">
               
                    <input type="submit"  value="提交" class="btn btn-primary"  />
                
            </div>
        </div>
    </form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--NDAwMDA5NzgyNw==-->