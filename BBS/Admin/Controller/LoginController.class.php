<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
                //登录界面
	   public function login(){

	         $this->display();
	   }
               //编辑界面
	   public function dologin(){
                //验证码验证
                $verify=I('param.verify','');
                if(!check_verify($verify)){
                	$this->error("亲，验证码输错了哦！",$this->site_url,9);
                }
                     //接收数据
	   	$post_admin_name=I('post.admin_name');
	   	$post_pass=I('post.pass');
                         $map['admin_name']=$post_admin_name;
                         $admin=D('admin');
                         $list=$admin->where($map)->select(); 
                         //dump($list);
                    //将session值带入登录页面
                        //  session('id', $list['id']); // 当前用户ID
                        //  session('admin_name', $list['admin_name']);   // 当前用户名称
                        // session('nickname', $list['nickname']);//当前用户昵称
                        // session('up_date', $list['up_date']);//当前用户时间
                        session('up_datetime', time());//当前用户时间
                        // session('up_ip', $list['up_ip']);//当前用户ip地址
                        // session('status', $list['status']);//当前用户状态
                        session('login', 'admin');//后台登录

                         session('mybbs', $list); 
                   //判断登录是否成功
                         if ($list[0]['admin_name']==null){
                                 return $this->Error('帐号不存在');
                         }
                         $res=password_verify($post_pass,$list[0]['pass']);
                         if($res){
                         	      return $this->success("登录成功！",U('Admin/Index/index'));
                         }else{
                                return $this->Error('密码不正确');//验证错误，返回错误信息
                         }
                                            
	   }

                //退出登录
                public function logout(){
               
                    // 清除所有session
                    session(null);
                    redirect(U('Login/login'), 2, '正在退出登录...');
                }




             //验证码
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