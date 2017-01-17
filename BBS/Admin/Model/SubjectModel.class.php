<?php 
namespace Admin\Model;
use Think\Model;

class SubjectModel extends Model{

	// $_validate 属性定义验证规则。
	protected $_validate = [
		// [验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]]
		// ['email','email','你的邮箱格式不正确！！！'],
		// ['address','require','邮箱地址必填！！'],
		['content','1,1024','请输入至少1个字至多1024个字的内容' , 1, 'length' , 3],
		// ['repwd', 'pwd' , '你跟老子不是一块的！' , 1  , 'confirm',3],
	];

	// 自动完成
	protected $_auto = [
		// [完成字段1,完成规则,[完成条件,附加规则]],
		// ['pwd','md5',3,'function'],

	];

	// 处理用户显示列表数据
	public function pro_index(){
		$data=I('get.');

		if(!empty($data)){
			$map=[];//定义一个查询条件的数组
			foreach($data as $k=>$v){
				if($v&&($k=='name'||$k=='search_nickname')){
					$map[$k]=['like','%'.$v.'%'];//如果GET传过来的键为username和nickname 那么搜索表达式用like
					}elseif(in_array($v,[0,1,2,3])&&($k=='status')){
				$map[$k]=['eq',$v];
			}
			}
			// dump($map);
			$totalRow=$this->where($map)->count();
			// dump($totalRow);
			
		}else{
			$totalRow=$this->count();//计算数据总行数
		}
		// 定义每页显示行数
		$rows = 10;
			//判断页数
		if ($_GET['p']>ceil($totalRow/$rows)) {
			$_GET['p']=ceil($totalRow/$rows);
		};
		// 实例化分页类
		$page = new \Think\Page( $totalRow,$rows );
		// 执行查询
		$list = $this->where($map)->order('`id` ')->limit( $page->firstRow . ',' . $page->listRows   )->select();

		// 获取会员信息
		$us=D('users');
		$ures=$us->field('id,nickname')->select();
		foreach ($ures as  $val) {
			$users[$val['id']]=$val['nickname'];

		}		
		
		// 获取板块信息
		$se=D('sections');
		$res=$se->field('id,name')->select();
		foreach ($res as  $val) {
			$seciton_id[$val['id']]=$val['name'];
		}
		$status = ['锁定','正常','高亮'];
		// 基本处理
		foreach($list as $key => &$val){
			$val['status'] = $status[ $val['status'] ];
			$val['content'] = substr($val['content'],0,128);
			$val['section_id'] =$seciton_id[$val['section_id']];
			$val['uid'] =$users[$val['uid']];
		}

		return [
			// 返回用户信息
			'list' => $list,
			'show' => $page->show(),
		];
	}

	// 添加新数据
	public function pro_add(){
		$post=I('post.');
		// 自动验证并判断
		if($this->create($post)){    
			// 写入数据到数据库并判断
			$res=$this->add();
			return $res;
    	}else{
    		return false;
    	}
	}


	// 删除数据
	public function pro_delete(){
		$get=I('get.id');
		// 删除数据并判断
		$res=$this->delete($get);
		return $res;
	}



	// 数据编辑处理，加入自动验证
	public function pro_edit(){
		
		// 在model层接收用户提交的数据
		$post = I('post.');
    	// 创建数据创建对象，会触发自动验证
    	if($this->create($post)){
    		// 进行写入，并判断
    		$res=$this->save();
			return $res;
		}else{
			return false;
		}
	}
}


