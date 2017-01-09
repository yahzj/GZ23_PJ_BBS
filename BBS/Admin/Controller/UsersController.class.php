<?php
namespace Admin\Controller;//代码编辑:刘欣;
use Think\Controller;

class UsersController extends EmptyController{
	//1.用户添加界面
	public function add(){
		$this->display();
	}
	//用户信息处理
	public function doadd(){
		$user=D('users');
		//dump($_FILES['image']);
			//执行文件上传
		$upload=new \Think\Upload();// 实例化上传类
		$upload->maxSize=3145728;// 设置附件上传大小
		$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath='./images/';// 设置附件上传目录
		$upload->autoSub =false;//关闭默认子目录;
		$upload->saveName = time().'_'.mt_rand();//设置保存的文件名
		$info=$upload->upload();//执行文件上传
		//dump($info);
		//die();
		if(!$info){
       				$this->error($upload->getError());// 上传错误提示错误信息
       				}else{
                     		$_POST['image']=$info['image']['savename'];//将文件名写入POST再写入数据库
                     		$msg=$user->pro_add();
                    }	
        $this->success($msg);//跳转并输出信息(错误或正确的);
	}
	//用户展示界面;
	public function index(){
		$user=D('users');
		$data=$user->select();
		$this->assign('title','用户列表');
		$this->assign('data',$data);
		$this->display();
	}
	

}