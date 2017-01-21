<?php
namespace Home\Controller;

class PasswordController extends EmptyController{
	public function password(){
		$this->display();
	}

	public function dopassword(){

	       $post_username=I('post.username');
	       $post_email=I('post.email');
	       $map['username']=$post_username;
	       $list=$users=D('users');
	       $list=$users->field('username,email')->where($map)->select();

	        if ($list[0]['username']==null || $list[0]['email']==null){
                                 return $this->Error('帐号不存在或邮箱不存在');
                     }
                 
                     $this->assign('list',$list[0]['userpass']);
                     $this->display();

	}
}