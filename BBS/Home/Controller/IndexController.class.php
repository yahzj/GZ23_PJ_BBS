<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends EmptyController {
    public function index(){
    	$sections=D('Admin/sections');
    	// 输出所有顶级版块
    	$map['parent_id']=0;
    	$list['sections']=$sections->where($map)->order('id')->select();

    	$subject=D('Admin/subject');
    	// 输出最新回复的主题
		$list['followtime'] =$subject->field('id,name,followtime')->where($map)->order('`followtime` desc')->limit('5')->select();
		foreach ($list['followtime'] as $key => $val) {
			
		}
		// 输出新增加的主题
		$list['addtime'] =$subject->field('id,name,followtime,addtime')->where($map)->order('`addtime` desc')->limit('5')->select();
		foreach ($list['addtime'] as $key => $val) {
			
		}

		// dump($list);
		$this->assign($list);
		$this->display();
    }
    public function index2(){

	$this->display();
    }
}