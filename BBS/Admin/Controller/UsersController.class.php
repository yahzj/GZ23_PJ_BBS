<?php
namespace Admin\Controller;//代码编辑:刘欣;
use Think\Controller;

class UsersController extends EmptyController{
	//用户添加界面
	public function add(){
		//===========测试用的添加数据随时可删===============================
		// $user=D('users');
		// for($i=1;$i<100;$i++){
		// 	$data=['username'=>rand(1000,2000),'nickname'=>rand(3000,4000),sex=>rand(0,1),status=>rand(0,2),];
		// 	$user->add($data);

		// }
		//===========测试用添加数据===============================
		$this->display();
	}
	//用户信息处理
	public function doadd(){
		$user=D('users');
		if($_FILES['image']['error']!=4){
			$this->imgUpload();//执行文件上传
		}
		$msg=$user->pro_add();//执行数据处理	
		if($res){        
 	  		return $this->success('新增成功',U('index/pro_index'),5);   
		}else{
            // 如果验证失败，则显示错误提示
	   			return $this->Error($user->getError());
     	}
	}
	//用户展示界面;
	public function index(){
		$user=D('users');//实例化对象
		$data=$user->pro_index();//走model层处理数据
		$data['title']='用户列表';
		$this->assign($data);
		$this->display();
	}
	//用户信息删除;
	public function del(){
		$user=D('users');//实例化MODEL类
		$res=$user->pro_del();//执行数据处理
		if($res){        
 	  		return $this->success('新增成功',U('index/pro_index'),5);   
		}else{
            // 如果验证失败，则显示错误提示
	   			return $this->Error($user->getError());
     	}
	}
	//显示用户信息;
	public function edit(){
		$user=D('users');//实例化users类
		$id=I('get.id');//获取get传过来的ID
		$data=$user->find($id);//通过ID查询一条数据
		//dump($data);
		$this->assign('data',$data);//分配数据
		$this->display();//展示模板
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
 	  		return $this->success('新增成功',U('index/pro_index'),5);   
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