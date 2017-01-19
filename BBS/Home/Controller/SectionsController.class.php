<?php
namespace Home\Controller;
use Think\Controller;
class SectionsController extends EmptyController {
    public function index(){
    	$s_id=(int)I('get.s');
    	if ($s_id==null) {
    		return $this->success('未知板块，正在跳转...',U('index/index'),5);

    	}
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
		$list =$obj->field('id,name,uid,followtime,fid,floor,addtime')->where($map)->order('`followtime` desc')->limit( $page->firstRow . ',' . $page->listRows   )->select();
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
				$v['link']=U('subject/index',"cid=$v[id]");
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

    public function add_subject()
    {
    	// 动态验证
    	$rules=[
		['name','require','请输入至少1个字的标题'],
		['name','1,32','标题至多32个字' , 1, 'length' , 3],
		['content','require','请输入至少1个字的内容'],
		['content','1,10240','内容至多10240个字' , 1, 'length' , 3],
		['uid','require','用户id必须输入'],
	];
    	// 获取post，并放入用户信息
    	$post=I('post.');
    	// dump($post);
    	$session=I('session.mybbs_home');
    	// dump($session);
    	$post[uid]=$session[0]['id'];
    	$post[fid]=$session[0]['id'];
    	// 写入数据库
    	$sub=D('admin/subject');
    	
    	// dump($res);
    	if ($sub->validate($rules)->create($post)) {
    			$res=$sub->add();
    			$this->success('发表新主题成功，正在跳转...',U('index',"s={$post['section_id']}"),5);   
    		}else
    		{
    			$this->Error($sub->getError());
    		}
    	// }
    	// dump($post);
    }

    public function adv_add_subject()
    {
    	$s_id=(int)I('get.s');
    	// 获取数据库中本板块的相关信息
    	$section=D('Admin/sections');
    	$Smap['id']=$s_id;
    	$Sdata=$section->field('name,path,top')->where($Smap)->select();
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
    	$data=array(
    		'link'=>$link,
    	);
    	$this->assign($data);
		$this->display();

    }
    public function textarea()
    {
    	$this->display();
    }
}