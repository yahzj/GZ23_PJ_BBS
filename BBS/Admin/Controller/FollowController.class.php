<?php
namespace Admin\Controller;
use Think\Controller;
class FollowController extends EmptyController {
    
		// 显示后台主页
	    public function index(){
	    	$follow=D("follow");
	    	$data=$follow->pro_index();
	    	dump($data);
	    	$this->assign("title","what");
	    	$this->assign($data);
	    	$this->display();
	    }

}