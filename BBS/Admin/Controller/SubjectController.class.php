<?php
namespace Admin\Controller;
use Think\Controller;

class SubjectController extends EmptyController{
	//查询
	public function index(){

		$User=D('subject');

		$data=$User->pro_index();

		$this->assign($data);

		$this->display();
	}
	//添加
	public function add(){

		if ($_POST['username']) {
			$User = D("subject"); // 实例化对象
			// 根据表单提交的POST数据创建数据对象
			$User->pro_add();
		}else{
			$this->display();
		}
		
	}

	//删除
	public function delete(){
		$obj=D('subject');

		$data=$obj->pro_delete();
	}

	//修改
	public function edit(){
		$obj=D('subject');

		$data=$obj->pro_index();

		$this->assign($data);

		$this->display();
	}
}