<?php 
namespace Admin\Model;
use Think\Model;

class SectionsModel extends Model{
    protected $_validate = [
		// [验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]]

		// ['email','email','你的邮箱格式不正确！！！'],
		// ['address','require','邮箱地址必填！！'],
		// ['pwd','3,6','你太短了，老娘不要！！' , 1, 'length' , 3],
		// ['repwd', 'pwd' , '你跟老子不是一块的！' , 1  , 'confirm',3],
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

		// 得到总行数
		$totalRow = $this->count();
		// 每页显示条数
		$num = 6;
		// 实例化分页类
		$page = new \Think\Page($totalRow , $num);
		// 执行分页查询
		$list = $this->order('id asc')->limit( $page->firstRow . ',' . $page->listRows )->select();

        $status = ['锁定','正常','高亮'];
		// 基本处理
		foreach($list as $key => &$val){
			$val['status'] = $status[ $val['status'] ];
		}

		return [
			// 用户列表
			'list' => $list,
			// 分页按钮
			'show' => $page->show(),
		];		

	}

	//获得用户要处理的ip，将原始数据读出并处理后发送到edit.html
    public function pro_edit(){
		$id=I("get.id");
		$res=$this->find($id);
		$aaa=D();
		$sql="select * from mybbs_sections order by concat(path,id)";
		$list = $aaa->query($sql);
		return[
			"res"=>$res,
			"list"=>$list,
		];
	}

	public function pro_doedit(){
		$post=I("post.");
		if($post['parent_id']==0){
			$post['path']="0,";

		}else{
			$res=$this->find($post['parent_id']);
			$post['path']=$res['path'].$post['parent_id'].",";
			$post['parent_id']=$res['id'];
		}
		
		dump($res);
		dump(11111);
		dump($post);
		$msg=$this->save($post);
		return $msg;
	}

	public function pro_add(){
		$aaa=D();
		$sql="select * from mybbs_sections order by concat(path,id)";
		$list = $aaa->query($sql);
		return[
			'list'=>$list,
		];
	}

 	public function pro_doadd(){
		$post=I('post.');
		
		if($post['parent_id']==0){
			$post['path']="0,";

		}else{
			$res=$this->find($post['parent_id']);
			$post['path']=$res['path'].$post['parent_id'].",";
			$post['parent_id']=$res['id'];
		}
		dump($post);
		$msg=$this->add($post);
		return $msg;
	}

	public function pro_del(){
		// Admim/Sections/del
    	// 接收用户ID
    	$id = I('get.id');
    	$id += 0;
    	$map['parent_id']=['eq',$id];
    	$list=$this->where($map)->select();
    	if(count($list)>0){
    		return "2";
    	}else{
    		$res=$this->delete($id);
    		if($res){
    			return "1";
    		}else{
    			return "0";
    		}
    		
    	}
	}
}

