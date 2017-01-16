<?php
namespace Home\Model;
use Think\Model;
class MessageModel extends Model{
	//type为sms时走这里
	public function sms(){
		$row=5;
		$users=D("users");
		$data=$users->field('id,nickname')->select();
		$userslist=[];
		foreach($data as $key=>$val){
			$userslist[$val['id']]=$val['nickname'];
		}
		//参数action不同时走不同的条件。
		//参数action为send时。
		if($_GET['action']=="send"){
			
			$map['senderid']=['eq',1];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['receivername']=$userslist[$val['receiverid']];
			}
		}elseif($_GET['action']=="receive"){//参数action为receive时。
			$map['receiverid']=['eq',1];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(empty($_GET)){//完全没有type和action参数时。
			$map['receiverid']=['eq',1];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(I('get.type')=='sms' && count(I('get.'))==1){//只有sms参数没有action参数时。
			$map['receiverid']=['eq',1];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(I('get.action')=='info'){//短信息具体页面。
			//用户点击了收件箱时，立刻将状态改为2已读
			$titleid=I('get.titleid');
			if(I('get.status')==1){
				$where['id']=['eq',$titleid];
				$data['status']=2;
				$this->where($where)->save($data);
				
			}
			
			$arr=$this->find($titleid);
			if($arr['senderid']==1){
				$arr['senderid']=['eq',1];
				$count=$this->where($arr)->count();
				$map['senderid']=['eq',1];
				$subjectlist=$this->field('id')->where($map)->select();
								
			}else{
				$arr['receiverid']=['eq',1];
				$count=$this->where($arr)->count();
				$map['receiverid']=['eq',1];
				$subjectlist=$this->field('id')->where($map)->select();
				
				

			}
			//用$i间接得到$a,$a的作用是遍历了几次得到了$titleid的值，这样就可以知道它的前后id，就可以方便显示上一条和后一条了。
			$i=0;
			//$subjectlist是个二维数组，下标从0开始递增的。
			foreach($subjectlist as $key=>$val){
				if($val['id']==$titleid){
					//得到$titleid处于$subjectlist数组的下标位置。
					$a=$i;
				}
				$i++;
			}
			//如果$a为0，说明本身这条信息就是第一条。没有上一条。
			if($a==0 && count($subjectlist)>1){
				$nextid=$a+1;
				$arr['lastid']=0;
				$arr['nextid']=$subjectlist[$nextid]['id'];
			}elseif($a==count($subjectlist)-1 && count($subjectlist)>1){
				$lastid=$a-1;
				$arr['lastid']=$subjectlist[$lastid]['id'];					
				$arr['nextid']=0;
			}elseif(count($subjectlist)==1){
				$arr['lastid']=0;
				$arr['nextid']=0;
			}else{
				$nextid=$a+1;
				$lastid=$a-1;
				$arr['lastid']=$subjectlist[$lastid]['id'];
				$arr['nextid']=$subjectlist[$nextid]['id'];
			}
			$users=D('users');
			if($arr['senderid']==1){
				//由发送人id找出这个人的昵称。
				$userslist=$users->find($arr['receiverid']);
				//找出昵称。
				$arr['sendername']=$userslist['nickname'];
				$arr['count']=count($subjectlist);
			}else{
				//由发送人id找出这个人的昵称。
				$userslist=$users->find($arr['senderid']);
				//找出昵称。
				$arr['sendername']=$userslist['nickname'];
				$arr['count']=count($subjectlist);
			}

			//统一将$list作为二维数组传过去显示。
			$list=[];
			$list[]=$arr;
		}else{//其它情况就是乱来的。
			$this->error("没有你要找的网页");
		}
		return [
			'list'=>$list,
			'count'=>$count,
		];
	}

	//处理删除。
	public function pro_del(){
		$arr=explode(",",I('get.data'));
		$map['id']=['in',$arr];
		$data['status']=3;
		$res=$this->where($map)->save($data);
		//返回删除结果。
		return $res;
	}

	public function pro_tag(){
		$arr=explode(",",I('get.data'));
		$map['id']=['in',$arr];
		$data['status']=1;
		$res=$this->where($map)->save($data);
		//返回删除结果。
		return $res;
	}

	public function pro_add(){
		$data=I("post.");
		$res=$this->add($data);
		return $res;
	}
}