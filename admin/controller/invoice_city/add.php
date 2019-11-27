<?php

//kinjal
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
	'text' 	=> $display_name.' Add',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';


if(isset($_GET['invoice_city_id']) && !empty($_GET['invoice_city_id'])){
		$city_id = $_GET['invoice_city_id'];
		$city = $obj_invoicecity->getCityData($city_id );
		
		$edit = 1;
}
//Close : edit
$style = 'style="display:none;"';
//insert user
	if(isset($_POST['btn_save'])){

		$post = post($_POST);
		$insert_id = $obj_invoicecity->addcity($post);
		//printr($insert_id);die;
		if($insert_id == 0)
		{
			$obj_session->data['warning'] = NO;
			page_redirect($obj_general->link($rout, 'mod=add', '',1));
		}
		else
		{
			$obj_session->data['success'] = ADD;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$city_id = $city['invoice_city_id'];
		$update_id = $obj_invoicecity->updateCity($city_id ,$post);
		if($update_id == 0)
		{
			$obj_session->data['warning'] = NOU;
			page_redirect($obj_general->link($rout, 'mod=add&invoice_city_id='.$_GET['invoice_city_id'], '',1));
		}
		else
		{
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
		//$obj_session->data['success'] = UPDATE;
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
            <?php $city_nm = $obj_invoicecity->getICity();
			//printr($city_nm);
		
			?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City Name</label>
                <div class="col-lg-4">
                	<input type="text" name="city_name" value="<?php echo isset($city) ? $city['city_name'] : '' ; ?>"
                     placeholder="City" id="city_name" class="form-control validate[required]" />
                     <input type="hidden" name="old_city_name" value="<?php echo isset($city) ? $city['city_name'] : '' ; ?>"/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City Code</label>
                <div class="col-lg-4">
                	<input type="text" name="city_code" value="<?php echo isset($city) ? $city['city_code'] : '' ; ?>" 
                    placeholder="City Code" id="city_code" class="form-control validate[required,custom[onlyNumberSp]]" />
                </div>
                </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control validate[required]">
                    <option value="1" <?php echo (isset($city['status']) && $city['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($city['status']) && $city['status'] == 0)?'selected':'';?>> Inactive</option>
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
<script src="<?php echo HTTP_SERVER;?>ckeditor2/ckeditor.js"></script>


<script>	
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
    });
	
	/*$('#city_name').keyup(function() 
	{
		if (this.value.match(/[^a-zA-Z ]/g))
		{
			alert("Please Enter Only Characters");
			$('#city_name').val('');
		}
	});*/
	
	
</script>