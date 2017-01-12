<?php
	namespace Admin\Model;
	use Think\Model;
	class FollowModel extends Model{
		public function pro_index(){
			//每页显示10行
			$row=10;
			//得到follow表数据的总条数
			$totalRow=$this->count();
			//实例化page类，得到$page对象。
			$page=new \Think\Page($totalRow,$row);
			//得到follow表限制后的10条数据。
			$list=$this->order('id')->limit($page->firstRow.",".$page->listRows)->select();
			//others用来查询mybbs_users表和mybbs_subject表和回帖表关联数据的。
			$others=D();
			//查询mybbs_users表的id和昵称
			$sql="select id,nickname from mybbs_users";
			$users=$others->query($sql);
			//设置一个空数组，用来存放数据。
			$users_list=[];
			//将查询出来的id作为users_list数组的键，nickname作为值存放。方便后面的使用。
			foreach($users as $key=>$val){
				$users_list[$val['id']]=$val['nickname'];
			}
			//查询mybbs_subject发帖表的id和帖子名字
			$sql="select id,name from mybbs_subject";
			$subject=$others->query($sql);	
			//设置一个空数组，用来存放数据。		
			$subject_list=[];
			//将查询出来的id作为subject_list数组的键，name作为值存放。方便后面的使用。
			foreach($subject as $key=>$val){
				$subject_list[$val['id']]=$val['name'];
			}
			//将status的状态改为中文。
			$status=['只对楼主可见','全部可见'];
			foreach($list as $key=>&$val){
				//改变状态为中文
				$val['status']=$status[$val['status']];
				//设置一个新的subject(帖子标题)接收上面查询出来的处理过的mybbs_subject的数据
				$val['subject']=$subject_list[$val['cardid']];
				//设置一个新的users(回帖人)接收上面查询出来的处理过的mybbs_users的数据
				$val['nickname']=$users_list[$val['uid']];
				//设置一个新的authorname(发帖人)接收上面查询出来的处理过的mybbs_users的数据
				$val['authorname']=$users_list[$val['authorid']];
			}
			
			//返回值出去
			return [
				"list"=>$list,
				"show"=>$page->show()
			];
		}

		//多种字段搜索。
		public function fModelSearch()
		{
			
			
			//分别接收用户搜索条件，并且将它们设定好相关的sql语句部分。
			if(I('post.search_content')){
				//输入了搜索内容的话，就设置搜索内容的sql语句部分。
				$data['content']="f.content like '%".I('post.search_content')."%'";
			}
			if((I('post.search_nickname')&&!I('post.search_author'))||(I('post.search_nickname')&&I('post.search_author'))){
				$data['nickname']="u.nickname like '%".I('post.search_nickname')."%'";
			}elseif(!I('post.search_nickname')&&I('post.search_author')){
				$data['nickname']="u.nickname like '%".I('post.search_author')."%'";
			}
			
			if(I('post.search_subject')){
				$data['subject']="s.name like '%".I('post.search_subject')."%'";
			}
			
			if(I('post.status')){
				$data['status']="f.status=".I('post.status');
			}
		
			if(count($data)>0){
				$str="where ".implode(" and ",$data)." and u.id=f.uid and f.cardid=s.id";
				$sql="select f.*,u.nickname,s.name from mybbs_follow f,mybbs_users u,mybbs_subject s $str order by f.id";
			}else{
				$sql="select * from mybbs_follow";
			}
			$fsearch=D();
			$list=$fsearch->query($sql);
			//dump($list);

			//others用来查询mybbs_users表和mybbs_subject表和回帖表关联数据的。
			$others=D();
			//查询mybbs_users表的id和昵称
			$sql="select id,nickname from mybbs_users";
			$users=$others->query($sql);
			//设置一个空数组，用来存放数据。
			$users_list=[];
			//将查询出来的id作为users_list数组的键，nickname作为值存放。方便后面的使用。
			foreach($users as $key=>$val){
				$users_list[$val['id']]=$val['nickname'];
			}
			//查询mybbs_subject发帖表的id和帖子名字
			$sql="select id,name from mybbs_subject";
			$subject=$others->query($sql);	
			//设置一个空数组，用来存放数据。		
			$subject_list=[];
			//将查询出来的id作为subject_list数组的键，name作为值存放。方便后面的使用。
			foreach($subject as $key=>$val){
				$subject_list[$val['id']]=$val['name'];
			}
			//准备一个空数组用来存放对键值重新排序后的数据
			$lastdata=[];
			//将status的状态改为中文。
			$status=['只对楼主可见','全部可见'];
			foreach($list as $key=>&$val){
				//改变状态为中文
				$val['status']=$status[$val['status']];
				//设置一个新的subject(帖子标题)接收上面查询出来的处理过的mybbs_subject的数据
				$val['subject']=$subject_list[$val['cardid']];
				//设置一个新的users(回帖人)接收上面查询出来的处理过的mybbs_users的数据
				$val['nickname']=$users_list[$val['uid']];
				//设置一个新的authorname(发帖人)接收上面查询出来的处理过的mybbs_users的数据
				$val['authorname']=$users_list[$val['authorid']];
				//将搜索得到的回帖的id全部作为$lastdata的键值。要对它们重新进行按照键值排。
				$lastdata[$val['id']]=$val;
			}
			ksort($lastdata);
			$testdata=[];
			foreach($lastdata as $key=>$val){
				if(I('post.search_nickname')&&I('post.search_author')){
					if(substr_count($val['authorname'],I('post.search_author'))>0){
						$testdata[]=$val;
					}
					
				}else{
					$testdata[]=$val;
				}
				
			}
			
			//是查询的话就给设置一个session放最新的查询数据。
			$_SESSION['sdata']=$testdata;
			//这里空数组用来放根据用户搜索显示的10条以内的数据。
			$tendata=[];
			//计算根据搜索条件得到的值的数量，有可能大于10，也有可能少于10.
			$lastnum=count($testdata);
			//dump($lastnum);
			//根据数组值的数量走不同的条件语句。
			//数组值的数量大于等于10就走这里
			if($lastnum>=10){
				//因为数组值的数量大于10，所以可以取到10条。
				for($m=0;$m<10;$m++){
					$tendata[]=$testdata[$m];
				}
			//数组值的数量小于10就走这里。
			}else{
				//因为数组值的数量小于10，所以只可以有多少条就取多少条。
				for($m=0;$m<$lastnum;$m++){
					$tendata[]=$testdata[$m];
					//dump($data[$m]);
					
				}
			}
			//dump($tendata);
			//每页显示10行
			$srow=10;
			//实例化page类，得到$page对象。
			$page=new \Think\Page($lastnum,$srow);

			//返回最后的结果。
			return [
				'list'=>$tendata,
				'show'=>$page->show(),
			];
		}

		public function sPage(){
			//每页显示10行
			$srow=10;
			//实例化page类，得到$page对象。
			$page=new \Think\Page(count($_SESSION['sdata']),$srow);
			//返回最后的结果。
			return $page->show();
		}


		public function pro_del(){
			//设置$link为空，在success方法里空就是跳转回上一页，给链接就是跳转到链接的地址。
			$link='';

			if(!empty($_GET)){
				//获得需要删除的id
		    	$id=I('get.id');
		    	//防止id乱写
		    	$id+=0;
		    	/*
		    		如果删除的是最后一条数据，并且所有的数据取余显示的条数等于1，
		    		则说明最后一页只有一条数据。success方法就要跳回到上一页。否则调回原页面会无数据显示。
		    	*/
		    	//dump($this->max('id'));
		    	if($id==$this->max('id') && $this->count()%10==1){
		    		//先得到跳转过来的网址，并且将它用/分隔到数组中。
		    		$arrs=explode("/",$_SERVER["HTTP_REFERER"]);
		    		//得到$arrs数组的最大下标值。
			    	$num=count($arrs)-1;
			    	//将$arrs的最后一个值(例如:10.html)用.切割成数组。
			    	$arr=explode(".",$arrs[$num]);
			    	//删除$arrs数组的最后一个值
			    	array_pop($arrs);
			    	//$arr[0]-1 得到上一页的页数，覆盖之前的页数。如9覆盖10.
			    	$arr[0]=$arr[0]-1;
			    	//将最新的$arr数组弄成字符串(如9.html)
			    	$arrs[$num]=implode(".",$arr);
			    	//再把$arrs数组用/拼接成新的链接，当删除成功的时候就可以跳转回去最新正确的页面了。
		    		$link=implode("/",$arrs);
		    	}

		    	//执行删除
		    	$res=$this->delete($id);
			}else{
				$map=[];
				foreach($_POST as $key=>$val){
					$map[]=$val;
				}

				if(in_array($this->max('id'),$_POST) && $this->count()%10==count($_POST)){
		    		//先得到跳转过来的网址，并且将它用/分隔到数组中。
		    		$arrs=explode("/",$_SERVER["HTTP_REFERER"]);
		    		//得到$arrs数组的最大下标值。
			    	$num=count($arrs)-1;
			    	//将$arrs的最后一个值(例如:10.html)用.切割成数组。
			    	$arr=explode(".",$arrs[$num]);
			    	//删除$arrs数组的最后一个值
			    	array_pop($arrs);
			    	//$arr[0]-1 得到上一页的页数，覆盖之前的页数。如9覆盖10.
			    	$arr[0]=$arr[0]-1;
			    	//将最新的$arr数组弄成字符串(如9.html)
			    	$arrs[$num]=implode(".",$arr);
			    	//再把$arrs数组用/拼接成新的链接，当删除成功的时候就可以跳转回去最新正确的页面了。
		    		$link=implode("/",$arrs);
		    	}
		    	$dels['id']=['in',$map];
				$res=$this->where($dels)->delete();
			}
			
	    	return [
	    		'res'=>$res,
	    		'link'=>$link,
	    	];
		}

		//用户点击详情之后的处理。
		public function pro_show(){

			$id=I("get.cardid");
			$subject=D('subject');
			$list=$subject->find($id);
			$users=D('users');
			$users_list=$users->find($list['uid']);
			$list['nickname']=$users_list['nickname'];
			$users=D('sections');
			$users_list=$users->find($list['section_id']);
			$list['section_name']=$users_list['name'];
			return $list;
		}

	}