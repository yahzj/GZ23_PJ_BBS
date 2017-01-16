<?php
namespace Home\Model;
use Think\Model;
class LoginModel extends Model{
	protected $_validate=[

	];

	protected $_auto=[

	];

             protected function is_agree(){   
	        // 获取POST数据
	        $agree = I('post.agree', 0, 'intval');
	        // 验证
	        if ($agree) {
	            return true;
	        } else {
	            return false;
	        }
             }

}