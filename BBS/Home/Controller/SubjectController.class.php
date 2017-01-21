<?php
namespace Home\Controller;//代码编辑:刘欣;
use Think\Controller;

class SubjectController extends EmptyController{
	public function index(){
		//echo'我是你大哥';
		$subject=D('Subject');
		$data=$subject->pro_index();
		$data['followsid']=@$_SESSION['mybbs_home']['0']['id'];
		$this->assign($data);
		$this->display();

	}
	//处理新回复信息。需要用户Id,session里面的Id
	//如果session里面不存在Id,那么请登录
	public function dofollow(){
		//dump($_SESSION);
		if($_SESSION['mybbs_home']['0']['id']){
			$subfollow=D('subject');
			$res=$subfollow->pre_dofollow();
			if($res){        
	 	  		return $this->success('回复成功','',1);   
			}else{
	            // 如果验证失败，则显示错误提示
		   			return $this->Error($user->getError());
	     	}
	     }else{
	     	$this->success('亲,你还没有登录,请登录!',U('Home/login/login'),1);  
	     }
		
	}

}