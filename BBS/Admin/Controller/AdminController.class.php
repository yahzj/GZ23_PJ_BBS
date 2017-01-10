<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends EmptyController{
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