<?php 
namespace Admin\Model;
use Think\Model;

class SectionsModel extends Model{
    protected $_validate = [
		['name','require','板块名字必须填写'],
		['name','','板块名已存在',0,'unique',3],
	];

           // 自动完成
	protected $_auto = [
		// // [完成字段1,完成规则,[完成条件,附加规则]],
		// // ['pwd','md5',3,'function'],

		// ['pwd','myHash',3,'function'],
		// ['address','trim',3,'function'],
	];


	// 处理板块表单展示的数据
	public function pro_index(){
		$map=[];
		if(!empty($_GET)){
			$arr=I('get.');
			foreach($arr as $key => $val){
				if($val && ($key =="name")||($key =="administrators")){
					$map[$key]=['like',"%".$val."%"];
				}
				
				if($key=="status" && $val !=3){
					$map[$key]=['eq',$val];
				}
			}
			$totalRow=$this->where($map)->count();
			
		}else{
			// 得到总行数
		$totalRow = $this->count();
		}
		
		// 执行分页查询
		$list = $this->where($map)->order('id asc')->select();
		//一定要搜索所有的数据，得到所有的id和名字作为html要显示的父类名字。
		$lists=$this->select();
		//准备一个数组用来存放键为id，值为name。用来给最后的数据加入父类名字，方便显示的。0为根目录。
		$parent_name=[0=>"根目录"];
		//遍历出所有的id作为键，所有的name作为值存放。
		foreach($lists as $key=>$val){
			$parent_name[$val['id']]=$val['name'];
		}
		
		if(!empty(I('get.parentname'))){
			foreach($parent_name as $key=>$val){
				if(substr_count($val,I('get.parentname'))<1){
					unset($parent_name[$key]);
				}
			}
			foreach($list as $key=>$val){
				if(!array_key_exists($val['parent_id'], $parent_name)){
					unset($list[$key]);
				}
			}
			$totalRow=count($list);
		}
		// 获取会员信息
		$us=D('users');
		$ures=$us->field('id,nickname')->select();
		foreach ($ures as  $val) {
			$users[$val['id']]=$val['nickname'];

		}

        $status = ['锁定','正常','高亮'];
		// 基本处理
		foreach($list as $key => &$val){
			$val['status'] = $status[ $val['status'] ];
			//新增一个parent_name键，分别存放上面遍历出来存放的根目录等。这样$list数组到了index.html页面就能显示父级名字了。
			$val['parent_name']=$parent_name[$val['parent_id']];
			$adm=trim($val['administrators'],',');
			$adm=explode(',', $adm);
			foreach ($adm as &$v) {
				$v=$users[$v];
			}
			$val['administrators']=implode(',',$adm);
		}

		//将所有数据重新按照键为0开始排列。得到一个新数组。
		$arrlist=array_values($list);
		$p=$_GET['p'];
		//每页显示条数。
		$row=10;
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

		// 实例化分页类
		$page = new \Think\Page($totalRow,$row);
		return [
			// 用户列表
			'list' => $lastlist,
			// 分页按钮
			"show" => $page->show(),
		];		

	}

	//获得用户要处理的ip，将原始数据读出并处理后发送到edit.html
    public function pro_edit(){
		$id=I("get.id");
		$res=$this->find($id);
		//实例化一个空模型。
		$aaa=D();
		//将它按照path和id加起来之后进行排序。例如path为0，id为1，则它为0,1.后面的就是0,2，或者0,1,1等。达到了分类显示。
		$sql="select * from mybbs_sections order by concat(path,id)";
		$list = $aaa->query($sql);
		foreach($list as $key=>&$val){
			$arr=explode(",",$val['path']);
			$num=(count($arr)-1)*2;
			$newstr=str_repeat("&nbsp;", $num)."--";
			$val['name']=$newstr.$val['name'];
		}
		return[
			"res"=>$res,
			"list"=>$list,
		];
	}

	public function pro_doedit(){
		//获得post过来的数据
		$post=I("post.");
		//如果传过来的父类id是0，那么它就是根目录下面的，所以要给path为0,
		if($post['parent_id']==0){
			$post['path']="0,";

		}else{
			//如果传过来的父类id不是0，那么就根据这个父类id值搜索这一条数据。也就是搜索它的未来的父类的数据。
			$res=$this->find($post['parent_id']);
			//将搜索出来的数据的path路径加上post过来的父类id就得到最新路径。将它加入到post数组里面一起修改
			$post['path']=$res['path'].$post['parent_id'].",";
			//将搜索出来的id，作为父类id加到post数组里到一起修改。
			$post['parent_id']=$res['id'];
		}
		$msg=$this->save($post);
		return $msg;
	}

	public function pro_add(){
		$aaa=D();
		//将它按照path和id加起来之后进行排序。例如path为0，id为1，则它为0,1.后面的就是0,2，或者0,1,1等。达到了分类显示。
		$sql="select * from mybbs_sections order by concat(path,id)";
		//将排序后的数据返回去显示出来给用户。
		$list = $aaa->query($sql);
		foreach($list as $key=>&$val){
			$arr=explode(",",$val['path']);
			$num=(count($arr)-1)*2;
			$newstr=str_repeat("&nbsp;", $num)."--";
			$val['name']=$newstr.$val['name'];
		}
		return[
			'list'=>$list,
		];
	}
	//跟修改的pro_edit方法一样道理
 	public function pro_doadd(){
		$post=I('post.');
		
		if($post['parent_id']==0){
			$post['path']="0,";

		}else{
			$res=$this->find($post['parent_id']);
			$post['path']=$res['path'].$post['parent_id'].",";
			$post['parent_id']=$res['id'];
		}
		$msg=$this->add($post);
		return $msg;
	}

	public function pro_del(){
		if($_POST){
			$ids=I('post.');
			$arr=[];
			  foreach($ids as $key=>$val){
                $map['parent_id']=['eq',$val];
                //根据这个数据的值去搜索是否有子分类数据。
                $list=$this->where($map)->select();
                if(count($list)>0){
                    //有子类，就将这个id给一个数组，返回控制器告诉用户这个id有子类，不能删除！
                    $arr[]=$val;
                }else{
                    //小于0代表没有子分类，可以删除。
                    $this->delete($val);

                }

            }
			return $arr;
			
			
		}else{
			// 接收用户ID
	    	$id = I('get.id');
	    	$id += 0;
	    	//将传过来的id给数组。
	    	$map['parent_id']=['eq',$id];
	    	//根据这个数据的值去搜索是否有子分类数据。
	    	$list=$this->where($map)->select();
	    	//如果大于0则代表有子分类，不能删除！
	    	if(count($list)>0){
	    		//返回2为了提示给用户有子分类。
	    		return "2";
	    	}else{
	    		//小于0代表没有子分类，可以删除。
	    		$res=$this->delete($id);
	    		if($res){
	    			//返回1为了提示给用户，删除成功了。
	    			return "1";
	    		}else{
	    			//删除失败。
	    			return "0";
	    		}
	    		
	    	}
		}
    	
	}
}

