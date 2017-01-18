<?php
namespace Home\Model;
use Think\Model;
class MessageModel extends Model{
	//type为sms时走这里,与发信箱和收信箱有关的。
	public function sms(){
		//获得当前用户的id。
		$nameid=$_SESSION['mybbs'][0]['id'];
		//设定好每页显示5条。
		$row=5;
		//状态为未读的消息
		$unreadarr['status']=0;
		//接收人是该用户的
		$unreadarr['receiverid']=$_SESSION['mybbs'][0]['id'];
		//统计出该用户未读的消息的条数。
		$unread=$this->where($unreadarr)->count();
		$objnotice=D('notice');
		//统计系统通知的条数。
		$noticenum=$objnotice->count();
		
		$users=D("users");
		//只搜用户类的id和昵称nickname，用来设置一个专门的id和nickname键值对数组。
		$data=$users->field('id,nickname')->select();
		//空数组用来放id和nickname键值对数组。
		$userslist=[];
		//将id作为键，昵称作为值。
		foreach($data as $key=>$val){
			$userslist[$val['id']]=$val['nickname'];
		}
		//参数action不同时走不同的条件。
		//参数action为send时。就是进入了发件箱
		if($_GET['action']=="send"){
			//找出所有该用户发出的所有短消息。
			$map['senderid']=['eq',$nameid];
			//得到总条数
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			//将总条数赋值给$count，用来显示页面显示总条数的。
			$count=$totalRow;
			//$list数组新增一个键值receivername，用来放置对方的昵称。
			foreach($list as $key=>&$val){
				$val['receivername']=$userslist[$val['receiverid']];
			}
		}elseif($_GET['action']=="receive"){//参数action为receive时。是收件箱，具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(empty($_GET)){//完全没有type和action参数时。是收件箱，具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(I('get.type')=='sms' && count(I('get.'))==1){//只有sms参数没有action参数时。是收件箱。具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		}elseif(I('get.action')=='info'){//短信息具体页面。
			//用户点击了某条短消息时，立刻将该短消息状态改为1已读，0为未读，1为已读，3为删除。
			$titleid=I('get.titleid');
			//get.status是前端页面传过来的，status==1是用户点击了
			if(I('get.status')==1){
				$where['id']=['eq',$titleid];
				$data['status']=1;
				$this->where($where)->save($data);
				
			}
			//找到要点击进去的这条短消息
			$arr=$this->find($titleid);
			//找到该用户发的所有信息。
			if($arr['senderid']==$nameid){
				$arrlist['senderid']=['eq',$nameid];
				$count=$this->where($arrlist)->count();
				$map['senderid']=['eq',$nameid];
				$subjectlist=$this->field('id')->where($map)->select();
								
			}else{
				//不是发的消息，就是收的消息。
				//查询出该用户收到的所有信息。
				$arrlist['receiverid']=['eq',$nameid];
				$count=$this->where($arrlist)->count();
				$map['receiverid']=['eq',$nameid];
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
			}elseif($a==count($subjectlist)-1 && count($subjectlist)>1){//条数大于1的，刚刚好$a又是最后一条，那么就没有下一条了。
				$lastid=$a-1;
				$arr['lastid']=$subjectlist[$lastid]['id'];					
				$arr['nextid']=0;
			}elseif(count($subjectlist)==1){//条数刚好等于1的，没有上一条，也没有下一条。
				$arr['lastid']=0;
				$arr['nextid']=0;
			}else{//有上一条，也有下一条
				$nextid=$a+1;
				$lastid=$a-1;
				$arr['lastid']=$subjectlist[$lastid]['id'];
				$arr['nextid']=$subjectlist[$nextid]['id'];
			}
			$users=D('users');
			//从用户是发信人还是收信人找出对方的昵称。
			if($arr['senderid']==$nameid){
				//由收信人id找出这个人的昵称。
				$userslist=$users->find($arr['receiverid']);
				//找出对方昵称。
				$arr['sendername']=$userslist['nickname'];
			}else{
				//由发信人id找出这个人的昵称。
				$userslist=$users->find($arr['senderid']);
				//找出对方昵称。
				$arr['sendername']=$userslist['nickname'];
			}

			
			//将$list作为二维数组传过去显示。
			$list=[];
			$list[]=$arr;
		//通知页面
		}elseif(I('get.type')=='notice'){
			$row=10;
			$notice=D('notice');
			$count=$notice->count();
			$page=new \Think\Page($count,$row);
			$list=$notice->limit($page->firstRow.",".$page->listRows)->select();
			

		}elseif(I('get.type')=='post'){
			//这里没什么用，就是用来给新消息页面返回未读消息和系统通知条数才设的这里。
		}elseif(I('get.type')=='request'){
		}else{//其它情况就是乱来的。
			$this->error("没有你要找的网页");
		}
		//根据是否有分页来返回不同数据。
		if($page){
			return [
				'list'=>$list,
				'count'=>$count,
				'show'=>$page->show(),
				'unread'=>$unread,
				'noticenum'=>$noticenum,
			];
		//如果没有分页，并且是新消息页面，则只返回未读消息和系统通知条数就好了。
		}elseif(I('get.type')=='post'){
			return [
				'unread'=>$unread,
				'noticenum'=>$noticenum,
			];
		}else{
			//其它情况返回这些数据。
			return [
				'list'=>$list,
				'count'=>$count,
				'unread'=>$unread,
				'noticenum'=>$noticenum,
			];
		}
		
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
	//标记已读处理
	public function pro_tag(){
		$arr=explode(",",I('get.data'));
		$map['id']=['in',$arr];
		$data['status']=1;
		$res=$this->where($map)->save($data);
		//返回处理结果
		return $res;
	}
	//回复内容，是回复，不是发新消息。
	public function pro_add(){
		$data=I("post.");
		$res=$this->add($data);
		return $res;
	}

	public function pro_post(){
		$users=D('users');
		$map['nickname']=['eq',I('post.receivername')];
		$res=$users->where($map)->select();
		if(!$res){
			return 0;
		}
		$add['receiverid']=2;
		$add['senderid']=1;
		$add['title']=['eq',I('post.title')];
		$add['content']=['eq',I('post.content')];
		$res=$this->add($add);
		if($res){
			return 1;
		}else{
			return 2;
		}
	}
}