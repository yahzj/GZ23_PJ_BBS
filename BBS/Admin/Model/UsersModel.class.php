<?php
namespace Admin\Model;//代码编辑:刘欣;
use Think\Model;


class UsersModel extends Model{
			//自动验证属性
			protected $_validate=[
			//对提交数据进行验证的一些规则
			//1.关于账号的
			['username','require','账号必须填'],
			['username','','账号已存在',1,'unique',3],
			//2.关于密码的
			['userpass','require','密码必须填'],
			['userpass','6,20','长度需在6到20位之间',1,'length',3],
			['userpass','reuserpass','密码两次输入不一致',1,'confirm',3],
			['userpass','/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/','密码不能为纯数字或纯字母',1,'regex',3],
			// 3.关于邮箱的
			['email','email','你的邮箱格式不正确!!!!'],
			];
			//自动完成属性
			protected $_auto=[
			//对密码进行哈希加密
			['userpass','myHash',3,'function'],
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
				$data=$this->create($data);
				//dump($data);
				$a=$this->getError();
				if($data){
					$this->add($data);//如果验证正确则添加到数据库
					return '你已经是管理大大了';
					}else{
					return $this->getError();//验证错误，返回错误信息
				}
			}
			
				
				
				 
		    
	}

	
