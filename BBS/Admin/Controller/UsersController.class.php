<?php
namespace Admin\Controller;
use Think\Controller;

class UsersController extends EmptyController{
	//1.用户添加界面
	public function add(){
		$this->display();
	}
	//用户信息处理
	public function doadd(){
		$user=D('users');
		dump($_FILES['image']);
			//执行文件上传
		$upload=new \Think\Upload();// 实例化上传类
		$upload->maxSize=3145728;// 设置附件上传大小
		$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath='./images/';// 设置附件上传目录
		$upload->saveName = time().'_'.mt_rand();//设置保存的文件名
		$info=$upload->upload();//执行文件上传
		if(!$info){// 上传错误提示错误信息  
       				echo $this->error($upload->getError());
       				die('图片上传失败'); 
                    }else{
                     		echo '上传成功';
                     		$user->pro_add();
                    }	


		
		
		

	}

}