var m=document.getElementById('move');//获取move对象
	var c=document.getElementById('change');//获取chenge对象
	var sm=document.getElementById('sm');//获取sm对象
	//console.log(m);
	//m对象鼠标按下事件
	m.onmousedown=function(e){
		var e=e||window.event;
		//console.log('按下鼠标，相对于浏览器可视区域的坐标：' + (e.clientX + ':' + e.clientY));
		//console.log(m.offsetLeft);
		var offsetX=e.clientX-m.offsetLeft; 
		m.style.borderRadius='0px 5px 5px 0px';//设置圆角
		//文档鼠标移动事件
		document.onmousemove=function(we){
				var we = we || window.event;
				m.style.left=we.clientX-offsetX-35+'px';
				c.style.display='block';
				c.style.width=m.offsetLeft-35+'px';
				if(m.offsetLeft<36){
					m.offsetLeft=35;
					m.style.left=0+'px';
					m.style.borderRadius='5px 5px 5px 5px';
				}
				if(m.offsetLeft>266){
					m.style.left=231+'px';
				}
				c.style.display='block';
				c.style.width=m.offsetLeft-35+'px';
				if(c.offsetWidth==1){
					c.style.display='none';
				}
				if(c.offsetWidth==232){
					c.innerHTML='验证完成';
					c.style.background='#669900';
					m.innerHTML='验证完成';
					//console.log(sm);
					sm.disabled=false;
				}else{
					c.innerHTML='';
					c.style.background='#FEC558';
					m.innerHTML='验证中';
					sm.disabled=true;
				}
		};
	};
	document.onmouseup = function(){
		document.onmousemove=function(){};

	};