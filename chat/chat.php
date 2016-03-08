
<!DOCTYPE>
<html>
	<head>
		<meta charset="utf-8">
		<title>在线客服</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="../css/site.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div id="sound"></div>
			<div class="row">
				<div class="span3">
					<div class="well" style="padding: 8px 0;">
						<ul class="nav nav-list" id="nav">
							<li class="nav-header">
								客户列表
							</li>
						</ul>
					</div>
				</div>
				<div class="span8">
					<div id="" class="hero-unit" style="height:450px;">
						
					</div>
				</div>
			</div>
		</div>
		<script src="../js/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/site.js"></script>
		<script src="../js/jquery-1.7.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				var nav = $("#nav");	//在线用户列表
				var chatwindows = $(".hero-unit");	//所有聊天窗数组
				var chatul = $(".zebra-list");		//单个聊天窗口的Ul
				var btn = $(".btn_send");		//发送按钮数组
				var customers = new Array();	//记录咨询用户的数组
				var glint = new Array();	//闪烁的头像数组
				var current = 0;
				var messagesound='<audio autoplay><source src="message.mp3" type="audio/mp3" /><embed src="message.mp3" type="audio/mp3"/></audio>';

				var init = function (){		//初始化 将按钮绑定时间 之后的元素要重新获取
					$("#nav").children().not(":first").css({"cursor":"pointer"}).bind("click",function(){
						$(this).addClass("active").siblings().removeClass("active");
						current = $(this).index();
						$(".hero-unit").eq(current).siblings().addClass("hidden").end().removeClass("hidden");
						//点击某一个客户时,清除头像闪烁
						clearInterval(glint[current]);
						$(this).find("i").removeClass("icon-white");
						$("title").text("在线客服");
					});
					$(".btn_send").unbind("click").bind("click",function () { //防止重复绑定
						var input = $(this).prev();
						var answer = input.val();
						if (answer=="") {
							return false;
						};
						var mydate = new Date();
	  					var t=mydate.toLocaleString();
						var cid = customers[current-1]; 
						$.post("index.php",{staff_id:"1",user_id:cid,content:answer,createtime:t},function (data) {
									if(data=="1"){
										input.val("");
										var newli = '<li><i class="icon-user"></i> <a class="title" href="#"><b>You</b></a> '+t+'<span class="meta"><em>'+answer+'</em></span></li>';
										$(".zebra-list").eq(current-1).append(newli).parent().animate({scrollTop:9999});
									}else{
										alert("发送失败,请检查网络.");
									}
								});
						return false;
					});
					$(".inputmessage").keypress(function(e){ //ctrl+enter发送
						if(e.ctrlKey && e.which == 13 || e.which == 10) { 
							$(this).parent().find("button").trigger("click"); 
						} else if (e.shiftKey && e.which==13 || e.which == 10) { 
							$(this).parent().find("button").trigger("click"); 
						} 
					});
				}
				init();
				

				//动态更新数据
				

				var addCustomer = function (cid) {
						customers.push(cid);
						var newUserLi = '<li><a><i class="icon-user"></i> 客户'+cid+'</a></li>';
						nav.append(newUserLi);
						var newChat = '<div id="" class="hero-unit hidden" style="height:450px;"><div id="" class="" style="height:421px;overflow-y:scroll;margin-top:-20px;"><ul class="zebra-list"></ul></div><div style="height=50px;margin-top:15px;"><form action="" method="post"><textarea rows="3" class="span6 inputmessage" placeholder="(Ctrl+Enter发送)"></textarea>&nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-primary btn_send" >send</button></form></div></div>';
						chatwindows.eq(0).parent().append(newChat);
						init();
				}

				var addMessage = function(message){
					var cIndex;
					var flag = 0;
					$.each(customers,function (n,v) {	//查找客户在哪个位置
						if(v==message.user_id){
							cIndex = n+1;
							flag = 1;
						}
					});
					if(!flag){		//如果没查找到 则添加客户
						addCustomer(message.user_id);
						cIndex = customers.length;
					}
					var newMessage = '<li><i class="icon-user"></i> <a class="title" href="#"><b> 客户'+ message.user_id +'</b></a> '+ message.createtime+'<span class="meta"><em>'+message.content+'</em></span></li>';
					$(".zebra-list").eq(cIndex-1).append(newMessage).parent().animate({scrollTop:9999});
					$("#sound").append(messagesound);
					
					//头像闪烁(先清除原有闪烁
					flag = 1;
					clearInterval(glint[cIndex]);
					glint[cIndex] = setInterval(function(){
						var currentHead = $("#nav").children().eq(cIndex).find("i"); //获取要闪烁的图标
							if(currentHead.parent().hasClass("active")) return false;
							if (currentHead.hasClass("icon-white")) {
								currentHead.removeClass("icon-white");
							}else{
								currentHead.addClass("icon-white");
							}
							if(flag==1){
								$("title").text("在线客服 [NEW!]");
								flag=0;
							}else{
								$("title").text("在线客服");
								flag=1;
							}
						},500);
					return true;
				}


				$(".btn_receive").bind("click",function () {
					var url = "index.php?timestamp="+new Date().getTime();
					$.ajaxSetup({cache:false});
					$.getJSON("index.php",{staff_id:"1"},function (data) {
						var message = data;
						console.log(message);
						if(message){
							if(addMessage(message)){
								$.post("update.php",{messageId:message.id},function () {
									
								});
							}
						}
						$.ajaxSetup({cache:true});
					});
					return false;
				});

				


				setInterval(function () {
					$.ajaxSetup({cache:false});
					$.getJSON("index.php",{staff_id:"1"},function (data) {
						var message = data;
						console.log(message);
						if(message){
							if(addMessage(message)){
								$.post("update.php",{messageId:message.id},function () {
									
								});
							}
						}
						$.ajaxSetup({cache:true});
					});
				},2000);
			})
		</script>
	</body>
</html>
