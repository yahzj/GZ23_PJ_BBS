<?php
namespace Home\Model;
use Think\Model;
class MessageModel extends Model{
	//type为sms时走这里
	public function sms(){
		$row=10;
		//参数action不同时走不同的条件。
		//参数为send时。
		if($_GET['action']=="send"){
			
			$map['senderid']=['eq',$_SESSION['users']];
			$totalRow=$this->count();
			$page=new \Think\Page();
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
		}elseif($_GET['action']=="receive"){//参数为receive时。
			$map['receiverid']=['eq',$_SESSION['users']];
			$totalRow=$this->count();
			$page=new \Think\Page();
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
		}elseif(empty($_GET)){//完全没有type和action参数时。
			$map['receiverid']=['eq',$_SESSION['users']];
			$totalRow=$this->count();
			$page=new \Think\Page();
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
		}elseif(I('get.type')=='sms' && count(I('get.'))==1){//只有sms参数没有action参数时。
			$map['receiverid']=['eq',$_SESSION['users']];
			$totalRow=$this->count();
			$page=new \Think\Page();
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
		}else{//其它情况就是乱来的。
			$this->error("没有你要找的网页");
		}
		return $list;
	}

	//处理删除。
	public function pro_del(){
		//post--即批量删除的数据处理
		if(!empty(I('post.'))){
			$map['id']=['in',I('post.')];
			$res=$this->where($map)->delete();
		}else{
			//get---单条数据的删除。
			dump(I('get.'));
			$id=I('get.id');
			$res=$this->delete($id);
		}
		//返回删除结果。
		return $res;
	}
}