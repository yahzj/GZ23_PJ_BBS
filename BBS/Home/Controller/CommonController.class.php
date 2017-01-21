<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends EmptyController{
	public function _initialize(){
		    $list=I('session.mybbs_home');
	        if(empty(session('mybbs_home'))){
		    $this->error('您尚未登陆',U('Home/Login/login'));
		    }elseif (time()-$list['landtime']>1800) {
		    	$this->error('登陆超时，请重新登陆',U('Home/Login/login'));
		    }elseif ($list['login']!='home') {
		    	$this->error('您没有登陆前台的权限',U('Home/Login/login'));
		    }
		    $list['landtime']=time();
	        session('mybbs_home', $list); 
	}
}