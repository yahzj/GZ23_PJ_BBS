<?php
namespace Home\Controller;//代码编辑:刘欣;
use Think\Controller;

class SubjectController extends EmptyController{
	public function index(){
		//echo'我是你大哥';
		$subject=D('Subject');
		$data=$subject->pro_index();
		$this->assign($data);
		$this->display();

	}
	

}