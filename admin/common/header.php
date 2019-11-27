<!DOCTYPE html>
<html lang="en">
<!--width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" tried for zoom in n out in meta tag with content attribute-->
<head> 
<meta charset="utf-8">
<title> Swiss ERP</title>
<meta name="description" content="Swisspack">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>jQuery_translate/dist/jquery.localizationTool.css">

<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>jQuery_translate/dist">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" />
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/select2/select2.css">
<link rel="shortcut icon" href="<?php echo HTTP_SERVER.'upload/admin/slogo/favicon.ico'; ?>" />
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/calendar_css/calendar.css">
<!--[if lt IE 9]> <script src="<?php echo HTTP_SERVER;?>js/ie/respond.min.js"></script> <script src="<?php echo HTTP_SERVER;?>js/ie/html5.js"></script> <script src="<?php echo HTTP_SERVER;?>js/ie/excanvas.js"></script> <![endif]-->
<script src="<?php echo HTTP_SERVER;?>js/common.js"></script>
<!-- app --> <script src="<?php echo HTTP_SERVER;?>js/app.v2.js"></script>
<script src="<?php echo HTTP_ADMIN;?>common/common.js"></script>



<script src="<?php echo HTTP_SERVER;?>js/prettyphoto/jquery.prettyPhoto.js"></script> 
<script src="<?php echo HTTP_SERVER;?>js/grid/jquery.grid-a-licious.min.js"></script> 
<script src="<?php echo HTTP_SERVER;?>js/grid/gallery.js"></script>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/prettyphoto/prettyPhoto.css" type="text/css" />

<!--<script>-->
<!--  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){-->
<!--  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),-->
<!--  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)-->
<!--  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');-->

<!--  ga('create', 'UA-59800027-1', 'auto');-->
<!--  ga('send', 'pageview');-->
<!--</script>-->
<!--<LocationMatch "/js/(.*)\.js">
    ExpiresDefault "access plus 10 years"
    Header set Cache-Control "public"
</LocationMatch>-->
</head>
<body class="navbar-fixed" > <!-- remove this class for set auto position for nav bar :navbar-fixed-->
<div class="scroll_new">
<!-- header  style="overflow:scroll" -->
<?php /* <div id="loading_div">
	<div class="load_wht">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
   </div> 
</div> */ ?>
<div id="loading">
	<div class="dataTables_processing"><i class="fa fa-spinner fa-spin fa-1x"></i> Processing...</div>
</div>

<?php //printr($_SESSION);
	if(is_loginAdmin()){ 
		if(SYSTEM_LOCK) { 
			if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1){ 
				$display=1;
			} else { 
				$display=0;
			}
		}else{
			$display=1;
		}
	}else{
		$display = 1;
	}
	$user_type_id = isset($obj_session->data['LOGIN_USER_TYPE'])?$obj_session->data['LOGIN_USER_TYPE']:'';
?>  
<header id="header" class="navbar"><input type="hidden" name="user_id" id="user_id" value="<?php echo isset($obj_session->data['ADMIN_LOGIN_SWISS'])?$obj_session->data['ADMIN_LOGIN_SWISS']:'';?>" >
	<?php if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && $display){ ?>    
          <ul class="nav navbar-nav navbar-avatar pull-right">
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="hidden-xs-only"><b>
                    <?php echo isset($obj_session->data['ADMIN_LOGIN_NAME'])?$obj_session->data['ADMIN_LOGIN_NAME']:'Admin';?>
                    </b></span>
                    <span class="thumb-small avatar inline" style="width:28px !important;">
                        <?php
                        if(isset($obj_general)){
                            $pimage = $obj_general->getUserProfileImage($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],'50_');
                        }
                        ?>
                        <img src="<?php echo $pimage;?>" alt="Image" class="img-circle">
                    </span>
                    
                </a>
              <ul class="dropdown-menu pull-right">
                <li><a href="#">Settings</a></li>
                <li><a href="<?php echo HTTP_ADMIN;?>index.php?route=profile">Profile</a></li>
                <li><a href="#"><span class="badge bg-danger pull-right">3</span>Notifications</a></li>
                <li><?php if($user_type_id!='2' || ($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='77')){?><a href="<?php echo HTTP_ADMIN;?>index.php?route=curr_setting">Currency Setting</a>
                <?php } ?></li>
                
                <li class="divider"></li>
                <li><a href="docs.html">Help</a></li>
                <li><a href="signout.php">Logout</a></li>
                
               				<?php 
				$employee_id = $obj_general->getLogin($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
                //print_r($employee_id);
				if(!empty($employee_id) && $employee_id['associate_acnt']!='')
				{	echo '
						<li class="divider"></li>
						<li><a>LOGIN INTO THIS</a></li>';
					$users = explode(',',$employee_id['associate_acnt']);
					foreach($users as $emp)
					{
							$data=explode("=",$emp);
							//print_r($data);
							$user_data=$obj_general->getUserData($data[0],$data[1]);
							echo '<form name="signin" id="signin" class="panel-body" method="post" action="multi_session_log_out.php" style="margin-bottom:-20px;">
									<input type="hidden" name="user_name_multi_session" id="user_name_multi_session" placeholder="User Name" class="form-control validate[required]" value="'.encode($user_data['user_name']).'" autofocus>
									<input type="password" id="password" name="password" placeholder="Password" class="form-control validate[required]" value="'.encode($user_data['password_text']).'" style="display:none;">
									<input type="hidden" name="model" value="1">';
							echo '<button type="submit" name="btn_submit" id="btn_submit" class="btn btn-default btn-xs btn-info" >'.$user_data['user_name'].'</button>
							</form>';
					}
				}
				?>
                
                
                
                
                
              </ul>
            </li>
          </ul>
  <?php } ?>
  <a class="navbar-brand" href="#" style="font-size:18px;">Swiss ERP</a>
 	 <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body"> <i class="fa fa-bars fa-lg text-default"></i> </button>
     <!--<div id="cartDiv">
     	<?php 
		//	if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && $display){ ?>    
    
                     <?php //echo $cart_list = $obj_general->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],5);?>
         <?php // }?>
         </div>-->
         
          <div id="cartDiv">
         	<?php 
    			if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && $display){ ?>    
        
                         <?php echo $cart_list = $obj_general->GetTestOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],5);?>
             <?php  }?>
         </div>
         
         <div id="cal">
        <?php
        if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && $display){ ?>

            <?php echo $msg_list = $obj_general->GetCal($obj_session->data['ADMIN_LOGIN_SWISS'],5);?>
        <?php  }?>
    </div>
    <div id="notification">
        <?php
        if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && $display){ ?>

            <?php echo $notification_list = $obj_general->Getnotification($obj_session->data['ADMIN_LOGIN_SWISS']);?>
        <?php  }?>
    </div>
	
  <!-- alert message -->
  <div class="alert header-alert-message" id="display_alert_message">
      <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
      <i class="fa fa-lg seticon"></i>
      <span></span>
  </div>
  <!-- close alert message -->
</header> 
<!-- / header --> <!-- nav -->

<script>
    
    
    /*setInterval(function() {
                $('#cal').load(document.URL + ' #cal'); // this will run after every 5 seconds
            }, 20000);
	setInterval(function() {
         $('#notification').load(document.URL + ' #notification'); // this will run after every 5 seconds
        }, 20000);*/
</script>