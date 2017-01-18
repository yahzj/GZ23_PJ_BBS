<?php
namespace Home\Controller;
use Think\Controller;
class SectionsController extends EmptyController {
    public function index(){
    	$s_id=(int)I('get.s');
    	// 获取数据库中本板块的相关信息
    	$section=D('Admin/sections');
    	$Smap['id']=$s_id;
    	$Sdata=$section->field('name,path,top')->where($Smap)->select();
    	$SectionTop=$Sdata['0']['top'];
    	// 拼接成导航路径
    	$Sdata['0']['path'].=$s_id;
    	$path=explode( ',',$Sdata['0']['path']);
    	$Smap['id']=array('in',$path);
    	$selist=$section->field('name,id')->where($Smap)->select();
    	foreach ($selist as $k=>$v) {
    			$link[0]['pname']='首页';
    			$link[0]['path']=U('index/index');
    			$link[$v['id']]['pname']=$v['name'];
    			$link[$v['id']]['path']=U("sections/index","s=".$v['id']);
    	}

    	// 获取数据库中本版块的子板块
    	$Ssmap['parent_id']=$s_id;
    	$child=$section->field('name,id,introduce')->where($Ssmap)->select();
    	foreach ($child as $key => &$sval) {
    		$sval['link']=U('sections/index',"s=".$sval['id']);
    	}
    	// 获取库中主题相关信息
	    $obj=D('Admin/subject');
    	$map['section_id']=$s_id;
		
	    $rows=25;
	    //统计总数量
	    $totalRow=$obj->where($map)->count();
	    // 实例化分页类
		$page = new \Think\Page( $totalRow,$rows);

		//TP语句拼装
		$list =$obj->field('name,uid,followtime,fid,floor,addtime')->where($map)->order('`followtime` desc')->limit( $page->firstRow . ',' . $page->listRows   )->select();
		// $data['list'] =$obj->field('mybbs_subject.name as name,mybbs_subject.uid as uid,mybbs_subject.followtime as f_time,mybbs_subject.fid as fid,mybbs_subject.floor as floor,mybbs_subject.addtime as a_time,mybbs_sections.id,mybbs_subject.section_id')->join('mybbs_sections on mybbs_subject.section_id=mybbs_sections.id')->where($map)->select();
		// $data['list'] = $obj->query($sql);
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
			
			// 进行昵称替换
			foreach($list as $k=>&$v){
				$v['uid']=$user[$v['uid']];
				$v['fid']=$user[$v['fid']];				
			}
		}else{

		}
		$data=array(
			'show'=>$page->show(),
			'list'=>$list,
			'child'=>$child,
			'SectionTop'=>$SectionTop,
			'link'=>$link,
			);
		// dump($data);
	    $this->assign($data);
		$this->display();
    }
}