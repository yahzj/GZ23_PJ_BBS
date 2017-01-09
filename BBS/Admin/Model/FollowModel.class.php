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
			//返回值出去
			return [
				"list"=>$list,
				"show"=>$page->show()
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
			$data=[];
			//for循环是用户搜索条件有n个空格，就认为有n-2个搜索单独的搜索条件，就要对每一个条件都搜索所有字段。
			for($i=0;$i<count($arr);$i++){
				//每次清空数组是为了防止where方法查询的时候出现and的查询，例如author='1' and content='1';就不对了。
				$map=[];
				$map['author']=['like',"%$arr[$i]%"];
				$data[]=$this->where($map)->select();
				$map=[];
				$map['content']=['like',"%$arr[$i]%"];
				$data[]=$this->where($map)->select();
				$map=[];
				$map['uid']=['like',"%$arr[$i]%"];
				$data[]=$this->where($map)->select();
				$map=[];
				$map['cardid']=['like',"%$arr[$i]%"];
				$data[]=$this->where($map)->select();
				$map=[];
				$map['floor']=['like',"%$arr[$i]%"];
				$data[]=$this->where($map)->select();
				$map=[];
				$map['status']=['like',"%$arr[$i]%"];
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

		public function spage(){
			//每页显示10行
			$srow=10;
			//实例化page类，得到$page对象。
			$page=new \Think\Page(count($_SESSION['sdata']),$srow);
			//返回最后的结果。
			return $page->show();
		}
	}