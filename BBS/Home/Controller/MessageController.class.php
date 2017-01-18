<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends EmptyController{
	//显示所有数据和搜索后的数据
	public function index(){
		$message = D("message");
		//什么参数都没有，默认就是收件箱。
		if(empty(I('get.'))){
			$list=$message->sms();
		}else{
			//从参数的不同跳转到不同的地方。
			switch(I('get.type')){
			case 'sms'://短消息
				$list=$message->sms();
				break;
			case 'notice'://系统通知，评论通知
				$list=$message->sms();
				break;
			case 'request'://好友请求
				$list=$message->sms();
				
				break;
			case 'post'://发新消息
				$list=$message->sms();
				break;
			default:
				$this->error("没有你要找的网页");
				break;
		}
		}
		if($list===false){
			$this->error("没有你要找的网页");
		}
		$this->assign($list);
		$this->display();
	}
	//删除消息
	public function del(){
		$message=D('message');
		$res=$message->pro_del();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
		
	}
	//处理已读
	public function tag(){
		$message=D('message');
		$message->pro_tag();
	}
	//短消息回复
	public function add(){

		$message=D('message');
		$res=$message->pro_add();
		if($res){
			$this->success("发送成功");
		}else{
			$this->error("发送失败");
		}
	}

	public function post(){
		//收信人不能为空
		if(empty(I('post.receivername'))){
       		$this->error("收信人不能为空！");
       	}
       	//标题不能为空
		if(empty(I('post.title'))){
       		$this->error("标题不能为空！");
       	}
       	//内容不能为空
		if(empty(I('post.content'))){
       		$this->error("内容不能为空！");
       	}
		//验证码验证
        $verify=I('param.verify','');
        if(!check_verify($verify)){
       	 	$this->error("亲，验证码输错了哦！");
       	 }
       	 $message=D('message');
       	 $data=$message->pro_post();
       	 if($data===0){
       	 	$this->error("没有这个用户",'index/type/post');
       	 	
       	 }elseif($data===1){
       	 	$this->success("发送成功",'index/type/sms/action/send');
       	 }else{
       	 	$this->error("系统出错，请稍后再试！！",'index/type/post');
       	 }
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

