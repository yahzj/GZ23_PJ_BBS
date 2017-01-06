<?php
	namespace Admin\Model;
	use Think\Model;
	class FollowModel extends Model{
		public function pro_index(){
			$row=10;
			$totalRow=$this->count();
			$page=new \Think\Page($totalRow,$row);
			$list=$this->limit($page->firstRow.",".$page->listRows)->select();
			return [
				"list"=>$list,
				"show"=>$page->show()
			];
		}
	}