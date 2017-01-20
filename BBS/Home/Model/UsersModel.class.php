<?php
namespace Home\Model;//代码编辑:刘欣;
use Think\Model;

class UsersModel extends Model{
	//自动验证
	protected $_validate=[
			//对提交数据进行验证的一些规则
			//1.关于账号的
			['username','require','账号必须填'],
			['username','','账号已存在',1,'unique',3],
			//2.关于密码的
			['userpass','require','密码必须填',1,"",1],
			['userpass','6,20','长度需在6到20位之间',1,'length',1],
			['userpass','reuserpass','密码两次输入不一致',1,'confirm',3],
			['userpass','/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/','密码不能为纯数字或纯字母',1,'regex',1],
			// 3.关于邮箱的
			['email','email','你的邮箱格式不正确!!!!'],
			];
	//自动完成属性
	protected $_auto=[
	//对密码进行哈希加密
	['userpass','myHash',1,'function'],
	//对地址和个性签名进行空格去除
	['address','trim',3,'function'],
	['sign','trim',3,'function'],
	];
	//执行自动验证
	public function pro_add(){
		//echo '我在数据处理方法';
		$data=I('post.');//获取数据
		//dump($data);
		//创建数据对象，触发自动验证
		$newdata=$this->create($data);
		//dump($newdata);
		
		if($newdata){
			$this->add($newdata);//如果验证正确则添加到数据库
			return '你已经是管理大大了';
			}else{
			//加入数据库失败。删除已经上传的图片
			//echo $data['image'];
			//echo '在';
			$this->imgdel($data);
			return false;//验证错误，返回错误信息

		}
	}
	//用户数据处理	
	public function pro_index(){
		//dump($_SESSION);
		$id=$_SESSION['mybbs_home']['0']['id'];//这是我的ID;
		//dump($id);
		$list=$this->find($id);//通过id找到一条数据。。find方法不管你有米有参数都会出来一条数据！
		//dump($list);
		//对数据进行替换显示
		$sex=['女','男'];
		$status=['超级管理员','管理员','会员'];
		$list['sex']=$sex[$list['sex']];
		$list['status']=$status[$list['status']];
		//dump($list);
		return $list;//返回数据	

	}

	//个人档资料修改处理
	public function pro_updata(){
		$data=I('post.');//获取post传的值
		$id=I('post.id');//得到ID
		// dump($data);
		//判断是否修改了图像，如果没有修改就用原图
		if($_FILES['image']['error']==4){
			$data['image']=$data['oldimage'];//将原图像名给修改数据后
			// dump($data);
		}
		//创建数据对象，触发自动验证
		$newdata=$this->create($data,2);
		// dump($newdata);
		if($newdata){
			$map['id'] = ['eq',$id];
			// dump($map);
			$this->where($map)->save($newdata);//执行保存
			return '修改信息成功';
		}else{
			//如果没有修改图片，那么数据写入失败时不执行图像删除
			//判断依据就是图片名还是原来的图片名
			//当图片名不是原来的图片名说明上传的新图像那就在数据写入是执行删除图片;
			if($data['image']!==$data['oldimage']){
				$this->imgdel($data);//执行图片删除
			}
			return false();//返回错误信息
		}
	}
	//删除图片的方法，要两个参数：$data数据变量名 $v图片的名称的键值;默认为‘image’;
	public function imgdel($data,$v='image'){
		$a='./Uploads/images/new'.$data[$v];//裁剪后图片路径
		$b='./Uploads/images/'.$data[$v];//裁剪前图片路径
		@unlink($a);//删除裁剪后图片
		@unlink($b);//删除裁剪前图片
	}
	//======================================================================

	//======================================================================

}

	
