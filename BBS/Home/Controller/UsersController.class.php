<?php
namespace Home\Controller;//代码编辑:刘欣;
use Think\Controller;

class UsersController extends EmptyController{
	//个人中心信息显示
	public function index(){
		$user=D('users');//实例化类
		$data=$user->pro_index();//接收数据处理
		//dump($list);
		//dump($_SESSION);
		//dump($data);
		$this->assign($data);//分配数据
		$this->display();//显示模板
	}
	//个人档资料信息显示
	public function upload(){
		$user=D('users');//实例化类
		$data=$user->pro_index();//接收数据处理
		$this->assign($data);//分配数据
		$this->display();//显示模板
	}
	//个人档资料修改
	public function doUpload(){
		$user=D('users');//实例化类
		$data=$user->pro_index();//接收数据处理
		$this->assign($data);//分配数局
		$this->display();//显示模板
	}
	//注册页面显示
	public function add(){
		
		$this->display();//显示模板
	}
	//用户信息处理
	public function doadd(){
		$user=D('users');
		if($_FILES['image']['error']!=4){
			$this->imgUpload();//执行文件上传
		}
		$res=$user->pro_add();//执行数据处理	
		if($res){        
 	  		return $this->success('新增成功',U('Home/login/login'),1);   
		}else{
            // 如果验证失败，则显示错误提示
	   			return $this->Error($user->getError());
     	}
	}
	//修改用户信息;
	public function updata(){
		$user=D('users');//实例化类
		$data=I('post.');//获取post传过来的值
		if($_FILES['image']['error']!=4){
			$this->imgUpload();//执行文件上传
		//修改了图像，删除原图
		$user->imgdel($data,'oldimage');
		}
		$res=$user->pro_updata();//执行数据处理
		if($res){        
 	  		return $this->success('修改成功',U('upload'),1);   
		}else{
            // 如果验证失败，则显示错误提示
	   			return $this->Error($user->getError());
     	}

	}
	
	//========================图片上传的方法=============================================
	public function imgUpload(){
		$upload=new \Think\Upload();// 实例化上传类
		$upload->maxSize=3145728;// 设置附件上传大小
		$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath='./images/';// 设置附件上传目录
		$upload->autoSub =false;//关闭默认子目录;
		$upload->saveName = time().'_'.mt_rand();//设置保存的文件名
		$info=$upload->upload();//如果用户选择了图像上传便执行文件上传
				//dump($info);
				//die();
				if(!$info){
		       	$this->error($upload->getError());// 上传错误提示错误信息
		       	}else{
		                $_POST['image']=$info['image']['savename'];//将文件名写入POST再写入数据库
		       //================================修改图像尺寸=================================
						$image = new \Think\Image(); 
						$image->open('./Uploads/images/'.$_POST['image']);// 生成一个左上角裁剪为100*100的缩略图并保存为thumb.jpg
						$image->thumb(100, 100,\Think\Image::IMAGE_THUMB_NORTHWEST)->save('./Uploads/images/new'.$_POST['image']);
				//================================修改图像尺寸=================================
		        }

	}
	//========================图片上传的方法结束=========================================



}