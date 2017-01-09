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

		public function pro_del(){

			//设置$link为空，在success方法跳转就是直接返回跳过来的这一页,空就是。
	    	$link='';
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
	    	return [
	    		'res'=>$res,
	    		'link'=>$link,
	    	];
		}

		//多种字段搜索。
		public function fModelSearch()
		{
			
			//得到用户提交的搜索内容
			$search=I('post.search');
			//用户的搜索如果有空格，那么视为多个搜索条件。将其切割到arr数组中。
			$arr=explode(' ',$search);
			//data空数组，放根据用户的搜索得到的全部数据。
			$searcharr=[];
			if(I('post.cardid')!=0){
				$searcharr['cardid']=['eq',I('post.cardid')];
			}
			if(I('post.status')!=0){
				$searcharr['status']=['eq',I('post.status')];
			}
			$data=[];
			//for循环是用户搜索条件有n个空格，就认为有n-2个搜索单独的搜索条件，就要对每一个条件都搜索所有字段。
			for($i=0;$i<count($arr);$i++){
				//每次清空数组是为了防止where方法查询的时候出现and的查询，例如author='1' and content='1';就不对了。
				$map=[];
				$map['author']=['like',"%$arr[$i]%"];
				$map=array_merge($map,$searcharr);
				$data[]=$this->where($map)->select();
				$map=[];
				$map['content']=['like',"%$arr[$i]%"];
				$map=array_merge($map,$searcharr);
				$data[]=$this->where($map)->select();
				$map=[];
				$map['uid']=['like',"%$arr[$i]%"];
				$map=array_merge($map,$searcharr);
				$data[]=$this->where($map)->select();
				$map=[];
				$map['floor']=['like',"%$arr[$i]%"];
				$map=array_merge($map,$searcharr);
				$data[]=$this->where($map)->select();
			}
			//空数组放接下来这个for循环得到的合并新二维数组。
			$newdata=[];
			//因为上面多搜索条件后的结果为一个三维数组，所以要将这个三维数组的每一个值都取出来，重新合并得到新二维数组。
			for($j=0;$j<count($data);$j++){
				$newdata=array_merge($newdata,$data[$j]);
			}

			//下面的for是为了删除newdata里重复的二维数组，并且将二维数组按id顺序排列好。
			//统计一下这个新数组有多少个值。
			$num=count($newdata);
			//inarr空数组放搜索出来的回帖表结果的所有id号。
			$inarr=[];
			//用来放最后的数组结果
			$lasttwodata=[];
			//for循环将newdata二维数组遍历，每一个遍历的结果后，对其键值进行判断是否与之前的重复了，没有重复则写入新数组。
			for($k=0;$k<$num;$k++){
				if(!in_array($newdata[$k]['id'], $inarr)){
						$id=$newdata[$k]['id'];
						$lasttwodata[$id]=$newdata[$k];
						$inarr[]=$newdata[$k]['id'];
				}
			}
			//将所有的结果按键值进行顺序排行。
			ksort($lasttwodata);
			$lastdata=array_values($lasttwodata);
			//是查询的话就给设置一个session放最新的查询数据。
			$_SESSION['sdata']=$lastdata;
			//这里空数组用来放根据用户搜索显示的10条以内的数据。
			$tendata=[];
			//计算根据搜索条件得到的值的数量，有可能大于10，也有可能少于10.
			$lastnum=count($lastdata);
			//根据数组值的数量走不同的条件语句。
			//数组值的数量大于等于10就走这里
			if($lastnum>=10){
				//因为数组值的数量大于10，所以可以取到10条。
				for($m=0;$m<10;$m++){
					$tendata[]=$lastdata[$m];
				}
			//数组值的数量小于10就走这里。
			}else{
				//因为数组值的数量小于10，所以只可以有多少条就取多少条。
				for($m=0;$m<$lastnum;$m++){
					$tendata[]=$lastdata[$m];
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
	}