<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
	// public function _initialize(){
	//                   if(empty(session('mybbs_home'))){
	// 	    $this->success('您尚未登陆',U('Home/Login/login'));
	// 	    }elseif (time()-session('addtime')>1800) {
	// 	    	$this->success('登陆超时，请重新登陆',U('Home/Login/login'));
	// 	    }elseif (session('login')!='home') {
	// 	    	$this->success('您没有登陆后台的权限',U('Home/Login/login'));
	// 	    }
	// 	    session('addtime',time());
	// }
}