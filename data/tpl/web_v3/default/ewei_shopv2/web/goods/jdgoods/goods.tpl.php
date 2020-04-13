<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="utf-8"/>
<style>
    tbody tr td{
        position: relative;
    }
    tbody tr  .icow-weibiaoti--{
        visibility: hidden;
        display: inline-block;
        color: #fff;
        height:18px;
        width:18px;
        background: #e0e0e0;
        text-align: center;
        line-height: 18px;
        vertical-align: middle;
    }
    tbody tr:hover .icow-weibiaoti--{
        visibility: visible;
    }
    tbody tr  .icow-weibiaoti--.hidden{
        visibility: hidden !important;
    }
    .full .icow-weibiaoti--{
        margin-left:10px;
    }
    .full>span{
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        vertical-align: middle;
        align-items: center;
    }
    tbody tr .label{
        margin: 5px 0;
    }
    .goods_attribute a{
        cursor: pointer;
    }
    .newgoodsflag{
        width: 22px;height: 16px;
        background-color: #ff0000;
        color: #fff;
        text-align: center;
        position: absolute;
        bottom: 70px;
        left: 57px;
        font-size: 12px;
    }
    .modal-dialog {
        min-width: 720px !important;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
    }
    .catetag{
        overflow:hidden;

        text-overflow:ellipsis;

        display:-webkit-box;

        -webkit-box-orient:vertical;

        -webkit-line-clamp:2;
    }
</style>
<div class="page-header">
    当前位置：<span class="text-primary">商品管理</span>
