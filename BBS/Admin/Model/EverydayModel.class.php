<?php 
namespace Admin\Model;
use Think\Model;

class EverydayModel extends Model{

	// $_validate 属性定义验证规则。
	protected $_validate = [
		// [验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]]
		// ['email','email','你的邮箱格式不正确！！！'],
		// ['address','require','邮箱地址必填！！'],
		// ['content','1,1024','请输入至少1个字至多1024个字的内容' , 1, 'length' , 3],
		// ['repwd', 'pwd' , '你跟老子不是一块的！' , 1  , 'confirm',3],
	];

	// 自动完成
	protected $_auto = [
		// [完成字段1,完成规则,[完成条件,附加规则]],
		// ['pwd','date',3,'function'],

	];

	public function pro_select()
	{
		// $map['to_date']=date('Y-m-d');
		$list=$this->order('to_date desc')->select();
		// if ($list[0]['to_date']==date('Y-m-d')) {
		if (1) {
			return $list;
		}else{
			$this->pro_add();
		}
	}

	public function pro_add(){

	}
}


