<?php
namespace Home\Model;
use Think\Model;
class MessageModel extends Model{
	//type为sms时走这里。
	public function sms(){
		//获得当前用户的id。
		$nameid=$_SESSION['mybbs_home'][0]['id'];
		//设定好每页显示5条。
		$row=5;
		//状态为未读的消息
		$unreadarr['status']=0;
		//接收人是该用户的
		$unreadarr['receiverid']=$_SESSION['mybbs_home'][0]['id'];
		//统计出该用户未读的消息的条数。
		$unread=$this->where($unreadarr)->count();
		$objnotice=D('notice');
		//统计系统通知的条数。
		$noticenum=$objnotice->count();
		
		//统计未添加好友的条数。
		$friend=D('friend');
		$friendmap['uid']=['eq',$nameid];
		$friendlist=$friend->where($friendmap)->select();
		//该用户在friend数据表是否有数据。
		if($friendlist){
			//该用户是否有未添加的好友
			if($friendlist[0]['waitfriend']){
				//去除两边的逗号
				$friendstr=trim($friendlist[0]['waitfriend'],",");
				//分解成数组形式
				$friendarr=explode(",",$friendstr);
				//得到未添加好友的人数。
				$friendcount=count($friendarr);
			}
		}else{
			//如果没有数据就是0
			$friendcount=0;
		}

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
			$map['status']=['neq',3];
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
		//------------------------------------------------------------------------------------------
		}elseif($_GET['action']=="receive"){//参数action为receive时。是收件箱，具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$map['status']=['neq',3];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		//------------------------------------------------------------------------------------------
		}elseif(empty($_GET)){//完全没有type和action参数时。是收件箱，具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$map['status']=['neq',3];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		//------------------------------------------------------------------------------------------
		}elseif(I('get.type')=='sms' && count(I('get.'))==1){//只有sms参数没有action参数时。是收件箱。具体和send一样。
			$map['receiverid']=['eq',$nameid];
			$map['status']=['neq',3];
			$totalRow=$this->where($map)->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		//------------------------------------------------------------------------------------------
		}elseif(!empty(I('get.p')) && count(I('get.'))==1){//只有p参数时。是收件箱。具体和send一样。
			$map['receiverid']=$nameid;
			$map['status']=['neq',3];
			$totalRow=$this->where($map)->count();

			$page=new \Think\Page($totalRow,$row);
			$list=$this->where($map)->limit($page->firstRow.",".$page->listRows)->select();
			$count=$totalRow;
			foreach($list as $key=>&$val){
				$val['sendername']=$userslist[$val['senderid']];
			}
		//------------------------------------------------------------------------------------------
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
		//------------------------------------------------------------------------------------------
		//通知页面
		}elseif(I('get.type')=='notice'){
			$row=10;
			$notice=D('notice');
			$count=$notice->count();
			$page=new \Think\Page($count,$row);
			$list=$notice->limit($page->firstRow.",".$page->listRows)->select();
		//------------------------------------------------------------------------------------------
		//其它页面点击写信跳转过来的处理。
		}elseif(I('get.type')=='post' && !empty(I('get.id'))){
			$id=I('get.id');
			$users=D('users');
			$map['id']=['eq',$id];
			$list=$users->where($map)->select();
		//------------------------------------------------------------------------------------------
		//搜索消息处理
		}elseif(I('get.type')=='search'){
			$id=I('post.id');
			$search=I('post.search');
			$friend=D('friend');

			$usersarr=$friend->where('`uid`='.$nameid)->select();
			if($usersarr){
				if($usersarr[0]['friend']){
					$str=trim($usersarr[0]['friend'],',');
					$arr=explode(",",$str);
					$list=[];
					foreach($arr as $key=>$val){
						$list[$val]=$userslist[$val];
					}
				}
			}
			if(!empty($search)){
				$key=array_search($search,$userslist);
				if($key){
					$count=$this->where("((`senderid` = {$key} and `receiverid` = {$nameid}) or (`receiverid` = {$key}) and `senderid` = {$nameid}) and `status` != 3")->count();
					$page=new \Think\Page($count,$row);
					$searchlist=$this->where("((`senderid` = {$key} and `receiverid` = {$nameid}) or (`receiverid` = {$key}) and `senderid` = {$nameid}) and `status` != 3")->limit($page->firstRow.','.$page->listRows)->select();
					foreach($searchlist as $key=>&$val){
						$val['name']=$search;
					}
					if($searchlist){
						$searchlist[0]['names']=2;
					}else{
						$searchlist[0]['names']=1;
					}
					
				}
				
			}
		//------------------------------------------------------------------------------------------
		}elseif(I('get.type')=='post'){
			//这里没什么用，就是用来给新消息页面返回未读消息和系统通知条数才设的这里。
		//------------------------------------------------------------------------------------------
		}elseif(I('get.type')=='request'){
			if($friendlist){
				if($friendlist[0]['waitfriend']){
					$list=[];
					$i=0;
					foreach($friendarr as $key=>$val){
						$list[$i]['id']=$val;
						$list[$i]['nickname']=$userslist[$val];
						$i++;
					}
				}
			}
		//------------------------------------------------------------------------------------------
		}else{//其它情况就是乱来的。
			$this->error("没有你要找的网页");
		}
		//根据是否有分页来返回不同数据。否则没有分页时$page->show()会出错。
		//list为数据，count为数据的条数，show为分页，unread为未读消息，noticenum为系统通知。
		if($page){
			return [
				'list'=>$list,
				'count'=>$count,
				'show'=>$page->show(),
				'unread'=>$unread,
				'noticenum'=>$noticenum,
				'friendcount'=>$friendcount,
				'searchlist'=>$searchlist,
			];
		//如果没有分页，并且是新消息页面，则只返回未读消息和系统通知条数和好友申请就好了。
		}elseif(I('get.type')=='post'){
			return [
				'list'=>$list,
				'unread'=>$unread,
				'noticenum'=>$noticenum,
				'friendcount'=>$friendcount,
			];
		}else{
			//其它情况返回这些数据。
			return [
				'list'=>$list,
				'count'=>$count,
				'unread'=>$unread,
				'noticenum'=>$noticenum,
				'friendcount'=>$friendcount,
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
		$data['senderid']=$_SESSION['mybbs_home']['0']['id'];
		$data['title']='回复：'.I('post.title');
		$res=$this->add($data);
		return $res;
	}

	public function pro_post(){
		
		//得到数字9
		$num=check();
		//返回到控制器。
		if($num==9){
			return 9;
		}


		$users=D('users');
		$map['nickname']=['eq',I('post.receivername')];
		$res=$users->where($map)->select();
		if(!$res){
			return 0;
		}
		$add['receiverid']=$res[0]['id'];
		$add['senderid']=$_SESSION['mybbs_home'][0]['id'];
		$add['title']=I('post.title');
		$add['content']=I('post.content');
		$res=$this->add($add);
		if($res){
			return 1;
		}else{
			return 2;
		}
	}

	//好友处理
	public function pro_friend(){
		header("Content-Type: text/html;charset=utf-8");
		
		//获得当前用户的id
		$nameid=$_SESSION['mybbs_home'][0]['id'];
		//获得当前用户的用户名
		$users=$_SESSION['mybbs_home'][0]['nickname'];
		$friend=D('friend');
		$friendmap['uid']=['eq',$nameid];
		//遍历出所有待添加的好友
		$friendlist=$friend->where($friendmap)->select();
		//如果有待添加的好友，就将所有等待用户的数据进行去除逗号处理。
		if($friendlist){
			if($friendlist[0]['waitfriend']){
				//去除待添加好友字符串两边的逗号
				$friendstr=trim($friendlist[0]['waitfriend'],",");
				//得到处理后的待添加好友的id数组
				$friendarr=explode(",",$friendstr);
			}
		//没有任何待添加好友也跳转到这里来，说明是用户在地址栏模拟输入的，告诉用户不要这样弄。
		}else{
			return 0;//用户在地址栏乱搞的id
		}
		$id=I('get.id');
		
		//用户传上来了id，就要判断这个id是否在待添加好友中
		if(in_array($id,$friendarr)){
			//status等于1就是接受添加，等于0是拒绝添加。
			if(I('get.status')==1){
				//接受添加就要判断该用户的好友是不是大于50个了，大于就不能再添加了。
				if($friendlist[0]['num']>=50){
					return 1;//返回超过50人的提示
				}else{
					//好友数量少于50就走这里
					//得到这个id在待添加好友数组的下标
					$friendkey=array_search($id,$friendarr);
					//因为接受添加，就要从待添加处删除该id。
					unset($friendarr[$friendkey]);
					//如果添加后待添加好友少于0了，那么就要清空待添加
					if(count($friendarr)==0){
						$str='';
					}else{
						//如果还有待添加的人员，那么将它重新组装好放回数据库。
						$str=",".implode(",",$friendarr).",";
					}
					//如果该用户之前已经有好友了，那么直接在后面拼接后再放进去。
					if(!empty($friendlist[0]['friend'])){
						$strs=$friendlist[0]['friend'].$id.',';
					}else{
						//如果该用户之前没有好友，那么就要在前面加多一个逗号。再放回去，前面有逗号是规定好了的。
						$strs=','.$friendlist[0]['friend'].$id.',';
					}
					$checkid['uid']=['eq',$id];
					$checklist=$friend->where($checkid)->select();
					dump($checklist);
					//准备好添加成功后要发送的系统文字消息
					$addstr="添加好友成功";
					$agreestr="已同意你的好友请求";
					//因为要先对数据库的各个列表进行不同情况的处理，所以用pdo的事务处理比较适合。
					try{
						// 1.准备数据源
						$pdo = new \PDO("mysql:host=localhost;dbname=my_bbs","root","");
						// 设置错误模式
						$pdo->setAttribute(3,1);
						//这句可以防止写入的时候中文乱码。
						$pdo->query("SET NAMES utf8");
						// PDO的事务处理
						// 开启事务
						$pdo->beginTransaction();
						//将好友这一栏重新写入
						$sql = "update `mybbs_friend` set `friend` = '".$strs."' where uid = {$nameid}";
						//$res正确的话就返回1.
						$res = $pdo->exec($sql);
						//将好友数量+1
						$sql = "update `mybbs_friend` set `num` = `num`+1 where uid = {$nameid}";
						$res += $pdo->exec($sql);
						//0表示系统，在用户列表是没有的，是系统发送给申请者添加好友成功消息的。
						$sql = "insert into `mybbs_message`(`senderid`,`receiverid`,`title`,`content`) values('0','".$id."','".$addstr."','".$users." ".$agreestr."')";
						$res += $pdo->exec($sql);
						//处理申请者这边的结果
						if($checklist[0]['uid']){
							if($checklist[0]['friend']){
								dump($checklist);
								$addid=$checklist[0]['friend'].$nameid.",";
								$sql = "update `mybbs_friend` set `friend` = '".$addid."',`num`=`num`+1 where uid = {$id}";
								$res += $pdo->exec($sql);
							}else{
								$addid=",".$nameid.",";
								$sql = "update `mybbs_friend` set `friend` = '".$addid."',`num`=`num`+1 where uid = {$id}";
								$res += $pdo->exec($sql);
							}
							

						}else{
							$addid=",".$nameid.",";
							$sql = "insert into `mybbs_friend`(`uid`,`friend`,`num`) values('$id','$addid','1')";
							$res += $pdo->exec($sql);
						}
						//将更新后的待添加好友数据库更新。
						$sql = "update `mybbs_friend` set `waitfriend` = '".$str."' where uid = {$nameid}";
						$res += $pdo->exec($sql);
						//判断上面的是不是都执行成功了，是的话就确定，否则就回滚恢复原样。
						if( $res > 4 ){
							//确认
							$pdo->commit();
							return 3;//添加好友成功，删除等待添加好友也成功。
						}else{
							// 回滚
							$pdo->rollBack();
							return 2;//系统出错
						}

					}catch(PDOException $e){
						return 2;//系统出错
					}
				}
			}else{
				//拒绝添加好友处理
				$friendkey=array_search($id,$friendarr);
				//既然拒绝了，就要将这个id从待添加列表里删除。
				unset($friendarr[$friendkey]);
				//删除之后要看剩下还有没有要添加的，有就执行拼接写入，没有就给一个空值回去。
				if(count($friendarr)>0){
					$str=','.implode(",",$friendarr).',';
					$map['waitfriend']=$str;

				}else{
					//删除之后没有了就走这里
					$str='';
					$map['waitfriend']=$str;
				}
				//执行修改
				$res=$friend->where('uid='.$nameid)->save($map);
				if($res){
					return 4;//拒绝添加成功
				}else{
					return 2;//系统出错。
				}
			
			}
		}else{
			return 0;//用户在地址栏乱搞的id
		}
	}

	//处理好友申请
	public function pro_requestadd(){
		if(empty($_SESSION['mybbs_home'][0]['id'])){
			return 10;
		}
		$num=check();
		if($num==9){
			return 9;
		}
		
		header("Content-Type: text/html;charset=utf-8");
		//获得用户希望添加的id
		$id=I('get.id');
		//获得当前用户的id
		$nameid=$_SESSION['mybbs_home'][0]['id'];
		//获得当前用户的用户名
		$users=$_SESSION['mybbs_home'][0]['nickname'];
		$friend=D('friend');
		$friendmap['uid']=['eq',$id];
		//遍历出所有待添加的好友
		$friendlist=$friend->where($friendmap)->select();
		//在friend表有这个id的数据就要判断这个当前用户之前是否已经申请添加了好友或者两人已经是这个好友了
		if($friendlist[0]['num']>=50){
			return 3;//返回超过50人的提示
		}
		if($friendlist){
			//已经是好友了，不能再申请。
			if($friendlist[0]['friend']){
				trim($friendlist[0]['friend'],",");
				$friendarr=explode(',',$friendlist[0]['friend']);
				if(in_array($nameid,$friendarr)){
					return 1;//1为已经是好友
				}
			}
			
			//已经申请过好友但对方还没有同意也没有拒绝，不能再申请。没有申请过就将,$id,加进去waitfriend字段就可以了。
			$map['id']=['eq',$friendlist[0]['id']];
			if($friendlist[0]['waitfriend']){
				$trimstr=trim($friendlist[0]['waitfriend'],",");
				$friendarr=explode(',',$trimstr);
				if(in_array($nameid,$friendarr)){
					return 2;//2为已经申请好友

				}else{
					//对方已经有待添加好友，但待添加好友列表中并没有当前用户
					array_push($friendarr,$nameid);	
					$str=implode(',',$friendarr);	
					$maps['waitfriend']=",".$str.",";
					$res=$friend->where($map)->save($maps);
					if($res){
						return 4;//添加成功
					}else{
						return 0;//添加失败
					}
				}
			}else{
				//对方暂没有待添加好友就走这里
				$maps['waitfriend']=",".$nameid.",";
				$res=$friend->where($map)->save($maps);

				if($res){
					return 4;//添加成功
				}else{
					return 0;//添加失败
				}
			}
		}else{
			$map['uid']=$id;
			$map['friend']='';
			$map['waitfriend']=",".$nameid.",";
			$map['num']=0;
			$res=$friend->add($map);
			if($res){
				return 4;//添加成功
			}else{
				return 0;//添加失败
			}
		}
	}	

	public function pro_search(){

	}
}