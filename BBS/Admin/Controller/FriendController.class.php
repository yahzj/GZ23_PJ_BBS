<?php 
//系统通知控制器---黄文明
namespace Admin\Controller;
use Think\Controller;

class FriendController extends EmptyController
{
	//显示所有通知
	public function index()
	{
		$friend=D("friend");
		$data=$friend->pro_index();
		//分配数据。
		$this->assign($data);
		$this->display();
	}
	

	public function del(){
		if(empty(I('get.id'))&&empty($_POST)){
            //失败的跳转
             $this->error("不要乱搞事好不好？",'',3);
        }
		$friend=D("friend");
		//执行删除
		$res=$friend->pro_del();
		if($res){
			$this->success("删除成功！",'',3);
		}else{
			$this->error("删除失败",'',3);
		}
	}
	
}


