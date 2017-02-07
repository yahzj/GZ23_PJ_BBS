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

//该用户是否有昵称，有才能发，没有就返回告知用户！适用于发帖，回帖，发消息等输入操作
function check(){
	$users=D('users');
	$id=$_SESSION['mybbs_home'][0]['id'];
	$checkarr['id']=['eq',$id];
	$checkarr['nickname']=['neq','null'];
	//找到该用户昵称不为空的数据，昵称为空就没有数据。
	$checklist=$users->where('id='.$id.' and nickname is not null')->select();
	if(!empty($checklist[0])){
		
	}else{
		//返回9到控制器，然后告诉用户没有昵称，不能发。
		return 9;
	}
}

//自动验证每日统计的日期
function Everyday(){
    $Everyday=D('admin/Everyday');
    $map['to_date']=date('Y-m-d');
    $data=$Everyday->where($map)->select();
    if (empty($data)) {
        $to_date['to_date']=date('Y-m-d');
        $Everyday->add($to_date);
        return ['daily_visitors'=>'0','member_num'=>'0','subject_num'=>'0','follow_num'=>'0','registered_users'=>'0'];
    }else{
    	return $data;
    }
}
