<?php
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

//Start : edit
$edit = '';

if(isset($_GET['machine_id']) && !empty($_GET['machine_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$machine_id = base64_decode($_GET['machine_id']);
		$machine = $obj_machine->getMachine($machine_id);
		//printr($machine);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_machine->addMachine($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$machine_id = $machine['machine_id'];
		$obj_machine->updateMachine($machine_id,$post);
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
                <label class="col-lg-3 control-label">Machine Name</label>
                <div class="col-lg-8">
                	<input type="hidden" name="machine_id" id="machine_id" value="<?php echo isset($machine['machine_id'])?$machine['machine_id']:'';?>" />
                  	<input type="text" name="machine" placeholder="Machine Name" value="<?php echo isset($machine['machine_name'])?$machine['machine_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
                 <div class="form-group">
                <label class="col-lg-3 control-label">Manufacturing Process</label>
                <div class="col-lg-4">
                  <?php $process = $obj_machine->getProductionProcess(); //printr($process);
						 $p=array();
						 if(isset($machine['production_process_id'])&& !empty($machine['production_process_id']) ){
					    		$p = json_decode($machine['production_process_id']);
				  			 }
							
						 
						foreach($process as $pro)
						{
						?>
							<div class="checkbox chf1" style="float: left; width: 40%;">
                                <label>
                                  <input type="checkbox" name="process_name[]" value="<?php echo $pro['production_process_id'];?>" id="process_name" class="formtypeclass" <?php if(isset($p)&& in_array($pro['production_process_id'],$p)){ echo 'checked = "checked"'; } ?>>
                                 <?php echo $pro['production_process_name'];?>
                                 </label>
                             </div>
						<?php }?>
                  	
                </div>
                <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">
                                        <a  id="btn-all-check" class="label bg-success selectall mt5"  onclick="javascript:checkall('form', true)">Select All</a>
                                        <a id="btn-all-uncheck" class="label bg-warning unselectall mt5"  onclick="javascript:uncheckall('form', true)">Unselect All</a>  
                                    </div>
                                </div>
              </div>
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($machine['status']) && $machine['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($machine['status']) && $machine['status'] == 0)?'selected':'';?>> Inactive</option>
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	 
	   jQuery("#form").validationEngine();
});

	
	function checkall(formname, checktoggle)
	{
		var checkboxes = new Array();
		checkboxes = $('input[name="process_name[]"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].type === 'checkbox') {
				checkboxes[i].checked = checktoggle;
			}
		}
	}
	function uncheckall(formname, checktoggle)
	{
		var checkboxes = new Array();
		checkboxes = $('input[name="process_name[]"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].type === 'checkbox') {
				checkboxes[i].checked = '';
			}
		}
	}	   	  	   	   
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>