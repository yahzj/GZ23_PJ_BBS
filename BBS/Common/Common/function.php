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

function land_user(){
	    $list=I('session.mybbs_home');
		if(empty(session('mybbs_home'))){
	    return false;
	    }elseif (time()-$list['landtime']>1800) {
	    	session(null);
	    return false;
	    }elseif ($list['login']!='home') {
	    	session(null);
	    return false;
	    }
	    $list['landtime']=time();
	     session('mybbs_home', $list); 
	    return true;
}
