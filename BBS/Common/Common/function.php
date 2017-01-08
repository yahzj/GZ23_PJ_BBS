<?php
//配合自动完成加密函数
function myHash($val){
	$hash=password_hash($val,PASSWORD_DEFAULT);
	return $hash;
}