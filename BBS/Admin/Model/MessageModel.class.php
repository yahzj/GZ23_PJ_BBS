<?php
namespace Admin\Model;
use Think\Model;
class MessageModel extends Model{
	public function pro_index(){
		
		if(!empty($_GET)){
			if($_GET['status']==='0'||$_GET['status']==='1'){
				$str='m.status='.$_GET['status'].' and ';
				$strs='status='.$_GET['status'];
			}
			$totalRow=$this->where($strs)->count();
			dump($totalRow);
		}else{
			$totalRow=$this->count();
		}
		$row=6;
		$page=new \Think\Page($totalRow,$row);
		$obj=D();
		$list=$obj->field('u.nickname,m.*')->table('mybbs_users u,mybbs_message m')->where($str.'u.id = m.senderid')->limit(10)->select();
		//dump($list);
		$users=D('users');
		$userslist=$users->field('id,nickname')->select();
		$newlist=[];
		foreach($userslist as $key=>$val){
			$newlist[$val['id']]=$val['nickname'];
		}
		$status=[0=>'未读',1=>'已读'];
		foreach($list as $key=>&$val){
			$val['receivername']=$newlist[$val['receiverid']];
			$val['status']=$status[$val['status']];
		}
		dump($list);
		return [
			'list'=>$list,
		];
	}
}