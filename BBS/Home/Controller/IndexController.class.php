<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends EmptyController {
    public function index(){
    	Everyday('daily_visitors');
    	$sections=D('Admin/sections');
    	// 输出所有顶级版块
    	$map['parent_id']=0;
    	$list['sections']=$sections->where($map)->order('id')->select();

    	$subject=D('Admin/subject');
    	foreach ($list['sections'] as $k => &$v) {
	    	// 输出最新回复的主题
	    	$map['section_id']=$v['id'];
			$v['followtime'] =$subject->field('id,name,followtime')->where($map)->order('`followtime` desc')->limit('5')->select();
			foreach ($list['followtime'] as $key => $val) {
				
			}
			// 输出新增加的主题
			$v['addtime'] =$subject->field('id,name,followtime,addtime')->where($map)->order('`addtime` desc')->limit('5')->select();
			foreach ($list['addtime'] as $key => $val) {
				
			}
			$v[link]=U("sections/index","s=$v[id]");
    	}

		// dump($list);
		$this->assign($list);
		$this->display();
    }
    public function index2(){

	$this->display();
    }
}