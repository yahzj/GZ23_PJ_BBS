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
					return $this->getError();//验证错误，返回错误信息

				}
			}
			//处理用户显列表数据
			public function pro_index(){
				//if(IS_POST){
				$data=I('post.');//获取post的值
				//}elseif(IS_GET){
					//$data=I('get.');
					//echo'这是get传的值';
					//dump($data);
				//}
				//查询条件处理
				dump($data);
				if(!empty($data)){
					$map=[];//定义一个查询条件的数组
					// $map['username']=['like','%'.$data['username'].'%'];
					// $map['nickname']=['like','%'.$data['nickname'].'%'];
					// $map['sex']=['eq',$data['sex']];
					// $map['status']=['eq',$data['status']];
					foreach($data as $k=>$v){
						if($v&&($k=='username'||$k=='nickname')){
							$map[$k]=['like','%'.$v.'%'];//如果POST传过来的键为username和nickname 那么搜索表达式用like
							}
							if(in_array($v,[0,1,2])&&($k=='sex'||$k=='status')){
								$map[$k]=['eq',$v];//如果POST传过来的键为sex和status 那么搜索表达式用eq
							}
						
					}
					dump($map);
					$totalRow=$this->where($map)->count();
					dump($totalRow);
					$data['p']=1;
				}else{
					$totalRow=$this->count();//计算数据总行数
				}
				
				$rows=10;//每行显示行数
				$page=new \Think\Page($totalRow,$rows);//实例化分页类

				foreach($data as $key=>$val){
					
					//echo $key;
					echo "<hr>";
					echo $val;   
       				$page->parameter.= "$key=".urlencode($val)."&";//将参数写入分页参数类，，，但P不会存在了。
       			}	
				$list=$this->where($map)->order('`id`')->limit($page->firstRow.",".$page->listRows)->select();//执行查询数据
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
						$images=$this->field('image')->where($map)->select();//通过id查询需要删除的数据的图像的路径
						foreach($images as $k=>$v){
							$this->imgdel($v);
						}
						$res=$this->where($map)->delete();//通过WHere方法批量删除数据
						if(!$res){
							return $this->getError();//返回错误信息
						}else{
							return "删除成功";
						}
					
					}else{
						$id=I('get.id');//获取删除的Id;
						$images=$this->field('image')->find($id);//通过id查询需要删除的数据的图像的路径
						$this->imgdel($images);
						$res=$this->delete($id);//执行删除的条数
						if(!$res){
							return $this->getError();//返回错误信息
						}else{
							return "删除成功";
						}
					}

				}
			public function pro_updata(){
				$data=I('post.');//获取post传的值
				$id=I('post.id');//得到ID
				dump($data);
				//判断是否修改了图像，如果没有修改就用原图
				if($_FILES['image']['error']==4){
					$data['image']=$data['oldimage'];//将原图像名给修改数据后
					dump($data);
				}
				//创建数据对象，触发自动验证
				$newdata=$this->create($data,2);
				dump($newdata);
				if($newdata){
					$map['id'] = ['eq',$id];
					dump($map);
					$this->where($map)->save($newdata);//执行保存
					return '修改信息成功';
				}else{
					//如果没有修改图片，那么数据写入失败时不执行图像删除
					//判断依据就是图片名还是原来的图片名
					//当图片名不是原来的图片名说明上传的新图像那就在数据写入是执行删除图片;
					if($data['image']!==$data['oldimage']){
						$this->imgdel($data);//执行图片删除
					}
					return $this->getError();//返回错误信息
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

	
