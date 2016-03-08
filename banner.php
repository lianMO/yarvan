<?php $count=$count+1;?>
<body>
		<div class="container">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">后台管理系统</a>
						<div class="nav-collapse">
							<ul class="nav">
								
						
							</ul>

							<ul class="nav pull-right">
								<?php 
								if($_SESSION['type']==1)
								{
									echo "<li><a href='staff.php'>点击进入成员管理</a></li>";
								}
								?>
								<li>
									<a href="staff.php?<?php echo $_SESSION['staff_id'];?>"><?php echo $_SESSION['name']; ?></a>
								</li>
								<li>
									<a href="logout.php">Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span3">
					<div class="well" style="padding: 8px 0;">
						<ul class="nav nav-list">
							<li class="nav-header">
								医院挂号
							</li>
							<li>
								<a href="hos_info_mgr.php"><i class="icon-home"></i> 医院信息管理</a>
							</li>
							<li>
								<a href="hos_appoint.php"><i class="icon-folder-open"></i> 查看预约</a>
							</li>
							
							<li class="nav-header">
								分中心服务
							</li>
							<li>
								<a href="center_mgr.php"><i class="icon-home"></i> 分中心管理</a>
							</li>
							<li>
								<a href="center_serv_mgr.php"><i class="icon-cog"></i> 服务项目管理</a>
							</li>
							<li>
								<a href="center_serv_add.php"><i class="icon-cog"></i> 服务发布</a>
							</li>
							<li>
								<a href="center_appoint.php"><i class="icon-folder-open"></i> 查看预约</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="chat/chat.php"target="blank"><i class="icon-info-sign"></i> 在线咨询</a>
							</li>
							<li class="nav-header">
								医生兼职
							</li>
							<li>
								<a href="doctor_add.php"><i class="icon-cog"></i> 兼职发布</a>
							</li>
							<li>
								<a href="doctor_appoint.php"><i class="icon-folder-open"></i> 查看已定预约</a>
							</li>
							<li>
								<a href="doctor_member.php"><i class="icon-user"></i> 查看注册信息</a>
							</li>
						</ul>
					</div>
				</div>