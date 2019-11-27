<?php
//echo "hello";

include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$edit = false;

if(isset($_GET['order_id']) && !empty($_GET['order_id']))
{
	$orderid = $_GET['order_id'];
	//echo $orderid;
	//die;
	$order=$obj_order_status->geteditvalue($orderid);
	$edit = true;

	
}

if($display_status){
	
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insertid = $obj_order_status->insertorder($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$order_id = $order['order_status_id'];
		$obj_order_status->OrderUpdatestatus($order_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
              	 <label class="col-lg-3 control-label"><span class="required">*</span> Name</label>
                <div class="col-lg-8">
                  <input type="text" name="name" value="<?php echo isset($order['status_name'])?$order['status_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select class="form-control" name="status">
						<?php if($order['status']==1){ ?>
                            <option value="1" selected="selected">Active</option>
                            <option value="0" >Inactive</option>
                            <?php
                        }else { ?>
                            <option value="1">Active</option>
                            <option value="0" selected="selected">Inactive</option>
                        <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>