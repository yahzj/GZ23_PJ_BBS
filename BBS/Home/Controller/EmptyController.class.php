<?php 
namespace Home\Controller;
use Think\Controller;

class EmptyController extends Controller{

	// 8.空操作(找不同请求的操作时)
	public function _empty($var){
		dump($var);
	}

	// EmptyController


}


