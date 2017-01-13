<?php
namespace Admin\Controller;
use Think\Controller;
class MessageController extends EmptyController{
	//显示所有数据和搜索后的数据
	public function index(){
		$message = D("message");
		//跳转到Model的pro_index()方法里。
		$data=$message->pro_index();
		$this->assign($data);
		$this->display();
	}
	//删除消息
	public function del(){
		//单条删除和批量删除的条件都没有，直接返回到上一页。
		if(empty(I('get.'))&&empty(I('post.'))){
			$this->error('不要搞事好不好？');
		}
		$message = D("message");
		//跳转到Model的pro_index()方法里。
		$res=$message->pro_del();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败!");
		}
	}
}