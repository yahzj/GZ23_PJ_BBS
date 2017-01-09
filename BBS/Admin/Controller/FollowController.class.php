<?php
namespace Admin\Controller;
use Think\Controller;
class FollowController extends EmptyController 
{
    
		// 显示后台主页+搜索功能。
	    public function index()
	    {
	    	//开启session是为了将用户每次查询的数据放进去，查询出来后若用户没有改变搜索条件，那么则每次从这个session数据中找到数据进行跳转。
	    	session_start();
	    	$follow=D("follow");
	    	//根据不同情况走不同的条件语句。
	    	//用户搜索走post这个条件
	    	if(I('post.')){
	    		//搜索就是post，那么就会走这里。
	    		$data = $follow->fModelSearch();
	    		
	    	//用户之前搜索了，又使用这个搜索条件跳转到其它分页就走这里。
	    	}elseif($_SESSION['sdata']&&I('get.p')){
	    		//搜索之后没有重新进行搜索或者返回默认页面而点击下一页或第几页，那么就是用户希望根据之前的搜索条件进行跳转网页，继续走这里。
	    		//得到用户跳转的网页
	    		$num=I('get.p');
	    		//根据跳转的网页，得到当前页原本要显示的第一条数据。例如参数p为9的话，那么最后原本最后一条显示的数据为81.
	    		$mstart=($num-1)*10;
	    		//根据跳转的网页，得到当前页原本要显示的最后一条数据。例如参数p为9的话，那么最后原本最后一条显示的数据为90.
	    		$mend=$num*10;
	    		//将之前用户搜索时得到的session数据赋值给一个新数组。
	    		$newdata=array_values($_SESSION['sdata']);
	    		//准备一个空数组要显示到index页面上的数据。
	    		$data['list']=[];
	    		//根据newdata这个数组值的数量走以下不同条件语句。
	    		if(count($newdata)>=$mend){
	    			//如果数组值的数量大于由页数得到的最大的条数码$mend，则走这里。例如newdata数组值的数量为85，而$mend为90.
	    			//将下标81到90的值赋给$data['list']
	    			for($m=$mstart;$m<$mend;$m++){
						$data['list'][]=$newdata[$m];
					}
					//调用FollowModel.class.php中的spage函数，重新实例化当前页面的分页类。系统的page分页类会根据参数p的大小自动调整跳转的链接。
					$data['show']=$follow->spage();
					//如果数组的值的数量小于81，那么就是用户在地址栏乱输入的p参数，因为系统的page类是不会显示大于数组值的链接的
	    		}elseif(count($newdata)<$mstart){
	    			die("请不要在地址栏乱输入！");
	    			//数组的值的数量大于81而小于90，则会走这里。这个范围有多少条就显示多少条，不能再显示10条那么多了。
	    		}else{
	    			//从下标为81开始取值，后面有多少取多少。因为最大也不会超过10条。
	    			for($m=$mstart;$m<count($newdata);$m++){
						$data['list'][]=$newdata[$m];
						
					}
					//调用FollowModel.class.php中的spage函数，重新实例化当前页面的分页类。系统的page分页类会根据参数p的大小自动调整跳转的链接。
					$data['show']=$follow->spage();
	    		}
	    	//用户希望回到默认主页，即没有搜索条件。	
	    	}else{
	    		//是默认主页的话，就删除用户之前的搜索条件。这样跳转就不会再次使用用户之前搜索出来的数据了。
	    		unset($_SESSION['sdata']);
	    		//跳转到model类的pro_index函数，将所有的数据每隔10条取出，并将它分页返回，用$data接收。
	    		$data=$follow->pro_index();
	    	}
	    	//分配数据。
	    	$this->assign($data);
	    	//显示到页面。
	    	$this->display();
	    }

	    public function del()
	    {
	    	//获得需要删除的id
	    	$id=I('get.id');
	    	//防止id乱写
	    	$id+=0;
	    	$follow=D("follow");
	    	//设置$link为空，在success方法跳转就是直接返回跳过来的这一页。
	    	$link='';

	    	/*
	    		如果删除的是最后一条数据，并且所有的数据取余显示的条数等于1，
	    		则说明最后一页只有一条数据。success方法就要跳回到上一页。否则调回原页面会无数据显示。
	    	*/
	    	if($id==$follow->max('id') && $follow->count()%10==1){
	    		//先得到跳转过来的网址，并且将它用/分隔到数组中。
	    		$arrs=explode("/",$_SERVER["HTTP_REFERER"]);
	    		//得到$arrs数组的最大下标值。
		    	$num=count($arrs)-1;
		    	//将$arrs的最后一个值(例如:10.html)用.切割成数组。
		    	$arr=explode(".",$arrs[$num]);
		    	//删除$arrs数组的最后一个值
		    	array_pop($arrs);
		    	//$arr[0]-1 得到上一页的页数，覆盖之前的页数。如9覆盖10.
		    	$arr[0]=$arr[0]-1;
		    	//将最新的$arr数组弄成字符串(如9.html)
		    	$arrs[$num]=implode(".",$arr);
		    	//dump($arrs);
		    	//再把$arrs数组用/拼接成新的链接，当删除成功的时候就可以跳转回去最新正确的页面了。
	    		$link=implode("/",$arrs);
	    	}
	    	//执行删除
	    	$res=$follow->delete($id);
	    	//dump($link);
	    	if($res){
	    		//成功的跳转
	    		$this->success('删除成功！',$link,3);
	    	}else{
	    		//失败的跳转
	    		$this->error("删除失败",'',3);
	    	}
	    }

}