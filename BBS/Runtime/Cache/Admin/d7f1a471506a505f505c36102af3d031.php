<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理员注册</title>
<style type='text/css'>
    .input_from{
        width:300px;
    }
</style>
<script type="text/javascript" src="/GZ23_PJ_BBS/Public/js/jquery.js"></script>

<link rel="stylesheet" href="/GZ23_PJ_BBS/Public/css/add.css" type="text/css" media="screen">
<link rel="stylesheet" href="/GZ23_PJ_BBS/Public/css/bootstrap.css" type="text/css" media="screen">

</head>
<body>
<center>
<h1>管理员注册</h1>
<div class="div_from_aoto" style="width: 500px;">
    <!-- <form action="<?php echo U('Home/User/doedit');?>"  method="post"> -->
    <form action="<?php echo U('Admin/Users/doadd');?>"  method="post" enctype="multipart/form-data">
      

        <div class="control-group">
            <label class="laber_from">账号</label>
            <div class="controls"><input  class="input_from"  placeholder=" 请输入纯数字字符" type="text" name='username'><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">密码</label>
            <div class="controls"><input class="input_from" placeholder=" 请输入至少六位的包含数字，字母的字符组合" type="password" name='userpass'><p class="help-block"></p></div>
        </div>
        <div class="control-group">
            <label class="laber_from">确认密码</label>
            <div class="controls"><input name="reuserpass" class="input_from" placeholder=" 请输入确认密码" type="password"><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">昵称</label>
            <div class="controls"><input name="nickname"  class="input_from" placeholder=" 请输入昵称" type="text"><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">头像上传</label>
            <div class="controls"><input name="image"  class="input_from" placeholder=" 请输入确认密码" type="file"><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">邮箱</label>
            <div class="controls"><input name="email"  class="input_from" placeholder=" 请输入正确的邮箱地址" type="text"><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">个性签名</label>
            <div class="controls"><input name="sign"  class="input_from" placeholder="来点不一样的个性签名" type="text"><p class="help-block"></p></div>
        </div>

        <div class="control-group">
            <label class="laber_from">性别</label>
            <div class="controls">
                <select name="sex" class="input_select">
                    <option value="1" >男</option>
                    <option value="0" selected>女</option>
                    
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="laber_from">地址</label>
            <div class="controls">
               <input type="text" placeholder="请输入真实的地址" name="address" id="" style='width:300px' />
            </div>
        </div>


        
        <div class="control-group">
            <label class="laber_from"></label>
            <div class="controls">
                <button class="btn btn-success" style="width:120px;">确认</button>
                <input type="reset" value="清空" class="btn btn-info" style="width:120px;"/>
            </div>
        </div>
    </form>
</div>

</body></html>