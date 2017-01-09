<?php 
namespace Admin\Model;
use Think\Model;

class SectionsModel extends Model{
            protected $_validate = [
		// [验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]]

		// ['email','email','你的邮箱格式不正确！！！'],
		// ['address','require','邮箱地址必填！！'],
		// ['pwd','3,6','你太短了，老娘不要！！' , 1, 'length' , 3],
		// ['repwd', 'pwd' , '你跟老子不是一块的！' , 1  , 'confirm',3],
	];

           // 自动完成
	protected $_auto = [
		// // [完成字段1,完成规则,[完成条件,附加规则]],
		// // ['pwd','md5',3,'function'],

		// ['pwd','myHash',3,'function'],
		// ['address','trim',3,'function'],
	];


	// 处理板块表单展示的数据
	public function pro_index(){

		// 得到总行数
		$totalRow = $this->count();
		// 每页显示条数
		$num = 15;
		// 实例化分页类
		$page = new \Think\Page($totalRow , $num);
		// 执行分页查询
		$list = $this->order('id desc')->limit( $page->firstRow . ',' . $page->listRows )->select();

		return [
			// 用户列表
			'list' => $list,
			// 分页按钮
			'show' => $page->show(),
		];		

	}

       public function pro_edit(){
		
		// 在model层接收用户提交的数据
		$post = I('post.');
    	//dump($post);
    	// 正则验证？

    	// 创建数据创建对象，会触发自动验证
    	$res = $this->create($post);
		//dump($res);
	    	if($res){
	    		$res = $this->save();
	    		//dump($res);
	    		return '修改成功！';
	    	}else{
	    		// 如果验证失败，则显示错误提示
		    	return $this->getError();
	    	}

	}




         // 添加新数据
	public function pro_add(){
		$post=I('post.');
		// 自动验证并判断
		if($this->create($post)){    
			// 写入数据到数据库并判断
			$res=$this->add();
			return $res;
	    	}else{
	    		return false;
	    	}
	}

          
}

