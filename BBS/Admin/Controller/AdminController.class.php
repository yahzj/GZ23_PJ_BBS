<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends EmptyController{
          
           //显示后台管理主页
	public function index(){
		// 实例化Model类
		$admin=D('admin');
		// 走Model层处理数据
		$data=$admin->pro_index();
		$data['title']='后台管理表单';
		$this->assign($data);
		$this->display();
	}

    
             //删除表单
	public function del(){
	    	// Admim/Sections/del
	    	// 接收用户ID
	    	$id = I('get.id');
	    	$id += 0;

	    	// 实例化对象
	    	$admin = D('admin');
	    	// 执行删除，返回受影响行
	    	$res = $admin->delete($id);

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
	    	$admin = D('admin');
	    	// find : 只找一条信息
	    	// 查询用户信息
	    	$info = $admin->find($id);
	    	 //dump($info);
	    	$data['info'] = $info;
	    	$data['title'] = '板块表单修改';

	    	$this->assign($data);
	    	$this->display();
               }

               //编辑管理员
	public function doedit(){
	       	// 1.使用自动验证，必须走Model层
	    	$admin = D('admin');
	    	// 2.调用model层的数据处理方法
	    	$msg = $admin->pro_edit();
	    	//dump($msg);
	    	// 3.跳转
	    	$this->success($msg,"",5);
	     
           }
      

	//后台管理员注册
         public function add(){
		$this->display();
	}

         public function doadd(){

	$obj = D("admin"); 
	// 根据表单提交的POST数据创建数据对象
	$data=$obj->pro_add();
	if($data){        
         	      return $this->success('新增成功',U('admin/index'),5);   
	}else{
                     // 如果验证失败，则显示错误提示
         	    return $this->Error($obj->getError());
    	}		
       }

}