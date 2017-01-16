<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
	public function _initialize(){
	     // if(!session('login_sign')){
      //              // echo '滚去登录';
      //             $this->success('重新登录',U('Admin/Login/login'));
      //              }
	}
}