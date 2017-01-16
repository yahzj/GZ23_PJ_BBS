<?php
namespace Admin\Model;
use Think\Model;
class MessageModel extends Model{
	//执行处理所有数据和处理搜索的数据
	public function pro_index(){
		//判断是搜索还是没有搜索。
		if(!empty($_GET['status'])){
			//搜索走这里
			//先对用户搜索的关于message表的内容进行sql语句的处理。
			//处理用户要搜索的消息内容
			if($_GET['content']){
				$contentstr='m.content like "%'.$_GET['content'].'%" and ';
				$contentstrs='content like "%'.$_GET['content'].'%"';
			}
			//处理用户要搜索的消息状态是否已读和未读。
			if($_GET['status']==='0'||$_GET['status']==='1'){
				$str='m.status='.$_GET['status'].' and ';
				if($_GET['content']){
					$strs='status='.$_GET['status'].' and ';
				}else{
					$strs='status='.$_GET['status'];
				}
				
			}
			//根据要搜索的消息内容和消息状态进行统计条数，用于作分页用。
			$totalRow=$this->where($strs.$contentstrs)->count();
		}else{
			//没有搜索走这里。得到所有条数。
			$totalRow=$this->count();
		}
		//实例化空模型
		$obj=D();
		//联合查询users和message表。得到数据。$contentstr和$str是搜索的消息内容和消息状态条件。
		$list=$obj->field('u.nickname,m.*')->table('mybbs_users u,mybbs_message m')->where($contentstr.$str.'u.id = m.senderid')->order('id')->select();
		
		//实例化users表
		$users=D('users');
		//搜索所有的用户id和昵称
		$userslist=$users->field('id,nickname')->select();
		//空数组用来准备放users表的每一个id作为键，每一个nickname作为值存放。
		$newlist=[];
		//这里开始存放users表的id和nickname。
		foreach($userslist as $key=>$val){
			$newlist[$val['id']]=$val['nickname'];
		}

		//将消息状态改变为中文的
		$status=[0=>'未读',1=>'已读'];
		//处理根据用户搜索的消息内容和消息状态得到的数据。加上接收人的昵称。因为原来的list数组只有发送人的昵称。
		foreach($list as $key=>&$val){
			$val['receivername']=$newlist[$val['receiverid']];
			$val['status']=$status[$val['status']];
		}
		//如果用户还查询了发送人，则还要对数组进行过滤。发送人昵称中有这个字的才留，没有的就销毁掉。
		if(!empty($_GET['sender'])){
			//将数据进行遍历，找出发送人昵称中有这个字的。
			foreach($list as $key=>&$val){
				if(substr_count($val['nickname'],$_GET['sender'])<1){
					//将不符合的销毁掉。
					unset($list[$key]);
				}
			}
			
			$totalRow=count($list);
		}
		//这里是用户搜索了接收人昵称，道理同上面的搜索发送人昵称。。
		if(!empty($_GET['receiver'])){
			foreach($list as $key=>&$val){
				if(substr_count($val['receivername'],$_GET['receiver'])<1){
					unset($list[$key]);
				}
			}
			
			$totalRow=count($list);
		}
		
		//将所有数据重新按照键为0开始排列。得到一个新数组。
		$arrlist=array_values($list);
		$p=$_GET['p'];
		//每页显示条数。
		$row=3;
		//得到总的页数。
		$num=ceil($totalRow/$row);
		//防止页数p大于最大的或者小于1.
		$p=min($p,$num);
		$p=max($p,1);
		//设置空数组，是最后要返回给控制器进行分配的。
		$lastlist=[];
		//如果条数大于要显示的条数。则走这里
		if($totalRow>$row){
			//如果参数p的值和总页数一样，说明最大下标取到总条数就可以了。
			if($p==$num){
				for($i=(($p-1)*$row);$i<$totalRow;$i++){
					$lastlist[]=$arrlist[$i];
				}
			}else{
				//如果参数p的值少于总页数，说明它每页都能显示正常的$row条。
				for($i=(($p-1)*$row);$i<$p*$row;$i++){
					$lastlist[]=$arrlist[$i];
				}
			}
		}else{
			//数据条数少于要显示的条数$row，就在第一页让它全部显示完。
			for($i=0;$i<$totalRow;$i++){
				$lastlist[]=$arrlist[$i];
			}
		}
		

		//因为可能过滤了发送人昵称和接收人昵称，所以数据可能减少了，要重新得到分页的新对象，这样分页才能跟新数据吻合。
		$newpage=new \Think\Page($totalRow,$row);
		return [
			'list'=>$lastlist,
			'show'=>$newpage->show(),
		];
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