<?php
namespace Admin\Controller;
use Think\Controller;

class SectionsController extends EmptyController{
	//显示板块表单主页
	public function index(){
		// 实例化Model类
		$sections=D('sections');
		// 走Model层处理数据
		$data=$sections->pro_index();
		$data['title']='板块表单';
		$this->assign($data);
		$this->display();
	}

	 public function del(){
    	// Admim/Sections/del
    	// 接收用户ID
    	$id = I('get.id');
    	$id += 0;

    	// 实例化对象
    	$sections = D('sections');
    	// 执行删除，返回受影响行
    	$res = $sections->delete($id);

    	echo '你要删除的ID：' . $id . '<br>';
    	//echo '删除结果：';
    	//dump($res);
    	
    	if($res){
    		// 默认返回上一个URL(来源地址)
	    	$this->success( '删除成功!','',5);
    	}else{
    		$this->error('删除失败');
    	}

         }
                 // 加载编辑模板
	       public function edit(){
	    	// 接收用户ID
	    	$id = I('get.id') + 0;
	    	//dump($id);
	    	// 实例化
	    	$sections = D('sections');
	    	// find : 只找一条信息
	    	// 查询用户信息
	    	$info = $sections->find($id);
	    	 //dump($info);
	    	$data['info'] = $info;
	    	$data['title'] = '板块表单修改';

	    	$this->assign($data);
	    	$this->display();
	    }
   
 public function doedit(){
           // 1.使用自动验证，必须走Model层
    	$sections = D('sections');
    	// 2.调用model层的数据处理方法
    	$msg = $sections->pro_edit();
    	// 3.跳转
    	$this->success($msg,"",50);
     
    }

     

}