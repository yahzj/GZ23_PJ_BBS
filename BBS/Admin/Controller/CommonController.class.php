<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	public function _initialize(){
		if(empty(session('mybbs'))){
	    $this->success('您尚未登陆',U('Admin/Login/login'));
	    }elseif (time()-session('up_datetime')>1800) {
	    	$this->success('登陆超时，请重新登陆',U('Admin/Login/login'));
	    }elseif (session('login')!='admin') {
	    	$this->success('您没有登陆后台的权限',U('Admin/Login/login'));
	    }
	    session('up_datetime',time());
	}
}