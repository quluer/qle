<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="utf-8"/>
<div class="page-header">当前位置：<span class="text-primary">达人中心</span></div>

    <div class="page-content">
        <form action="" method="post" class="form-horizontal form-validate" enctype="multipart/form-data" >
           
            <div class="form-group">
                <label class="col-lg control-label">“尊敬的达人，您好”，背景图</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('backgroup', $data['backgroup'])?>
                   
                </div>
            </div>
            
             <div class="form-group">
                <label class="col-lg control-label">达人特权banner</label>
                <div class="col-sm-9 col-xs-12">
                  
                    <?php  echo tpl_form_field_image2('banner', $data['banner'])?>
                   
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-lg control-label">栏目ICON-1</label>
                <div class="col-sm-9 col-xs-12">
                    
    <img src="<?php  echo tomedia($data['icon'][0]['img'])?>" id="icon1_img" style="width:100px;height:100px">
     <input name="icon1_img" value="<?php  echo $data['icon'][0]['img'];?>" id="icon1_value" type="hidden">
     <div style="margin-top:20px;margin-left:5%;">
     <input type="file" name="file" id="file" onchange="fileUpload()" style="display:none">
     <input type="button"  onclick="select_file()" value="上传"  style="background: #44abf7 !important; border-color: #44abf7 !important;color: #fff !important;width:150px;height:30px;">
     </div>
     <!-- 
                   标题：<input type="text" name="icon1_title" class="form-control" value="<?php  echo $data['icon'][0]['title'];?>" />
          链接：<input type="text" name="icon1_url" class="form-control" value="<?php  echo $data['icon'][0]['url'];?>" />
       -->
                </div>
            </div>
            
            
            
<script>

function select_file(){
    $("#file").trigger("click");
}

function fileUpload(){
    var formData = new FormData();
    formData.append('file', $('#file')[0].files[0]);
    $.ajax({
                url : "<?php  echo $submitUrl;?>",//这里写你的url
                type : 'POST',
                data : formData,
                contentType: false,// 当有文件要上传时，此项是必须的，否则后台无法识别文件流的起始位置
                processData: false,// 是否序列化data属性，默认true(注意：false时type必须是post)
                dataType: 'json',//这里是返回类型，一般是json,text等
                clearForm: true,//提交后是否清空表单数据
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                	console.log(data);
                    console.log(data.status);
                    var video=data.message;
                    
                    if(data.status==0){
                    	//添加数据库
                    	
                    				var _videoPlay = document.getElementById("icon1_img");
                    				 
                    				_videoPlay.src = data.addr;
                    				document.getElementById("icon1_value").value=data.message
                    				
                    	//alert('上传成功');
                    }else{
                    	alert(data.message);
                    }
                }
            });
}


</script>
            
            
            
            <div class="form-group">
                <label class="col-lg control-label">栏目ICON-2</label>
                <div class="col-sm-9 col-xs-12">
                    
    <img src="<?php  echo tomedia($data['icon'][1]['img'])?>" id="icon2_img" style="width:100px;height:100px">
     <input name="icon2_img" value="<?php  echo $data['icon'][1]['img'];?>" id="icon2_value" type="hidden">
     <div style="margin-top:20px;margin-left:5%;">
     <input type="file" name="file" id="file2" onchange="fileUpload2()" style="display:none">
     <input type="button"  onclick="select_file2()" value="上传"  style="background: #44abf7 !important; border-color: #44abf7 !important;color: #fff !important;width:150px;height:30px;">
     </div>
     <!-- 
                   标题：<input type="text" name="icon2_title" class="form-control" value="<?php  echo $data['icon'][1]['title'];?>" />
          链接：<input type="text" name="icon2_url" class="form-control" value="<?php  echo $data['icon'][1]['url'];?>" />
       -->
                </div>
            </div>
            
            
            
<script>

function select_file2(){
    $("#file2").trigger("click");
}

function fileUpload2(){
    var formData = new FormData();
    formData.append('file', $('#file2')[0].files[0]);
    $.ajax({
                url : "<?php  echo $submitUrl;?>",//这里写你的url
                type : 'POST',
                data : formData,
                contentType: false,// 当有文件要上传时，此项是必须的，否则后台无法识别文件流的起始位置
                processData: false,// 是否序列化data属性，默认true(注意：false时type必须是post)
                dataType: 'json',//这里是返回类型，一般是json,text等
                clearForm: true,//提交后是否清空表单数据
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                	console.log(data);
                    console.log(data.status);
                    var video=data.message;
                    
                    if(data.status==0){
                    	//添加数据库
                    	
                    				var _videoPlay = document.getElementById("icon2_img");
                    				 
                    				_videoPlay.src = data.addr;
                    				document.getElementById("icon2_value").value=data.message
                    				
                    	//alert('上传成功');
                    }else{
                    	alert(data.message);
                    }
                }
            });
}


