<?php
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
	protected $_validate=[

 //            ['admin_name','require','账号必须填'],
	// ['admin_name','','账号已存在',1,'unique',3],
	// //2.关于密码的
	// ['pass','require','密码必须填'],
	// ['pass','6,20','长度需在6到20位之间',1,'length',3],
	// ['pass','reuserpass','密码两次输入不一致',1,'confirm',3],
	// ['pass','/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/','密码不能为纯数字或纯字母',1,'regex',3],

	];

	protected $_auto=[
    //               //对密码进行哈希加密
	   // ['pass','myHash',3,'function'],
	]

	// 添加新数据
	public function pro_add(){
		
		$data=I('post.');//获取数据		
		//创建数据对象，触发自动验证
		$data=$this->create($data);
		
		if($data){
		    $this->add($data);//如果验证正确则添加到数据库
		    return '你已经是管理员了';
		}else{
		    return $this->getError();//验证错误，返回错误信息
		}
	}
}