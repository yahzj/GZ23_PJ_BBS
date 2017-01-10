<?php 
//友情链接控制器---黄文明
namespace Admin\Controller;
use Think\Controller;

class LinksController extends EmptyController
{
	//显示所有链接
	public function index()
	{
		$links=D("links");
		$data=$links->pro_index();
		//分配数据。
		$this->assign($data);
		$this->display();
	}
	//更新链接就跳转到这里先查询数据，然后再展示原来的数据到页面
	public function update()
	{
		
		$links=D("links");
		//跳转过去Model类得到要修改id的内容
		$res=$links->pro_update();
		$this->assign("res",$res);
		$this->display();
	}
	//执行更改的数据库操作
	public function action()
	{
		
		$links=D("links");
		//跳转过去将修改的内容写进数据库更新。
		$res=$links->pro_action();
		$this->success($res);
	}

	public function del(){
		$links=D("links");
		//执行删除
		$res=$links->pro_del();
		if($res){
			$this->success("删除成功！",'',3);
		}else{
			$this->error("删除失败",'',3);
		}
	}
	//用来跳转到add.html页面的。
	public function add(){
		$links=D("links");
		//直接跳转到添加页面进行添加
		$this->display();
	}
	//处理添加的控制器
	public function doadd(){
		$links=D("links");
		//到model类执行添加数据到数据库。
		$res=$links->pro_doadd();
		if($res){
			$this->success("添加成功！",'',3);
		}else{
			$this->error("添加失败",'',3);
		}
	}
}


