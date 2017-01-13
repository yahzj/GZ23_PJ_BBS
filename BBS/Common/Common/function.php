<?php
//配合自动完成加密函数
// $val为需要哈希加密的数据
// return返回加密后的数据
function myHash($val){
	$hash=password_hash($val,PASSWORD_DEFAULT);
	return $hash;
}


//Memcache数据库公共缓存
// 用于减少数据库的读取次数
// $table 为表单名称
// $data为数据在Memcache里的键值
function MemcacheModel($table,$data){
	// 1.实例化对象
	$mem = new Memcache();
	// 2.连接服务器
	$mem->connect('127.0.0.1',11211);

	$list=[];
	$list = $mem->get($data);

	if(!$list){

		$obj=D($table);
		
		$list=$obj->pro_index();
		
		// 写入缓存
		$mem->set($data,$list,MEMCACHE_COMPRESSED,10);
		return $list;
	}
	return $list;
}


//page页数生成
// $mod 如果在mod类里调用一般传入$this即可，在控制器里调用，需要传入实例化的mod类
// $map 传入需要查询的条件，默认没有条件
// $key 传入排序的属性基准,默认排序以id排序
// $order 传入排序方式，默认为asc
function PageMod($mod,$map=[],$key='id',$order=''){
	// 计算总行数
	$totalRow = $mod->where($map)->count();
	// 定义每页显示行数
	$rows = 10;
		//判断页数
	if ($_GET['p']>ceil($totalRow/$rows)) {
		$_GET['p']=ceil($totalRow/$rows);
	};
	// 实例化分页类
	$page = new \Think\Page( $totalRow,$rows );
	// 执行查询
	$list = $mod->where($map)->order('`'.$key.'` '.$order)->limit( $page->firstRow . ',' . $page->listRows   )->select();

	$status = ['锁定','正常','高亮'];
	// 基本处理
	foreach($list as $key => &$val){
		$val['status'] = $status[ $val['status'] ];
		$val['content'] = substr($val['content'],0,128);
	}

	return [
			// 返回用户信息
			'list' => $list,
			'show' => $page->show(),
		];
}