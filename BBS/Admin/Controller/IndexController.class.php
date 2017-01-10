<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    
		// 显示后台主页
	    public function index(){
	    	$this->display();
	    }

	    // 欢迎页
	    public function welcome(){
	    	$everyday=D('everyday');
	    	$data['list']=$everyday->pro_select();
	    	// echo '<pre>';
	    	// var_dump($data);
	    	$this->assign($data);
	    	$this->display();
	    }
}