</script>
            
            
            <div class="form-group">
                <label class="col-lg control-label">栏目ICON-3</label>
                <div class="col-sm-9 col-xs-12">
                    
    <img src="<?php  echo tomedia($data['icon'][2]['img'])?>" id="icon3_img" style="width:100px;height:100px">
     <input name="icon3_img" value="<?php  echo $data['icon'][2]['img'];?>" id="icon3_value" type="hidden">
     <div style="margin-top:20px;margin-left:5%;">
     <input type="file" name="file" id="file3" onchange="fileUpload3()" style="display:none">
     <input type="button"  onclick="select_file3()" value="上传"  style="background: #44abf7 !important; border-color: #44abf7 !important;color: #fff !important;width:150px;height:30px;">
     </div>
     <!-- 
                   标题：<input type="text" name="icon3_title" class="form-control" value="<?php  echo $data['icon'][2]['title'];?>" />
          链接：<input type="text" name="icon3_url" class="form-control" value="<?php  echo $data['icon'][2]['url'];?>" />
       -->
                </div>
            </div>
            
            
            
<script>

function select_file3(){
    $("#file3").trigger("click");
}

function fileUpload3(){
    var formData = new FormData();
    formData.append('file', $('#file3')[0].files[0]);
    $.ajax({
                url : "<?php  echo $submitUrl;?>",//这里写你的url
                type : 'POST',
                data : formData,
                contentType: false,// 当有文件要上传时，此项是必须的，否则后台无法识别文件流的起始位置
                processData: false,// 是否序列化data属性，默认true(注意：false时type必须是post)
                dataType: 'json',//这里是返回类型，一般是json,text等
                clearForm: true,//提交后是否清空表单数据
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                	console.log(data);
                    console.log(data.status);
                    var video=data.message;
                    
                    if(data.status==0){
                    	//添加数据库
                    	
                    				var _videoPlay = document.getElementById("icon3_img");
                    				 
                    				_videoPlay.src = data.addr;
                    				document.getElementById("icon3_value").value=data.message
                    				
                    	//alert('上传成功');
                    }else{
                    	alert(data.message);
                    }
                }
            });
}


</script>
            
            
            <div class="form-group">
                <label class="col-lg control-label">栏目ICON-4</label>
                <div class="col-sm-9 col-xs-12">
                    
    <img src="<?php  echo tomedia($data['icon'][3]['img'])?>" id="icon4_img" style="width:100px;height:100px">
     <input name="icon4_img" value="<?php  echo $data['icon'][3]['img'];?>" id="icon4_value" type="hidden">
     <div style="margin-top:20px;margin-left:5%;">
     <input type="file" name="file" id="file4" onchange="fileUpload4()" style="display:none">
     <input type="button"  onclick="select_file4()" value="上传"  style="background: #44abf7 !important; border-color: #44abf7 !important;color: #fff !important;width:150px;height:30px;">
     </div>
     <!--
                   标题：<input type="text" name="icon4_title" class="form-control" value="<?php  echo $data['icon'][3]['title'];?>" />
          链接：<input type="text" name="icon4_url" class="form-control" value="<?php  echo $data['icon'][3]['url'];?>" />
        -->
                </div>
            </div>
            
            
            
<script>

function select_file4(){
    $("#file4").trigger("click");
}

function fileUpload4(){
    var formData = new FormData();
    formData.append('file', $('#file4')[0].files[0]);
    $.ajax({
                url : "<?php  echo $submitUrl;?>",//这里写你的url
                type : 'POST',
                data : formData,
                contentType: false,// 当有文件要上传时，此项是必须的，否则后台无法识别文件流的起始位置
                processData: false,// 是否序列化data属性，默认true(注意：false时type必须是post)
                dataType: 'json',//这里是返回类型，一般是json,text等
                clearForm: true,//提交后是否清空表单数据
                success: function(data) {   //提交成功后自动执行的处理函数，参数data就是服务器返回的数据。
                	console.log(data);
                    console.log(data.status);
                    var video=data.message;
                    
                    if(data.status==0){
                    	//添加数据库
                    	
                    				var _videoPlay = document.getElementById("icon4_img");
                    				 
                    				_videoPlay.src = data.addr;
                    				document.getElementById("icon4_value").value=data.message
                    				
                    	//alert('上传成功');
                    }else{
                    	alert(data.message);
                    }
                }
            });
}


</script>
            
           
            <div class="form-group">
                <label class="col-lg control-label"></label>
                <div class="col-sm-9 col-xs-12">
                  
                    <input type="submit" value="提交" class="btn btn-primary"  />
                  
                </div>
            </div>
        </form>
    </div>
 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>