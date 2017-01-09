<?php 
namespace Admin\Model;
use Think\Model;

class LinksModel extends Model{

    public function pro_index(){
    	$list=$this->select();
    	return['list'=>$list];
    }

    public function pro_update(){
    	
    }
}

