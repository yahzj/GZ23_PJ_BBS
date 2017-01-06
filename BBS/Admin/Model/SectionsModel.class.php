<?php 
namespace Admin\Model;
use Think\Model;

class SectionsModel extends Model{

	// 处理板块表单展示的数据
	public function pro_index(){

		// 得到总行数
		$totalRow = $this->count();
		// 每页显示条数
		$num = 15;
		// 实例化分页类
		$page = new \Think\Page($totalRow , $num);
		// 执行分页查询
		$list = $this->order('id desc')->limit( $page->firstRow . ',' . $page->listRows )->select();

		return [
			// 用户列表
			'list' => $list,
			// 分页按钮
			'show' => $page->show(),
		];
		

	}
}

