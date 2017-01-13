<?php
namespace Admin\Controller;
use Think\Controller;
class MessageController extends EmptyController{
	public function index(){
		$message = D("message");
		$data=$message->pro_index();
		$this->assign($data);
		$this->display();
	}
}