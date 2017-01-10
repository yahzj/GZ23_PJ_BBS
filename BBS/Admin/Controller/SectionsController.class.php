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
		//dump($data);
		$this->assign($data);
		$this->display();
	}


    //删除板块表单
	public function del(){
    	
    	// 实例化对象
    	$sections = D('sections');
    	// 执行删除，返回受影响行
    	$res = $sections->pro_del();
    	
    	if($res==2){
    		$this->error("你要删除的目录还有子目录存在，不能删除！");
    	}elseif($res==1){
    		$this->success("删除成功！");
    	}else{
    		$this->success("删除失败！");
    	}
    	

    }

         
    // 加载编辑模板
	public function edit(){
    	// 接收用户ID
    	$id = I('get.id') + 0;
    	//dump($id);
    	// 实例化
    	$sections = D('sections');
    	$data=$sections->pro_edit();	
    	$data['title'] = '板块表单修改';
    	$this->assign($data);
    	$this->display();
    }
   
              //编辑模板
	public function doedit(){
       	// 1.使用自动验证，必须走Model层
    	$sections = D('sections');
    	$res=$sections->pro_doedit();
	   
    }

           //增加板块
    public function add(){
    	$sections=D('sections');
    	$data=$sections->pro_add();		
    	$this->assign($data);		
        $this->display();
    }

    public function doadd(){

		$obj = D("sections"); 
			// 根据表单提交的POST数据创建数据对象
		$data=$obj->pro_doadd();
		if($data){        
         	return $this->success('新增成功',U('sections/index'),3);
		}else{
		       // 如果验证失败，则显示错误提示
         	return $this->Error($obj->getError());
    	}
		
    }
    
}