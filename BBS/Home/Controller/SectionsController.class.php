<?php
namespace Home\Controller;
use Think\Controller;
class SectionsController extends EmptyController {
    public function index(){
    	$s_id=(int)I('get.s');
	    $obj=new \Think\Model();
	    //板块查询需要的SQL语句
		$sql = 'select su.name as name,su.uid as uid,su.followtime as f_time,su.fid as fid,su.floor as floor,su.addtime as a_time,se.id,su.section_id from mybbs_subject su,mybbs_sections se  where se.id=su.section_id and se.id='.$s_id;
		// .$map.' order by a.id '.$sort.' limitl '.$p->firstRow.','.$p->listRows;
		$data['list'] = $obj->query($sql);
		if (empty($data['list'])) {
			return '';
		}
		// 便利出需要查询的用户id和昵称
		foreach ($data['list'] as $key => $val) {
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
			if ($v['fid']==0) {
				$v['fid']=$v['uid'];				
				$v['f_time']=$v['a_time'];
			}else{
				$v['fid']=$user[$v['fid']];				
			}
		}
		 // 将数据根据 volume 降序排列，
		foreach ($data['list'] as $key => $val) {
			$volume[]=$val['f_time'];
		}

	 	$res=array_multisort($volume,SORT_DESC,$data['list']);
	 	// dump($res);

	    $this->assign($data);
		$this->display();
    }
}