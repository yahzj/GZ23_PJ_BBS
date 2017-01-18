<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	public function _initialize(){
	    $list=I('session.mybbs');
		if(empty(session('mybbs'))){
	    $this->redirect('Login/login','',5,'您尚未登陆,正在为您跳转回登陆界面...');
	    }elseif (time()-$list['landtime']>1800) {
	    	$this->redirect('Login/login','',5,'登陆超时,正在为您跳转回登陆界面...');
	    }elseif ($list['login']!='admin') {
	    	$this->redirect('Login/login','',5,'很抱歉，您没有后台登陆权限,正在为您跳转回登陆界面...');
	    }
	    $list['landtime']=time();
	     session('mybbs', $list); 
	}
}