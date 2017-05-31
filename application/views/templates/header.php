<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
    	<title>QAM-OODP Support Tool - <?=$title ?> </title>
    	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    	
    	<!-- 
		****************************************
			CSS linkage
		****************************************
    	-->
    	<!-- Bootstrap Paper Theme -->
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>bootstrap.min.css" >
        <!-- FontAwesome 4.3.0 -->
        <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons 2.0.0 -->
    	<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    	<!-- Theme style -->
    	<link href="<?php echo URL; ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    	<!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    	<link href="<?php echo URL; ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    	<!-- Defined style sheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>style.css">


        <!-- 
		****************************************
			JS linkage
		****************************************
    	-->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    	<script src="<?php echo JS; ?>bootstrap.min.js"></script>
    	<!-- jQuery 2.1.4 -->
    	<script src="<?php echo JS; ?>jQuery-2.1.4.min.js"></script>
    	<script type="text/javascript">
        	var baseURL = "<?php echo base_url(); ?>";
    	</script>
    	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/ckeditor/ckeditor.js"></script>
    </head>
    <body class="skin-red sidebar-mini">
    	<div class="wrapper">
    		<header class="main-header">
    			<a href="<?php echo base_url(); ?>cDashboard/index" class="logo" >
    				<span class="logo-mini"><b>QAM</b></span>
    				<span class="logo-lg"><b>QAM-OODP</b> Tool</span>
    			</a>
    			<nav class="navbar navbar-static-top" role="navigation">
    				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            			<span class="sr-only">Toggle navigation</span>
          			</a>
          			<div class="navbar-custom-menu">
			            <ul class="nav navbar-nav">
			              	<li class="dropdown user user-menu">
			                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			                	<?php if($userObj['user_role'] == 'Admin') { ?>
			                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image" alt="User Image"/>
			                  	<?php }
			                  	else if($userObj['user_role'] == 'Assessor') { ?>
			                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar04.png" class="user-image" alt="User Image"/>
			                  	<?php } else{ ?>
			                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar5.png" class="user-image" alt="User Image"/>
			                  	<?php } ?>
			                	<span class="hidden-xs"><?=$userObj['user_name'];?></span>
				                </a>
				                <ul class="dropdown-menu">
				                  	<!-- User image -->
				                  	<li class="user-header">
				                    	<?php if($userObj['user_role'] == 'Admin') { ?>
					                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle" alt="User Image"/>
					                  	<?php }
					                  	else if($userObj['user_role'] == 'Assessor') { ?>
					                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar04.png" class="img-circle" alt="User Image"/>
					                  	<?php } else{ ?>
					                  		<img src="<?php echo base_url(); ?>assets/dist/img/avatar5.png" class="img-circle" alt="User Image"/>
					                  	<?php } ?>
				                    	<p>
				                      	<?=$userObj['user_name'];?>
				                      	<small><?php 
				                      		if($userObj['user_role'] == 'Admin'){
												echo 'System Administrator';
											}
											else if($userObj['user_role'] == "Assessor"){
												echo 'Pattern Assessor';
											}
											else{
												echo 'Pattern Developer';
											}
				                      	?></small>
				                    	</p>
				                  	</li>
				                  	<li class="user-footer">
				                    	<div class="pull-left">
				                      		<a href="<?php echo base_url(); ?>cChangePassword/index" class="btn btn-default btn-flat"><i class="fa fa-key"></i> Change Password</a>
				                    	</div>
				                    	<div class="pull-right">
				                      		<a href="<?php echo base_url()."cUser/logout" ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i>Sign out</a>
				                		</div>
				                	</li>
				            	</ul>
			            	</li>
			        	</ul>
			        </div>
			    </nav>
    		</header>
      		<aside class="main-sidebar">
        		<section class="sidebar">
          			<ul class="sidebar-menu">
            			<li class="header">MENU</li>
            			<li class="treeview">
              				<a href="<?php echo base_url(); ?>cDashboard/index">
                				<i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
              				</a>
            			</li>
            			<?php if($userObj['user_role'] == 'Admin' || $userObj['user_role'] == 'Regular') { ?>
	            			<li class="treeview">
	              				<a href="<?php echo base_url(); ?>cPattern/index" >
	                				<i class="fa fa-book"></i>
	                				<span>Pattern List</span>
	              				</a>
	            			</li>
            			<?php } 
            			if($userObj['user_role'] == 'Admin' || $userObj['user_role'] == 'Assessor') { ?>
	            			<li class="treeview">
		              			<a href="<?php echo base_url(); ?>cAssess/index" >
		                			<i class="fa fa-check-square-o"></i>
		                			<span>Assessment List</span>
		              			</a>
	            			</li>
	            		<?php } 
	            		if($userObj['user_role'] == 'Admin') { ?>
	            			<li class="treeview">
		              			<a href="<?php echo base_url(); ?>cUserManagement/index" >
		                			<i class="fa fa-users"></i>
		                			<span>User Management</span>
		              			</a>
	            			</li>
	            		<?php } ?>
            			<li class="treeview">
	              			<a href="<?php echo base_url(); ?>cReport/index" >
	                			<i class="fa fa-bar-chart"></i>
	                			<span>Report</span>
	              			</a>
            			</li>
        			</ul>
        		</section>
      		</aside>