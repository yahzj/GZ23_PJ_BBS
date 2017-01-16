<?php
namespace Home\Controller;
use Think\Controller;
class SectionsController extends EmptyController {
    public function index(){
    	$s_id=I('get.s');
    	$s_id=1;
	    $obj=new \Think\Model();
		$sql = 'select su.name as name,su.uid as uid,su.followtime as f_time,su.fid as fid,su.floor as floor,su.addtime as a_time from mybbs_subject su,mybbs_sections se  where su.section_id=se.id='.$s_id.' order by f_time desc';
		// .$map.' order by a.id '.$sort.' limit '.$p->firstRow.','.$p->listRows;
		$data['list'] = $obj->query($sql);
		dump($data);
	    $this->assign($data);
		$this->display();
    }
}