<?php 
namespace Admin\Controller;
use Think\Controller;

class LinksController extends EmptyController
{

	public function index()
	{
		$links=D("links");
		$data=$links->pro_index();
		$this->assign($data);
		$this->display();
	}

	public function update()
	{
		$links=D("links");
		$links->pro_update();
	}

}


