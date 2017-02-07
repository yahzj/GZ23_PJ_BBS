<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends CommonController{
	//显示所有数据和搜索后的数据
	public function index(){
		$message = D("message");
		//什么参数都没有，默认就是收件箱。
		if(empty(I('get.'))){
			$list=$message->sms();
		}elseif(!empty(I('get.p')) && count(I('get.'))==1){
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
			case 'search'://发新消息

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
		//验证码验证
        $verify=I('param.verify','');
        if(!check_verify($verify)){
       	 	$this->error("亲，验证码输错了哦！");
       	 }
		$message=D('message');
		$res=$message->pro_add();
		if($res){
			$this->success("发送成功",'index/type/sms/action/send');
		}else{
			$this->error("发送失败");
		}
	}

	public function post(){
		//收信人不能为空
		if(empty(I('post.receivername'))){
       		$this->error("收信人不能为空！");
       	}
       	//不能给自己写信
		if($_SESSION['mybbs_home'][0]['nickname']==I('post.receivername')){
       		$this->error("不能给自己写信！");
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
       	 }elseif($data===9){
       	 	$this->error("你的昵称为空，不能发帖！",'index/type/sms/action/send');
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
	//用户同意和拒绝好友请求的走这里
	public function friend(){
		$message=D('message');
		$res=$message->pro_friend();
		if($res==0){
			$this->error('不要在地址栏乱搞啦！');
		}elseif($res==1){
			$this->error('你的好友已经达到50人，无法再添加');
		}elseif($res==2){
			$this->error('暂无法添加，请稍后再试');
		}elseif($res==4){
			$this->success('拒绝成功！',U('Home/Message/index',['type'=>'request']));
		}else{
			$this->success('添加好友成功！',U('Home/Message/index',['type'=>'request']));
			
		}
	}
	//用户请求别人为好友的走这里
	public function requestadd(){
		$message=D('message');
		$res=$message->pro_requestadd();

		if($res==0){
			$this->error('添加失败！');
		}elseif($res==1){
			$this->error('已经是好友！不能再申请！');
		}elseif($res==2){
			$this->error('你已经申请过该好友！');
		}elseif($res==3){
			$this->error('对方好友数量已超过50人,不能再添加，你可以给他发短信说明！');
		}elseif($res==9){
			$this->error('你的昵称未完善！');
		}elseif($res==10){
			$this->error('你还没登录！正在跳转回登录页',U('Home/login/login'));
		}else{
			$this->success('申请好友成功！');
			
		}
	}


}

