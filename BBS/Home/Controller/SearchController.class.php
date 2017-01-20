<?php
namespace Home\Controller;
use Think\Controller;

class SearchController extends EmptyController{

	public function search_end()
	{
		$s_id=(int)I('get.s');
    	// 获取数据库中本板块的相关信息
    	$section=D('Admin/sections');
		// 拼接成导航路径
    	$Sdata['path'].=$s_id;
    	$path=explode( ',',$Sdata['path']);
    	$Smap['id']=array('in',$path);
    	$selist=$section->field('name,id')->where($Smap)->select();
    	foreach ($selist as $k=>$v) {
    			$link[0]['pname']='首页';
    			$link[0]['path']=U('index/index');
    			$link[$v['id']]['pname']=$v['name'];
    			$link[$v['id']]['path']=U("sections/index","s=".$v['id']);
    	}
		$sub=D('admin/subject');
		$searchName=I('post.search');
		$map['name']=array('like','%'.$searchName.'%');
		$map['section_id']=$s_id;
		// dump($map);
	    $rows=25;
	    //统计总数量
	    $totalRow=$sub->where($map)->count();
	    // 实例化分页类
		$page = new \Think\Page( $totalRow,$rows);

		//TP语句拼装
		$list =$sub->field('id,name,uid,followtime,fid,floor,addtime,status')->where($map)->order('`followtime` desc')->limit( $page->firstRow . ',' . $page->listRows   )->select();
		if (!empty($list)) {
			// 便利出需要查询的用户id和昵称
			foreach($list as $key => $val) {
				$userid[]=$val[uid];
				$userid[]=$val[fid];
				
			}
			// dump($sql);
			// dump($data);
			// 进行查询
			
			$user=D('admin/users');
			$Umap[id]=array('in',$userid);
			$userlist=$user->field('id,nickname')->where($Umap)->select();
			$user=[];
			foreach ($userlist as $key => $val) {
				$user[$val['id']]=$val['nickname'];
			}
			$status=["rgb(199, 199, 199)",'',"rgb(66, 139, 202)","rgb(60, 118, 61)","rgb(49, 112, 143)","rgb(138, 109, 59)","rgb(169, 68, 66)"];
			// 进行昵称替换
			foreach($list as $k=>&$v){
				$v['uid']=$user[$v['uid']];
				$v['fid']=$user[$v['fid']];
				$v['link']=U('subject/index',"cid=$v[id]");
                if (in_array($v['status']%10, [0,1,2,3,4,5,6])) {
                $v['color']=$status[$v['status']%10];
                }
			}
				$data=array(
			'show'=>$page->show(),
			'list'=>$list,
			'link'=>$link,
			);
		// dump($data);
	    $this->assign($data);
		$this->display();
		}
	}
}