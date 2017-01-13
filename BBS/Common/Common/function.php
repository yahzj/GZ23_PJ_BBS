<?php
//配合自动完成加密函数
function myHash($val){
	$hash=password_hash($val,PASSWORD_DEFAULT);
	return $hash;
}

//验证码检查
 function check_verify($code,$id=''){
          	$verify = new \Think\Verify();   
          	 return $verify->check($code, $id);
   }