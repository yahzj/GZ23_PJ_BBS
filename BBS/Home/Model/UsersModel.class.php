<?php
namespace Home\Model;//代码编辑:刘欣;
use Think\Model;

class UsersModel extends Model{
		
	public function pro_index(){
		$id=529;//这是我的ID;
		$list=$this->find($id);//通过id找到一条数据
		//dump($list);
		//对数据进行替换显示
		$sex=['女','男'];
		$status=['超级管理员','管理员','会员'];
		$list['sex']=$sex[$list['sex']];
		$list['status']=$status[$list['status']];
		//dump($list);
		
		return $list;//返回数据	

	}


}

	
