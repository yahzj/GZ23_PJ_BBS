<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    
	   public function login(){

	         $this->display();
	   }

	   public function dologin(){
	   	$post_admin_name=I('post.admin_name');
	   	$post_pass=I('post.pass');
                         $map['admin_name']=$post_admin_name;
                         $admin=D('admin');
                         $list=$admin->where($map)->select(); 
                         $res=password_verify($post_pass,$list['pass']);
                         if($res){
                         	      return $admin->success("登录成功！");
                         }else{
                                return $admin->getError();//验证错误，返回错误信息
                         }
                                            
	   }


              public function verify(){
		ob_clean();
		    // 实例化Verify对象
		    $verify = new \Think\Verify();
		    // 配置验证码参数
		    // $verify->fontSize = 14;     // 验证码字体大小
		    // $verify->length = 4;        // 验证码位数
		    // $verify->imageH = 34;       // 验证码高度
		    // $verify->useImgBg = true;   // 开启验证码背景
		    // $verify->useNoise = false;  // 关闭验证码干扰杂点
		     $verify->entry();
		}
 

}