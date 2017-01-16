<?php
namespace Home\Controller;
use Think\Controller;
class SectionsController extends EmptyController {
    public function index(){
    	$s_id=(int)I('get.s');
    	$map['section_id']=$s_id;
	    $obj=D('admin/subject');

	    $rows=25;
	    //统计总数量
	    $totalRow=$obj->where($map)->count();
	    // 实例化分页类
		$page = new \Think\Page( $totalRow,$rows);

	    //板块查询需要的SQL语句
		// $sql = 'select su.name as name,su.uid as uid,su.followtime as f_time,su.fid as fid,su.floor as floor,su.addtime as a_time,se.id,su.section_id from mybbs_subject su,mybbs_sections se  where se.id=su.section_id and se.id='.$s_id;
		// .$map.' order by a.id '.$sort.' limitl '.$p->firstRow.','.$p->listRows;

		//TP语句拼装
		$data['list'] =$obj->field('name,uid,followtime,fid,floor,addtime')->where($map)->order('`followtime` desc')->limit( $page->firstRow . ',' . $page->listRows   )->select();

		// $data['list'] =$obj->field('mybbs_subject.name as name,mybbs_subject.uid as uid,mybbs_subject.followtime as f_time,mybbs_subject.fid as fid,mybbs_subject.floor as floor,mybbs_subject.addtime as a_time,mybbs_sections.id,mybbs_subject.section_id')->join('mybbs_sections on mybbs_subject.section_id=mybbs_sections.id')->where($map)->select();
		// $data['list'] = $obj->query($sql);
		if (empty($data['list'])) {
			return '';
		}
		// 便利出需要查询的用户id和昵称
		foreach($data['list'] as $key => $val) {
			$userid[]=$val[uid];
			$userid[]=$val[fid];
		}
		// dump($sql);
		// dump($data['list']);
		// 进行查询
		$user=D('admin/users');
		$map[id]=array('in',$userid);
		$userlist=$user->field('id,nickname')->where($map)->select();
		$user=[];
		foreach ($userlist as $key => $val) {
			$user[$val['id']]=$val['nickname'];
		}
		// 进行昵称替换
		foreach($data['list'] as $k=>&$v){
			$v['uid']=$user[$v['uid']];
			$v['fid']=$user[$v['fid']];				
		}
		$data[show]=$page->show();
	    $this->assign($data);
		$this->display();
    }
}