<?php 
namespace Home\Widget;
use Think\Controller;

class CateWidget extends Controller {
	public function user_data(){
		$user=D('Admin/users');
		$data=I('session.');
		$this->assign($data);
		$this->display('Cate:user_data');
	}
	public function linklist(){
		$link=D('Admin/Links');
		$data['link']=$link->select();
		$this->assign($data);
		$this->display('Cate:linklist');
	}
}



