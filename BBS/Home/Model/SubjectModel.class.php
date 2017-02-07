<?php
namespace Home\Model;//代码编辑:刘欣;
use Think\Model;

class SubjectModel extends Model{
	

	//前台显示帖子数据	
	public function pro_index(){
		$id=I('get.cid');//这是我的帖子ID;
		//dump($id);
		$list=$this->find($id);//通过id找到一条数据
		//dump($list);
		//dump($list['uid']);//找到数据里面对应的发帖人ID
		$userList=$this->find_users($list['uid']);//获取发帖人个人信息
		$followData=$this->find_follows($list['id']);//获取所有回复信息
		return [
			'list'=>$list,
			'userList'=>$userList,
			'followList'=>$followData['followList'],
			'show'=>$followData['show'],
			'totalRow'=>$followData['totalRow'],
		];//返回数据	

	}
	//查询发帖人或回复人的个人信息，需要一个参数发帖人或回复人Id
	public function find_users($id){
		$user=D('users');
		$userList=$user->find($id);
		//dump($userList);
		return $userList;
	}
	//查询回复信息，需要一个参数帖子Id
	public function find_follows($id){
		$follow=D('follow');
		$map=[];
		$map['cardid']=['eq',$id];
		//==============================分页===============================================
		$totalRow=$follow->where($map)->count();//计算数据总行数
		//echo "总行数".$totalRow;
		$rows=20;//每页显示行数
		$page=new \Think\Page($totalRow,$rows);//实例化分页类
		$followList=$follow->where($map)->order('`addtime`')->limit($page->firstRow.",".$page->listRows)->select();//执行查询数据
		//==============================分页===============================================
		foreach($followList as $k=>&$v){
			//dump($v['uid']);
			$v['uidData']=$this->find_users($v['uid']);
			$v['L']=$page->firstRow;
		}
		//dump($followList);
		return [
			'followList'=>$followList,
			'show'=>$page->show(),
			'totalRow'=>$totalRow,
		];
	}
	//处理回复信息
	public function pre_dofollow(){
		$data=I('post.');
		//dump($data);
		//echo $data['content'];
		$data['content']=str_replace(['&lt;','&gt;'],['<','>'],$data['content']);
		//echo $data['content'];
		//dump($data);
		//echo "我在数据处理层";
		//die();
		$follow=D('follow');//实例化follow类
		$newdata=$follow->create($data);
		//dump($newdata);
		if($newdata){
				$follow->add($newdata);//如果验证正确则添加到数据库
				//=============实例化subjiect类 将最回复人id 回复时间 楼层进行修改===========================================
				$subject=D('subject');
				$subdata['floor']=$data['floor'];
				$subdata['fid']=$data['uid'];
				$subdata['followtime']=date('Y-m-d,H:i:s');
				//dump($subdata);
				$map=[];
				$map['id']=['eq',$data['cardid']];
				$subject->where($map)->save($subdata);
				//=============实例化subjiect类 将最回复人id 回复时间 楼层进行修改===========================================
				//=============调用everyday函数 将当天的回复数量进行修改===========================================
				Everyday('follow_num');

				//=============调用everyday函数 将当天的回复数量进行修改===========================================
				return '回复成功';
				}else{
				return false;//验证错误，返回错误信息;

		}
		
	}	

}

	
