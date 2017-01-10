<?php 
namespace Admin\Model;
use Think\Model;

class LinksModel extends Model{	
	// $_validate 属性定义验证规则。
	protected $_validate = [
		// [验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]]

		['link','url','你的超链接格式不正确！！！'],
	];
	//获得所有链接，传回给控制器。
    public function pro_index(){
    	$list=$this->select();
    	return['list'=>$list];
    }
    //根据传过来的id，得到它的所有内容。
    public function pro_update(){
    	$id=I('get.id');
    	$res=$this->find($id);
    	//将这个id的内容返回
    	return $res;
    }

    public function pro_action(){
    	//获得要修改的id号
    	$post=I("post.");
    	//必须实例化后才能使用create()方法。
    	$link=D("links");
    	//自动验证url。
    	$res=$link->create($post);
    	//写进数据库。
    	if($res){
    		//将新数据写进数据库。
    		$res=$this->save($_POST);
    		return "修改成功！!!!!!!!!!!!!!!!!!";
    	}else{
    		//错误就返回自动验证的错误提示。
    		return $this->getError();
    	}
    	
    	return $res;
    }
    //执行删除
    public function pro_del(){
    	$map=[];
    	//$_POST的所有值就是上传过来要删除的所有id，所以要用遍历将所有值取出放到一个新数组，方便用来直接删除。
    	foreach($_POST as $key=>$val){
    		$map[]=$val;
    	}
    	//设置好id，方便直接放进where方法执行删除。
    	$data['id']=['in',$map];
    	$res=$this->where($data)->delete();
    	return $res;
    }
    //执行添加
    public function pro_doadd(){
    	$post=I("post.");
    	//直接执行添加。在add.html页面已经专门设置成键值为title和link了。方便快捷。
    	$res=$this->add($post);
    	return $res;
    }
}

