<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary"><?php  if(!empty($notice['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>公告<?php  if(!empty($notice['id'])) { ?>(<?php  echo $notice['title'];?>)<?php  } ?></span>
</div>

<div class="page-content">
    <div class="page-sub-toolbar">
        <span class=''>
            <?php if(cv('shop.notice.add')) { ?>
                <a class="btn btn-primary btn-sm" href="<?php  echo webUrl('shop/notice/add')?>">添加新公告</a>
            <?php  } ?>
        </span>
    </div>
    <form <?php if( ce('shop.notice' ,$notice) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate" enctype="multipart/form-data" onsubmit='return formcheck()'>
        <input type="hidden" name="id" value="<?php  echo $notice['id'];?>" />
        <div class="form-group">
            <label class="col-lg control-label">排序</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <input type="text" name="displayorder" class="form-control" value="<?php  echo $notice['displayorder'];?>" />
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $notice['displayorder'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label must">公告标题</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <input type="text" id='title' name="title" class="form-control" value="<?php  echo $notice['title'];?>" data-rule-required='true' />
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $notice['title'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">公告图片</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <?php  echo tpl_form_field_image2('thumb', $notice['thumb'])?>
                    <span class="help-block">正方型图片</span>
                <?php  } else { ?>
                    <?php  if(!empty($notice['thumb'])) { ?>
                        <a href='<?php  echo tomedia($notice['thumb'])?>' target='_blank'>
                        <img src="<?php  echo tomedia($notice['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                    <?php  } ?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">公告链接</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <div class="input-group form-group" style="margin: 0;">
                        <input type="text" name="link" class="form-control" value="<?php  echo $notice['link'];?>" id="noticelink" />
                        <span class="input-group-btn">
                            <span data-input="#noticelink" data-toggle="selectUrl" class="btn btn-default">选择链接</span>
                        </span>
                    </div>
                    <span class="help-block">如果输入链接，则不显示内容，直接跳转</span>
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $notice['link'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">公告内容</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <?php  echo tpl_ueditor('detail',$notice['detail'])?>
                <?php  } else { ?>
                    <textarea id='detail' style='display:none'><?php  echo $notice['detail'];?></textarea>
                    <a href='javascript:preview_html("#detail")' class="btn btn-default">查看内容</a>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg control-label">是否显示</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <label class='radio-inline'>
                        <input type='radio' name='status' value=1' <?php  if($notice['status']==1) { ?>checked<?php  } ?> /> 是
                    </label>
                    <label class='radio-inline'>
                        <input type='radio' name='status' value=0' <?php  if($notice['status']==0) { ?>checked<?php  } ?> /> 否
                    </label>
                <?php  } else { ?>
                    <div class='form-control-static'><?php  if(empty($notice['status'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.notice' ,$notice) ) { ?>
                    <input type="submit"  value="提交" class="btn btn-primary"  />
                <?php  } ?>
                <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.notice.add|shop.notice.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
            </div>
        </div>
    </form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--NDAwMDA5NzgyNw==-->