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
			//得到follow表的数据。
			$list=$this->limit($page->firstRow.",".$page->listRows)->select();
			//将status的状态改为中文。
			$status=['只对楼主可见','全部可见'];
			foreach($list as $key=>&$val){
				$val['status']=$status[$val['status']];
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
			
			
			//for循环是用户搜索条件有n个空格，就认为有n-2个搜索单独的搜索条件，就要对每一个条件都搜索所有字段。
			if(I('post.search_content')){
				$data['content']="f.content like '%".I('post.search_content')."%'";
			}
			if(I('post.search_username')){
				$data['username']="u.username like '%".I('post.search_username')."%'";
			}
			if(I('post.status')){
				$data['status']="f.status=".I('post.status');
			}
			
			if(count($data)>0){
				$str="where ".implode(" and ",$data)." and u.id=f.uid";
				$sql="select f.*,u.nickname from mybbs_follow f,mybbs_users u $str";
			}else{
				$sql="select * from mybbs_follow";
			}
			$fsearch=D();
			$list=$fsearch->query($sql);
			dump($list);
			//是查询的话就给设置一个session放最新的查询数据。
			$_SESSION['sdata']=$list;
			//这里空数组用来放根据用户搜索显示的10条以内的数据。
			$tendata=[];
			//计算根据搜索条件得到的值的数量，有可能大于10，也有可能少于10.
			$lastnum=count($list);
			//根据数组值的数量走不同的条件语句。
			//数组值的数量大于等于10就走这里
			if($lastnum>=10){
				//因为数组值的数量大于10，所以可以取到10条。
				for($m=0;$m<10;$m++){
					$tendata[]=$list[$m];
				}
			//数组值的数量小于10就走这里。
			}else{
				//因为数组值的数量小于10，所以只可以有多少条就取多少条。
				for($m=0;$m<$lastnum;$m++){
					$tendata[]=$list[$m];
				}
			}
			//将status的状态改为中文。
			$status=['只对楼主可见','全部可见'];
			foreach($tendata as $key=>&$val){
				$val['status']=$status[$val['status']];
			}
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
		    	dump("get");
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

		
	}