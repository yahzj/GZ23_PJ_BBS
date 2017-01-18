<?php 
namespace Admin\Model;
use Think\Model;

class NoticeModel extends Model{	
	
	//获得所有通知，传回给控制器。
    public function pro_index(){
    	$arr=['0'=>'注册时发送','1'=>'升为一级时发送','2'=>'升为二级时发送','3'=>'升为三级时发送','4'=>'升为四级时发送','5'=>'升为五级时发送','6'=>'升为六级时发送','7'=>'升为七级时发送','8'=>'最新通知','9'=>'历史通知'];
    	//以status拼接id排行。
    	$list=$this->field("*,concat(status,'-',id) as bpath")->order('bpath')->select(); 
    	
    	foreach($list as $key=>&$val){
    		$val['statusname']=$arr[$val['status']];
    	}
    	return['list'=>$list];
    }
    //根据传过来的id，得到它的所有内容。
    public function pro_update(){
    	$id=I('get.id');
    	$res=$this->find($id);
    	//将这个id的内容返回
    	return $res;
    }
   //处理要更新修改的数据。
    public function pro_action(){
		//将新数据写进数据库。
		$res=$this->save($_POST);
    	return $res;
    }
    //执行删除
    public function pro_del(){
        if(!empty($_GET['id'])){
            $id=I('get.id');
            $id+=0;
            $res=$this->delete($id);
        }else{
            $map=[];
            //$_POST的所有值就是上传过来要删除的所有id，所以要用遍历将所有值取出放到一个新数组，方便用来直接删除。
            foreach($_POST as $key=>$val){
                $map[]=$val;
            }
            //设置好id，方便直接放进where方法执行删除。
            $data['id']=['in',$map];
            $res=$this->where($data)->delete();
        }
    	
    	return $res;
    }
    //执行添加
    public function pro_doadd(){
    	$post=I("post.");
    	$map['status']=['eq',I('post.status')];
    	$sel=$this->where($map)->select();
    	if($sel){
    		return 0;
    	}
    	//直接执行添加。在add.html页面已经专门设置成键值为title和content和status了。方便快捷。
    	$res=$this->add($post);
    	if($res){
    		return 1;
    	}else{
    		return 2;
    	}
    }

    public function pro_send(){
    	$users=D('users');
    	$userslist=$users->select();
    	$noticelist=$this->find(I('get.id'));
    	$noticesend=D('noticesend');
    	$userlist=[];
    	foreach($userslist as $key=>$val){
    		$userlist[]=$val['id'].'_0';
    	}
    	$where['id']=I('get.id');
    	$map['status']=9;
    	$this->where($where)->save($map);
    	$arr['receiverid']=implode(',',$userlist).",";
    	$arr['title']=$noticelist['title'];
    	$arr['content']=$noticelist['content'];
    	$res=$noticesend->add($arr);
    	return $res;
    }
}

