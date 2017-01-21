<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
                //登录界面
	   public function login(){

            //如果是从主题页面或主题详细回复点击了登录，那么就将跳转过来的主题页面地址或主题详细回复地址赋给session的url。
            if(substr_count( $_SERVER["HTTP_REFERER"],'GZ23_PJ_BBS/subject')>0 || substr_count( $_SERVER["HTTP_REFERER"],'GZ23_PJ_BBS/sections/index/s/')>0){
                session('url',$_SERVER["HTTP_REFERER"]);

            //如果你原来是从主题页面或主题详细回复跳过来的，但是这个登录中发生了输入用户名，密码，验证码错误等，那么就session的url就保持原样不变。
            }elseif(substr_count( $_SESSION["url"],'GZ23_PJ_BBS/subject')>0 || substr_count(  $_SESSION["url"],'GZ23_PJ_BBS/sections/index/s/')>0){

            }else{
            //如果不是从这两个页面过来的，全部跳到首页。
                session('url','http://localhost/obj2/GZ23_PJ_BBS/home/index/index');    
            }
            
	         $this->display();
	   }
               //编辑界面
	   public function dologin(){

        $verify=I('param.verify','');
        if(!check_verify($verify)){
        	$this->error("亲，验证码输错了哦！",U('Login/login'),2);
        }
  
	   	$post_username=I('post.username');
	   	$post_userpass=I('post.userpass');
         $map['username']=$post_username;
         $users=D('users');
         $list=$users->field('id,username,userpass,nickname,image,status,integral')->where($map)->select(); 
   
       
         if ($list[0]['username']==null){
            return $this->Error('帐号不存在');
         }
         $res=password_verify($post_userpass,$list[0]['userpass']);
         if($res){

         $list['landtime']=time();
         $list['login']='home';                     
          session('mybbs_home', $list); 
         	      return $this->success("登录成功！",$_SESSION['url']);
         }else{
                return $this->Error('密码不正确',U('Login/login'));//验证错误，返回错误信息
         }
                                            
	   }


      //退出登录
        public function logout(){
       
            // 清除所有session
            session(null);
            redirect(U('Index/index'), 0, '正在退出登录...');
        }





        public function verify(){
		    ob_clean();
		    // 实例化Verify对象
		    $verify = new \Think\Verify();
		    // 配置验证码参数
		    $verify->expire = 9999990;     // 验证码字体大小
		    // $verify->length = 4;        // 验证码位数
		    // $verify->imageH = 34;       // 验证码高度
		    // $verify->useImgBg = true;   // 开启验证码背景
		    // $verify->useNoise = false;  // 关闭验证码干扰杂点
		     $verify->entry();
		}

             

 

}