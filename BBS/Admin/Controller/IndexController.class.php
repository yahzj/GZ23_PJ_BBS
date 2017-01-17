<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends EmptyController {
    
		// 显示后台主页
	    public function index(){
	    	$this->display();
	    }

	    // 欢迎页
	    public function welcome(){
	    	$everyday=D('everyday');
	    	$data['list']=$everyday->pro_select();
	    	$data['session']=I('session.');
	    	// echo '<pre>';
	    	// dump($data);
	    	$this->assign($data);
	    	$this->display();
	    }
}