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
