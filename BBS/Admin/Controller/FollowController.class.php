<?php
namespace Admin\Controller;
use Think\Controller;
class FollowController extends EmptyController {
    
		// 显示后台主页
	    public function index(){
	    	$this->display();
	    }

	    // 欢迎页
	    public function welcome(){
	    	$this->assign('title','客官，看中了几号？'); 

	    	$this->display();
	    }
}