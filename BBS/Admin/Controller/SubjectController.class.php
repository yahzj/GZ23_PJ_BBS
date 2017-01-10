<?php
namespace Admin\Controller;
use Think\Controller;

class SubjectController extends EmptyController{
	//查询
	public function index(){

		$obj=D('subject');

		$data=$obj->pro_index();
		$data['title']='主题列表';

		$this->assign($data);

		$this->display();
	}
	//添加
	public function add(){

		if ($_POST['name']) {
			$obj = D("subject"); 
			// 根据表单提交的POST数据创建数据对象
			$data=$obj->pro_add();
			if($data){        
				return $this->success('新增成功',U('subject/index'),5);   
			}else{
    		// 如果验证失败，则显示错误提示
	    	return $this->Error($obj->getError());
	    	}
		}else{
			$sections=D('sections');

			$data['list']=$sections->field('id,name')->select();

			$this->assign($data);

			$this->display();
		}
		
	}

	//删除
	public function delete(){
		$obj=D('subject');

		$data=$obj->pro_delete();

		if($data){    
			return $this->success('删除成功','',5);   
		}else{
    		// 如果验证失败，则显示错误提示
	    	return $this->Error($obj->getError());
    	}
	}

	//修改
	public function edit(){
		$get=I('get.id')+0;
		if ($_POST['id']) {

			$obj=D('subject');

			$data=$obj->pro_edit();
			if ($data) {
				$this->success('修改成功',U('subject/index'),5);   
			}else{
				// 如果验证失败，则显示错误提示
		    	$this->Error($obj->getError());
			}
		}else{
		$obj=D('subject');

		$data['info']=$obj->find($get);

		$sections=D('sections');

		$data['list']=$sections->field('id,name')->select();

		$this->assign($data);

		$this->display();
		}
	}
}