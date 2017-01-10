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
			//处理用户显列表数据
			public function pro_index(){
				$totalRow=$this->count();//计算数据总行数
				$rows=10;//每行显示行数
				$page=new \Think\Page($totalRow,$rows);//实例化分页类

				$list=$this->order('`id`')->limit($page->firstRow.",".$page->listRows)->select();//执行查询数据
				$sex=['女','男'];//设定转换性别
				$status=['超级管理员','管理员','会员'];//设定用户类型
				foreach($list as $k=>&$v){
					$v['sex']=$sex[$v['sex']];//修改性别显示
					$v['status']=$status[$v['status']];//修改用户类型显示
				}
				
				return [
					'list'=>$list,
					'show'=>$page->show(),//取得分页按钮;
				];

			}
			//删除用户信息
			public function pro_del(){
					$data=I('post.');//获取多选框传过来的值
					$ids=[];//定义一个空数组做Id的容器
					if($data){
						foreach($data as $k=>$v){
							$ids[]=$v;//将遍历的ID值放到容器里
						}
						$map['id']=['in',$ids];
						$res=$this->where($map)->delete();//通过WHere方法批量删除数据
						if(!$res){
							return $this->getError();
						}else{
							return "删除成功";
						}
					
					}else{
						$id=I('get.id');//获取删除的Id;
						$res=$this->delete($id);//执行删除的条数
						if(!$res){
							return $this->getError();
						}else{
							return "删除成功";
						}
					}

				}

			
				
				
				 
		    
	}

	
