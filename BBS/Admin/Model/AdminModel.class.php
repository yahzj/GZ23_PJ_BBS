<?php
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
	protected $_validate=[

		['admin_name','require','账号必须填'],
		['admin_name','','账号已存在',0,'unique',3],
		//2.关于密码的
		['pass','require','密码必须填',0,"",1],
		['pass','6,20','长度需在6到20位之间',0,'length',1],
		['pass','repass','密码两次输入不一致',0,'confirm',3],
		['pass','/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/','密码不能为纯数字或纯字母',0,'regex',1],

	];

	protected $_auto=[
                  //对密码进行哈希加密
	   ['pass','myHash',3,'function'],
	];


            public function pro_index(){

		// 得到总行数
		$totalRow = $this->count();
		// 每页显示条数
		$num = 10;
		// 实例化分页类
		$page = new \Think\Page($totalRow , $num);
		// 执行分页查询
		$list = $this->order('id asc')->limit( $page->firstRow . ',' . $page->listRows )->select();

        $status = ['锁定','管理员','超级管理员'];
		// 基本处理
		foreach($list as $key => &$val){
			$val['status'] = $status[ $val['status'] ];
		}

		return [
			// 用户列表
			'list' => $list,
			// 分页按钮
			'show' => $page->show(),
		];		

	}

                     // 数据编辑处理，加入自动验证
	public function pro_edit(){
		
		// 在model层接收用户提交的数据
		$post = I('post.');
		//dump($post);
	    	// 创建数据创建对象，会触发自动验证
		$res=$this->create($post);
		//dump($res);
	    	if($res){
    		      // 进行写入，并判断
    		      $res=$this->save();
			return TRUE;
		}else{
			// return false;
		}
	    }


	// 添加新数据
	public function pro_add(){
		
		$data=I('post.');//获取数据		
		//创建数据对象，触发自动验证
		$data=$this->create($data);
		
		if($data){
		    $this->add($data);//如果验证正确则添加到数据库
		    return '添加成功';
		}else{
		    return false;//验证错误，返回错误信息
		}
	}
}