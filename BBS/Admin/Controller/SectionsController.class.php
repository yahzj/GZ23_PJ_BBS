<?php
namespace Admin\Controller;
use Think\Controller;

class SectionsController extends EmptyController{
	//显示板块表单主页
	public function index(){
		// 实例化Model类
		$sections=D('sections');
		// 走Model层处理数据
		$data=$sections->pro_index();
		$data['title']='板块表单';
		//dump($data);
		$this->assign($data);
		$this->display();
	}

         
    // 加载编辑模板
	public function edit(){
    	// 接收用户ID
    	$id = I('get.id') + 0;
    	//dump($id);
    	// 实例化
    	$sections = D('sections');
    	$data=$sections->pro_edit();	
    	$data['title'] = '板块表单修改';
    	$this->assign($data);
    	$this->display();
    }
   
              //编辑模板
	public function doedit(){
       	// 1.使用自动验证，必须走Model层
    	$sections = D('sections');
    	$res=$sections->pro_doedit();
	   if($res){
            $this->success("更新成功",U('Admin/Sections/index'),3);
       }else{
            $this->error("更新失败");
       }
    }

           //增加板块
    public function add(){
    	$sections=D('sections');
    	$data=$sections->pro_add();		
    	$this->assign($data);		
        $this->display();
    }

    public function doadd(){

		$obj = D("sections"); 
			// 根据表单提交的POST数据创建数据对象
		$data=$obj->pro_doadd();
		if($data){        
         	$this->success('新增成功',U('sections/index'),3);
		}else{
		       // 如果验证失败，则显示错误提示
         	$this->Error($obj->getError());
    	}
		
    }
    

    //删除板块表单
    public function del(){
        if(empty(I('get.id'))&&empty($_POST)){
            //失败的跳转
             $this->error("不要乱搞事好不好？",'',3);
        }
        // 实例化对象
        $sections = D('sections');
        // 执行删除，返回受影响行
        $res = $sections->pro_del();

        if(is_array($res)){
            if(count($res)>0){        
                $str=implode("和",$res);
                if(count($res)<count(I('post.'))){
                    $this->error("id为 $str 因为有子元素，不能删除！其它已删除","index");
                }else{
                    $this->error("id为 $str 因为有子元素，你要删除的全部不能删除！");
                }
               
            }else{
                $this->success("全部删除成功！");
            }
        }else{
             if($res==2){
                $this->error("你要删除的目录还有子目录存在，不能删除！");
            }elseif($res==1){
                $this->success("删除成功！");
            }else{
                $this->success("删除失败！");
            }
        
        }
       
    }

}