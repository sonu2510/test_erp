<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.'',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index_opening_balance&year='.$_GET['year'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
$edit ='';
$class = 'collapse';
if(isset($_GET['daily_stock_opening_balance_id']))
{
		$daily_stock_opening_balance_id = decode($_GET['daily_stock_opening_balance_id']);
		$opening_balance_details = $obj_invoice->GetOpening_Balance_detail($daily_stock_opening_balance_id);
			$dt = strtotime($opening_balance_details['manufactured_date']);
			$nmonth=date("F", $dt);
			 $dt =(date("Y",$dt));
		//	printr($opening_balance_details);
			$edit = 1;
}
if($display_status) {
    if(isset($_POST['btn_add'])){
		$post = post($_POST);
		$data = $obj_invoice->AddOpening_Balance($post);
	    page_redirect($obj_general->link($rout, 'mod=index_opening_balance', '',1));
    } 
    if(isset($_POST['btn_update'])){
    		$post = post($_POST);
            //printr($post);die;
    		$data = $obj_invoice->UpdateOpening_Balance($daily_stock_opening_balance_id,$post);
    		page_redirect($obj_general->link($rout, 'mod=index_opening_balance&year='.encode($dt).'&month='.encode($opening_balance_details['month']), '',1));
    }

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white">
                 <span> Daily Stock Opening Balance</span>
               
                
                </header>
          
          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
          
			 <div class="form-group"> 
                   <label class="col-lg-2 control-label"><span class="required">*</span>Month</label>
                           <div class="col-lg-3">
                                 <select name="month" id='month' class="form-control validate[required]">
                                  <option>Select Month</option>
                                  <?php for($m=1;$m<=12;$m++)
                                        { ?>
                                            <option value=<?php echo $m;?> <?php if(isset($daily_stock_opening_balance_id) && $opening_balance_details['month'] == $m) { ?> selected="selected" <?php } ?> ><?php echo DateTime::createFromFormat('!m', $m)->format('F');?></option>
                                 <?php  }?>
                                </select>
                           </div>
                  
                        <label class="col-lg-3 control-label">Date </label>
                           <div class="col-lg-3">
                              <input type="text" name="manufactured_date" readonly data-date-format="yyyy-mm-dd" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['manufactured_date'] : date("Y-m-d") ; ?>" placeholder="Invoice Date" id="manufactured_date" class="input-sm form-control datepicker" />
                           </div>
                          
             </div> 
             
             <h4><i class="fa fa-edit"></i> For Pouch</h4>
             <div class="line m-t-large col-lg-11" style="margin-top:-4px;"></div>    
             <div class="form-group"> 
                 
                <label class="col-lg-2 control-label"><span class="required">*</span>Month Opening</label>
                   <div class="col-lg-3">
					    <input type="text" name="month_opening" id="month_opening" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['month_opening'] :''; ?>" placeholder="Month Opening" class="form-control validate[required]">
	                </div>
                    
                  <label class="col-lg-3 control-label"><span class="required">*</span>Quantity Manufactured</label>
                   <div class="col-lg-3">
					<input type="text" name="quantity_manufactured" id="quantity_manufactured" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['quantity_manufactured'] : '' ; ?>" placeholder="Quantity Manufactured"  class="form-control validate[required]">
	            </div>
             </div>
             
             <h4><i class="fa fa-edit"></i> For Roll</h4>
             <div class="line m-t-large col-lg-11" style="margin-top:-4px;"></div>    
             <div class="form-group"> 
                 
                <label class="col-lg-2 control-label"><span class="required">*</span>Month Opening</label>
                   <div class="col-lg-3">
					    <input type="text" name="month_opening_roll" id="month_opening_roll" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['month_opening_roll'] :''; ?>" placeholder="Month Opening For Roll" class="form-control validate[required]">
	                </div>
                    
                  <label class="col-lg-3 control-label"><span class="required">*</span>Quantity Manufactured</label>
                   <div class="col-lg-3">
					<input type="text" name="quantity_manufactured_roll" id="quantity_manufactured_roll" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['quantity_manufactured_roll'] : '' ; ?>" placeholder="Quantity Manufactured For Roll"  class="form-control validate[required]">
	            </div>
             </div>
             
             <h4><i class="fa fa-edit"></i> For Scrap</h4>
             <div class="line m-t-large col-lg-11" style="margin-top:-4px;"></div>    
             <div class="form-group"> 
                 
                <label class="col-lg-2 control-label"><span class="required">*</span>Month Opening</label>
                   <div class="col-lg-3">
					    <input type="text" name="month_opening_scrap" id="month_opening_scrap" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['month_opening_scrap'] :''; ?>" placeholder="Month Opening For Scrap" class="form-control validate[required]">
	                </div>
                    
                  <label class="col-lg-3 control-label"><span class="required">*</span>Quantity Manufactured</label>
                   <div class="col-lg-3">
					<input type="text" name="quantity_manufactured_scrap" id="quantity_manufactured_scrap" value="<?php echo isset($opening_balance_details) ? $opening_balance_details['quantity_manufactured_scrap'] : '' ; ?>" placeholder="Quantity Manufactured For Scrap"  class="form-control validate[required]">
	            </div>
             </div>
             
             <!--<h4><i class="fa fa-edit"></i> For Silica Gell</h4>
             <div class="line m-t-large col-lg-11" style="margin-top:-4px;"></div>    
             <div class="form-group"> 
                 
                <label class="col-lg-2 control-label"><span class="required">*</span>Month Opening</label>
                   <div class="col-lg-3">
					    <input type="text" name="month_opening_Silica" id="month_opening_Silica" value="<?php //echo isset($opening_balance_details) ? $opening_balance_details['month_opening_Silica'] :''; ?>" placeholder="Month Opening For Silica" class="form-control validate[required]">
	                </div>
                    
                  <label class="col-lg-3 control-label"><span class="required">*</span>Quantity Manufactured</label>
                   <div class="col-lg-3">
					<input type="text" name="quantity_manufactured_Silica" id="quantity_manufactured_Silica" value="<?php //echo isset($opening_balance_details) ? $opening_balance_details['quantity_manufactured_Silica'] : '' ; ?>" placeholder="Quantity Manufactured For Silica"  class="form-control validate[required]">
	            </div>
             </div>-->
             
                <?php if($edit){?>
                     <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                           <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index_opening_balance&year='.encode($dt).'&month='.encode($opening_balance_details['month']), '',1);?>">Cancel</a>
                        </div>
                     </div>
                     <?php } else {?> 
                     <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                           <button type="submit" name="btn_add" id="btn_add" class="btn btn-primary">Add </button>
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index_opening_balance&year='.encode($dt).'&month='.encode($opening_balance_details['month']), '',1);?>">Cancel</a>
                        </div>
                     </div>
                     <?php }  ?>
              
            
            </form>
                    
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  
 $(document).ready(function() {
   $("#manufactured_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
 
   
   
    });  
</script>
          
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>