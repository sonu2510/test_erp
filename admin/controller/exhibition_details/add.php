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
//$exhibition = '';
$edit = '';
if(isset($_GET['exhibition_id']) && !empty($_GET['exhibition_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$exhibition_id = base64_decode($_GET['exhibition_id']);
		$exhibition = $obj_exhibition->getExhibition_detail($exhibition_id);
		$product_details ='';
		//printr($exhibition);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

//Close : edit



if($display_status){

//insert

if(isset($_POST['btn_save'])){
	$post = post($_POST);
		//printr($post);die;
		$insert_id = $obj_exhibition->addExhibition($post);
		$_SESSION['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));

	
}

if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	//$exhibition_id =$countrys['exhibition_id'];
	
		$obj_exhibition->updateExhibition($exhibition_id,$post);
		//printr($post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString='';
			$_SESSION['warning'] = 'User name exist!';
		
		}
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
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Exhibition Name</label>
                <div class="col-lg-8">
                <!-- <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit; ?>">-->
                  <input type="text" name="exhibition_name" value="<?php echo isset($exhibition['exhibition_name'])?$exhibition['exhibition_name']:'';?>" class="form-control validate[required]" id="exhibition_name">
                  
                    <span id="exists" style="color:red;display:none;"></span>
                   
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                    <div class="col-lg-3">
                      <input type="text" class="form-control validate[required]" name="f_date" value="<?php echo isset($exhibition['duration_from'])?$exhibition['duration_from']:'';?>" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date"/>
                        </div>
                <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                        <div class="col-lg-3">
                         <input type="text" class="form-control validate[required]" name="t_date" value="<?php echo isset($exhibition['duration_to'])?$exhibition['duration_to']:'';?>" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($exhibition['country']))?$exhibition['country']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($exhibition['city'])?$exhibition['city']:'';?>"  class="form-control validate[required]">
                </div>
              </div>


				<!--add-->
				<div class="form-group " >
					<label class="col-lg-3 control-label"><span class="required">*</span>Exhibition Details</label>
					<div class="col-lg-8">
						<textarea rows="5" width="4" class="form-control validate[required]" value="" name="exhibition_details"><?php echo isset($exhibition['exhibition_details'])?$exhibition['exhibition_details']:'';?></textarea>
					</div>
				</div>

			 <div class="form-group" >               
                <label class="col-lg-3 control-label"><span class="required">*</span>Person Name</label>
                    <div class="col-lg-4">             
                         <input type="text" class="form-control validate" value="<?php echo isset($exhibition['person_name_1'])?$exhibition['person_name_1']:'';?>" name="person_name_1">
                    </div>             	
                  </div> 
				<div class="form-group" >               
					<label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                    <div class="col-lg-4">             
                         <input type="email" class="form-control validate" value="<?php echo isset($exhibition['email_1'])?$exhibition['email_1']:'';?>" name="email_1">
                    </div>             	
                  </div>

				<div class="form-group " >
                <label class="col-lg-3 control-label">Person Name</label>
                    <div class="col-lg-4">             
                         <input type="text" class="form-control validate" value="<?php echo isset($exhibition['person_name_2'])?$exhibition['person_name_2']:'';?>" name="person_name_2">
                    </div>
                  </div> 
				  
					<div class="form-group" >               
					<label class="col-lg-3 control-label">Email</label>
                    <div class="col-lg-4">             
                         <input type="email" class="form-control validate" value="<?php echo isset($exhibition['email_2'])?$exhibition['email_2']:'';?>" name="email_2">
                    </div>             	
                  </div>
				  
               <div class="form-group ">
                <label class="col-lg-3 control-label">Person Name</label>
                    <div class="col-lg-4">             
                         <input type="text" class="form-control validate" value=" <?php echo isset($exhibition['person_name_3'])?$exhibition['person_name_3']:'';?>" name="person_name_3">
                    </div>                	                   		 	
                  </div>

					<div class="form-group" >               
					<label class="col-lg-3 control-label">Email</label>
                    <div class="col-lg-4">             
                         <input type="email" class="form-control validate" value="<?php echo isset($exhibition['email_3'])?$exhibition['email_3']:'';?>" name="email_3">
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
	   jQuery("#frm_add").validationEngine();
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date').datepicker({
		//alert(checkin);
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
				//alert(newDate);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date')[0].focus()
    	}).data('datepicker');
    	var checkout = $('#t_date').datepicker({
		//alert(checkout);
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
			
			
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
		//alert(data('datepicker'));
	
});

$("#exhibition_name").change(function(e){
var name =$(this).val();
			checkName(name);
		});

function checkName(name)
{
	
	var exibitionname = '<?php echo isset($exhibition['exhibition_name'])?$exhibition['exhibition_name']:'';?>';
	

		if(name.length > 0 && exibitionname != name){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=ExibitionNameExsist', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{name:name},
				success: function(json) {
					if(json > 0){
						$("#exhibition_name").val('');
						$("#exists").show();
						$("#exists").html('Exibitioin Name already exists!');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$("#exists").hide();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			return true;
		}
	}
</script>

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>