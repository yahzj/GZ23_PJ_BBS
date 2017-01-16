<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends EmptyController {
    public function index(){
    $list['link']=U('sections/index?s=23');
    $this->assign($list);
	$this->display();
    }
    public function index2(){

	$this->display();
    }
}