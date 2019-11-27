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
	'text' 	=>$display_name.' List',
	'href' 	=>$obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Credit Note List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$limit = 50;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = '';
}
$credit = $obj_invoice->getCredit(decode($_GET['invoice_no']));
if($display_status) {

?>
<section id="content">
  <section class="main padder">
      <h4><i class="fa fa-users"></i> <?php echo $display_name;?></h4>
    <div class="clearfix">
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> 		  	
			<span>Credit Note Listing</span>          		
      		<span class="text-muted m-l-small pull-right">
                    
            </span>   
          </header>
          <div class="panel-body">
          </div>
          <form name="form_list" id="form_list" method="post">
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
			      <th>Sr.No</th>	
                  <th>Credit No.</th>
                  <th>Generated Date</th>
                </tr>
              </thead>
              <tbody>
              <?php  $i=1;
                foreach($credit as $note){
				?>
                <tr>
                	<td class="col-lg-1"><a href="<?php echo $obj_general->link($rout, 'mod=view_credit_note&invoice_no='.$_GET['invoice_no'].'&cre_no='.$note['cre_no'].'&is_delete='.$_GET['is_delete'],'',1); ?>" ><?php echo $i;?></a></td>
                    
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view_credit_note&invoice_no='.$_GET['invoice_no'].'&cre_no='.$note['cre_no'].'&is_delete='.$_GET['is_delete'],'',1); ?>" ><?php echo $note['cre_no'];?></a></td>
                    
                   	<td><a href="<?php echo $obj_general->link($rout, 'mod=view_credit_note&invoice_no='.$_GET['invoice_no'].'&cre_no='.$note['cre_no'].'&is_delete='.$_GET['is_delete'],'',1); ?>" ><?php echo dateFormat('4',$note['date_added']);?></a></td>
                </tr> 
        	<?php $i++; } ?>
					
              </tbody>
          </table>
          
          </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              	<div class="form-group">
                     <div class="col-lg-9 col-lg-offset-3"> 
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'], '',1);?>">Cancel</a>
                   </div>
        		 </div>   
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/><strong></strong>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
</script>