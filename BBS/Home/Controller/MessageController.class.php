<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends EmptyController{
	//显示所有数据和搜索后的数据
	public function index(){
		$message = D("message");
		if(empty(I('get.'))){
			$list=$message->sms();
		}else{
			switch(I('get.type')){
			case 'sms'://短消息
				$list=$message->sms();
				break;
			case 'notice'://系统通知，评论通知
				$message->notice();
				break;
			case 'request'://好友请求
				$message->request();
				
				break;
			default:
				$this->error("没有你要找的网页");
				break;
		}
		}
		if($list===false){
			$this->error("没有你要找的网页");
		}
		$this->assign($list);
		$this->display();
	}
	//删除消息
	public function del(){
		$message=D('message');
		$res=$message->pro_del();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
		
	}

	public function tag(){
		$message=D('message');
		$message->pro_tag();
	}

	public function add(){

		$message=D('message');
		$res=$message->pro_add();
		if($res){
			$this->success("发送成功");
		}else{
			$this->error("发送失败");
		}
	}
}

