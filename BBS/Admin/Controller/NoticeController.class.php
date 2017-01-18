<?php 
//系统通知控制器---黄文明
namespace Admin\Controller;
use Think\Controller;

class NoticeController extends EmptyController
{
	//显示所有通知
	public function index()
	{
		$notice=D("notice");
		$data=$notice->pro_index();
		//分配数据。
		$this->assign($data);
		$this->display();
	}
	//更新通知就跳转到这里先查询要更新的数据，然后再展示原来的数据到页面
	public function update()
	{
		
		$notice=D("notice");
		//跳转过去Model类得到要修改id的内容
		$res=$notice->pro_update();
		$this->assign("res",$res);
		$this->display();
	}
	
	//执行更改的数据库操作
	public function action()
	{
		
		$notice=D("notice");
		//跳转过去将修改的内容写进数据库更新。
		$res=$notice->pro_action();
		if($res){
			$this->success('更新成功','index');
		}else{
			$this->error("更新失败");
		}
		
	}

	public function del(){
		if(empty(I('get.id'))&&empty($_POST)){
            //失败的跳转
             $this->error("不要乱搞事好不好？",'',3);
        }
		$notice=D("notice");
		//执行删除
		$res=$notice->pro_del();
		if($res){
			$this->success("删除成功！",'',3);
		}else{
			$this->error("删除失败",'',3);
		}
	}
	//用来跳转到add.html页面的。
	public function add(){
		dump($_SESSION);
		$notice=D("notice");
		//直接跳转到添加页面进行添加
		$this->display();
	}
	//处理添加的控制器
	public function doadd(){
		$notice=D("notice");
		//到model类执行添加数据到数据库。
		$res=$notice->pro_doadd();
		if($res){
			$this->success("添加成功");
		}else{
			$this->error("添加失败");
		}
	}

	public function send(){
		$notice=D('notice');
		$res=$notice->pro_send();
		if($res){
			$this->success("发送成功！",'',3);
		}else{
			$this->error("发送失败",'',3);
		}
	}
}


