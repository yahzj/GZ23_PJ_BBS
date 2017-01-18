<?php
namespace Home\Controller;//代码编辑:刘欣;
use Think\Controller;

class UsersController extends EmptyController{
	public function index(){
		$user=D('users');//实例化类
		$list=$user->pro_index();//接收数据处理
		$data=[];
		$data['list']=$list;
		$this->assign($data);//分配数局
		$this->display();//显示模板
	}
	public function upload(){
		$user=D('users');//实例化类
		$list=$user->pro_index();//接收数据处理
		$data=[];
		$data['list']=$list;
		$this->assign($data);//分配数据
		$this->display();//显示模板
	}
	public function doUpload(){
		$user=D('users');//实例化类
		$list=$user->pro_index();//接收数据处理
		$data=[];
		$data['list']=$list;
		$this->assign($data);//分配数局
		$this->display();//显示模板
	}
	public function add(){
		
		$this->display();//显示模板
	}

}