<?php 
namespace Admin\Model;
use Think\Model;

class FriendModel extends Model{	
	
	//获得所有通知，传回给控制器。
    public function pro_index(){
        $row=10;
        $totalRow=$this->count();
        $page=new \Think\Page($totalRow,$row);
    	$obj=D();
    	$list=$obj->table('mybbs_users u,mybbs_friend f')->where('u.id=f.uid')->order('f.id')->limit($page->firstRow.",".$page->listRows)->select(); 
    	return['list'=>$list];
    }

    //执行删除
    public function pro_del(){
        if(!empty($_GET['id'])){
            $id=I('get.id');
            $id+=0;
            $res=$this->delete($id);
        }else{
            $map=[];
            //$_POST的所有值就是上传过来要删除的所有id，所以要用遍历将所有值取出放到一个新数组，方便用来直接删除。
            foreach($_POST as $key=>$val){
                $map[]=$val;
            }
            //设置好id，方便直接放进where方法执行删除。
            $data['id']=['in',$map];
            $res=$this->where($data)->delete();
        }
    	
    	return $res;
    }
 
}