</div>
<div class="page-content">
    <div class="fixed-header">
        <div style="width:25px;"></div>
       
        <div style="width:80px;">商品</div>
        <div class="flex1" style="width:200px;">&nbsp;</div>
        <div style="width:100px;">分类</div>
        <div style="width:100px;">成本</div>
        <div style="width:100px;">京东价格</div>
        <div style="width:100px;">平台价格</div>
       <div style="width:100px;">销量</div>
       <div style="width:100px;">虚拟销量</div>
       <div  style="width:80px;">状态</div>
     
        <!--<div class="flex1">属性</div>-->
        <div style="width: 120px;">操作</div>
    </div>
    <form action="./index.php" method="get" class="form-horizontal form-search" role="form">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="goods.jdgoods.goods" />
        <div class="page-toolbar">
           
            <div class="input-group col-sm-6 pull-right">
                
                <span class="input-group-select">
                    <select name="onsale" class='form-control select2' style="width:200px;" data-placeholder="商品分类">
                        <option value="" <?php  if(empty($_GPC['onsale'])) { ?>selected<?php  } ?> >状态</option>
                      
                        <option value="1" <?php  if($_GPC['onsale']==1) { ?>selected<?php  } ?> >上架</option>
                         <option value="2" <?php  if($_GPC['onsale']==2) { ?>selected<?php  } ?> >下架</option>
                        
                    </select>
                </span>
                
                <span class="input-group-select">
                    <select name="cateid" class='form-control select2' style="width:200px;" data-placeholder="商品分类">
                        <option value="" <?php  if(empty($_GPC['cateid'])) { ?>selected<?php  } ?> >商品分类</option>
                        <?php  if(is_array($category)) { foreach($category as $c) { ?>
                        <option value="<?php  echo $c['id'];?>" <?php  if($_GPC['cateid']==$c['id']) { ?>selected<?php  } ?> ><?php  echo $c['catename'];?></option>
                        <?php  } } ?>
                    </select>
                </span>
                
                <input type="text" class="input-sm form-control" name='keyword' value="<?php  echo $_GPC['keyword'];?>" placeholder="ID/名称/编号">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"> 搜索</button>
                </span>
            </div>
        </div>
    </form>
    <?php  if(count($list)>0 && cv('goods.main')) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="page-table-header">
                <input type='checkbox' />
                <div class="btn-group">
                    
                    
                </div>
            </div>
            <table class="table table-responsive">
                <thead class="navbar-inner">
                <tr>
                    <th style="width:25px;"></th>
                   
                    <th style="width:80px;">商品</th>
                    <th style="">&nbsp;</th>
                    <th style="width: 100px;">分类</th>
                    <th style="width: 100px;">成本</th>
                    <th style="width: 100px;">京东价格</th>
                    <th style="width: 100px;">平台价格</th>
                   
                     <th style="width: 80px;">销量</th>
                    <th style="width: 80px;" >虚拟销量</th>
                    
                    <th  style="width:80px;" >状态</th>
                  
                   <!--  <th>属性</th>-->
                    <th style="width: 120px;">操作</th>
                </tr>

                </thead>
                <tbody>
                <?php  if(is_array($list)) { foreach($list as $item) { ?>
                <tr>
                    <td>
                        <input type='checkbox'  value="<?php  echo $item['id'];?>"/>
                    </td>
                   
                    <td>
                        <a href="<?php  echo webUrl('jdgoods/goods/edit', array('id' => $item['id'],'goodsfrom'=>$goodsfrom,'page'=>$page))?>">
                            <img src="<?php  echo $item['imagePath'];?>" style="width:72px;height:72px;padding:1px;border:1px solid #efefef;margin: 7px 0" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'" />
                        </a>
                        
                    </td>
                    <td class='full' >
                        <span>
                            <span style="display: block;width: 100%;">
                                    <?php  echo $item['name'];?>
                        </span>
                       
                        </span>
                    </td>
                    <td><?php  echo $item["cate_name"];?></td>
                   <td>
                   &yen;<?php  echo $item["price"];?>
                   </td>
                   <td>
                   &yen;<?php  echo $item["jdprice"];?>
                   </td>
                    <td>&yen;
                       
                        <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('goods/jdgoods/goods/change',array('type'=>'ptprice','id'=>$item['id']))?>" ><?php  echo $item['ptprice'];?></a>
                        <i class="icow icow-weibiaoti-- " data-toggle="ajaxEdit2"></i>
                       
                    </td>
                     <td>
                       
                      <?php  echo $item["sale"];?><?php  echo $item["saleUnit"];?>
                    </td>
                     <td>
                     <a href='javascript:;' data-toggle='ajaxEdit' data-href="<?php  echo webUrl('goods/jdgoods/goods/change',array('type'=>'virtual_sales','id'=>$item['id']))?>" ><?php  echo $item['virtual_sales'];?></a>
                        <i class="icow icow-weibiaoti-- " data-toggle="ajaxEdit2"></i>
                       
                     </td>
                     
                    <td  style="overflow:visible;">
                       
                        <span class='label <?php  if($item['onsale']==1) { ?>label-primary<?php  } else { ?>label-default<?php  } ?>'
                        
                        data-toggle='ajaxSwitch'
                        data-confirm = "确认是<?php  if($item['onsale']==1) { ?>下架<?php  } else { ?>上架<?php  } ?>？"
                        data-switch-refresh='true'
                        data-switch-value='<?php  echo $item['onsale'];?>'
                        data-switch-value0='0|下架|label label-default|<?php  echo webUrl('goods/jdgoods/goods/status',array('status'=>1,'id'=>$item['id']))?>'
                        data-switch-value1='1|上架|label label-success|<?php  echo webUrl('goods/jdgoods/goods/status',array('status'=>0,'id'=>$item['id']))?>'>
                        <?php  if($item['onsale']==1) { ?>上架<?php  } else { ?>下架<?php  } ?></span>
                        
                        <br>
                       
                    </td>
                     
                 
                   <td  style="overflow:visible;position:relative">
                       
                        <a  class='btn btn-op btn-operation' href="<?php  echo webUrl('goods/jdgoods/goods/edit', array('id' => $item['id'],'page'=>$page))?>" >
                                         <span data-toggle="tooltip" data-placement="top" title="" data-original-title="设置分类">
                                            <i class="icow icow-bianji2"></i>
                                         </span>
                        </a>
                       
                      
                        <a  class='btn  btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('goods/jdgoods/goods/delete', array('id' => $item['id']))?>" data-confirm='确认彻底删除此商品？'>
                                <span data-toggle="tooltip" data-placement="top" title="" data-original-title="删除">
                                     <i class='icow icow-shanchu1'></i>
                                </span>
                        </a>
                       
                      
                    </td>
                    
                </tr>
                
                <?php  } } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td    <?php  if($goodsfrom!='cycle') { ?>colspan="4"<?php  } else { ?>colspan="3" <?php  } ?>>
                    <div class="btn-group">
                        
                      
                        <button class="btn btn-default btn-sm  btn-operation" type="button" data-toggle='batch-remove' data-confirm="确认要彻底删除吗?" data-href="<?php  echo webUrl('goods/jdgoods/goods/delete')?>">
                         <i class='icow icow-shanchu1'></i> 彻底删除</button>
                        
                    </div>
                    </td>
                    <td colspan="5" style="text-align: right">
                        <?php  echo $pager;?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php  } else { ?>
    <div class="panel panel-default">
        <div class="panel-body empty-data">暂时没有任何商品!</div>
    </div>
    <?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/batchcates', TEMPLATE_INCLUDEPATH)) : (include template('goods/batchcates', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<script>
    //获得分类标签
    // var length = $('#catetag').children().length;
    // if (length >10){
    //     for (var i=2;i<length;i++)
    //     {
    //         $('#catetag').children().eq(i).hide();
    //     }
    //     $('#catetag').append('...等');
    // }
    //显示批量分类
    $('#batchcatesbut').click(function () {
        $('#batchcates').show();
    })

    //关闭批量分类
    $('.modal-header .close').click(function () {
        $('#batchcates').hide();
    })

    // 取消批量分类
    $('.modal-footer .btn.btn-default').click(function () {
        $('#batchcates').hide();
    })


    //确认
    $('.modal-footer .btn.btn-primary').click(function () {
        var selected_checkboxs = $('.table-responsive tbody tr td:first-child [type="checkbox"]:checked');
        var goodsids = selected_checkboxs.map(function () {
            return $(this).val()
        }).get();

        var cates=$('#cates').val();
        var iscover=$('input[name="iscover"]:checked').val();
        $.post(biz.url('goods/ajax_batchcates'),{'goodsids':goodsids,'cates': cates,'iscover':iscover}, function (ret) {
            if (ret.status == 1) {
                $('#batchcates').hide();
                tip.msgbox.suc('修改成功');
                window.location.reload();
                return
            } else {
                tip.msgbox.err('修改失败');
            }
        }, 'json');
    })

    $(document).on("click", '[data-toggle="ajaxEdit2"]',
        function (e) {
            var _this = $(this)
            $(this).addClass('hidden')
            var obj = $(this).parent().find('a'),
                url = obj.data('href') || obj.attr('href'),
                data = obj.data('set') || {},
                html = $.trim(obj.text()),
                required = obj.data('required') || true,
                edit = obj.data('edit') || 'input';
            var oldval = $.trim($(this).text());
            e.preventDefault();

            submit = function () {
                e.preventDefault();
                var val = $.trim(input.val());
                if (required) {
                    if (val == '') {
                        tip.msgbox.err(tip.lang.empty);
                        return;
                    }
                }
                if (val == html) {
                    input.remove(), obj.html(val).show();
                    //obj.closest('tr').find('.icow').css({visibility:'visible'})
                    return;
                }
                if (url) {
                    $.post(url, {
                        value: val
                    }, function (ret) {

                        ret = eval("(" + ret + ")");
                        if (ret.status == 1) {
                            obj.html(val).show();

                        } else {
                            tip.msgbox.err(ret.result.message, ret.result.url);
                        }
                        input.remove();
                    }).fail(function () {
                        input.remove(), tip.msgbox.err(tip.lang.exception);
                    });
                } else {
                    input.remove();
                    obj.html(val).show();
                }
                obj.trigger('valueChange', [val, oldval]);
            },
                obj.hide().html('<i class="fa fa-spinner fa-spin"></i>');
            var input = $('<input type="text" class="form-control input-sm" style="width: 80%;display: inline;" />');
            if (edit == 'textarea') {
                input = $('<textarea type="text" class="form-control" style="resize:none;" rows=3 width="100%" ></textarea>');
            }
            obj.after(input);

            input.val(html).select().blur(function () {
                submit(input);
                _this.removeClass('hidden')

            }).keypress(function (e) {
                if (e.which == 13) {
                    submit(input);
                    _this.removeClass('hidden')
                }
            });

        })
</script>
