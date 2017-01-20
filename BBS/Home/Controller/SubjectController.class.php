<?php
namespace Home\Controller;//代码编辑:刘欣;
use Think\Controller;

class SubjectController extends EmptyController{
	public function index(){
		//echo'我是你大哥';
		$subject=D('Subject');
		$data=$subject->pro_index();
		$this->assign($data);
		$this->display();

	}
	//处理新回复信息。需要用户Id,session里面的Id
	//如果session里面不存在Id,那么请登录
	public function dofollow(){
		$subfollow=D('subject');
		$res=$subfollow->pre_dofollow();
		if($res){        
 	  		return $this->success('新增成功',U('index'),3);   
		}else{
            // 如果验证失败，则显示错误提示
	   			return $this->Error($user->getError());
     	}
	}

}