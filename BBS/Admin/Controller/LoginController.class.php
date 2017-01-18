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
                	$this->error("亲，验证码输错了哦！",U('Admin/Login/login'),3);
                }
                     //接收数据
	   	$post_admin_name=I('post.admin_name');
	   	$post_pass=I('post.pass');
                         $map['admin_name']=$post_admin_name;
                         $admin=D('admin');
                         $list=$admin->where($map)->select(); 
                    
                         $list['landtime']=time();
                         $list['login']='admin';   

                         session('mybbs', $list); 
                            //判断登录是否成功
                         if ($list[0]['admin_name']==null){
                                 return $this->Error('帐号不存在',U('Admin/Login/login'));
                         }
                         $res=password_verify($post_pass,$list[0]['pass']);
                         if($res){
                            $data['up_ip']=I('server.REMOTE_ADDR');
                            $data['up_date']=date('Y-m-d H:i:s');
                            // dump($data);
                            $res=$admin->create($data);
                            array_pop($res);
                            // dump($res);
                            $res=$admin->where($map)->save($res);
                            // dump($res);
                            return $this->success("登录成功！",U('Admin/Index/index'));
                         }else{
                                return $this->Error('密码不正确',U('Admin/Login/login'));//验证错误，返回错误信息
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