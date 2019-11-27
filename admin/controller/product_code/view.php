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


if($display_status){	
	if(isset($_GET['product_id']) && !empty($_GET['product_id']))
	{
		$product_id = decode($_GET['product_id']);
		$product_code = $obj_product_code->getProductCodeData($product_id);
		//echo $product_id;
		$product_view=$obj_product_code->getView($product_id);
		
		//printr($product_Total);
	}	
?>

 <?php // mansi ======================= ?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
      <div class="col-sm-12">
        
            <section class="panel">  
            	
            <header class="panel-heading bg-white">
                 <span>  product code Detail</span> 
                 
 		     </header>
              
            <div class="panel-body">
              	 <label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class=code ><b>  <?php echo $product_code['product_code'];?> </b>&nbsp;
                  		<?php  $product_Total=$obj_product_code->getTotal($product_id); ?>
					  Total Available Stock : <?php echo $product_Total['store_qty'] -  $product_Total['dis_qty']; ?>
                    
                    </span>
                    
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                
               			
                        			
                     <div class="form-group">
						<div class="table-responsive"><br />
							<table class="table table-striped b-t text-small" width="100%">
								<thead>
									<tr>
                                          <th>Proforma No</th>
                                          <th>Purchase Invoice</th>
                                          <th>Sales Invoice No</th>
										  <th>Order No</th>
                                          <th>From Company</th>
                                          <th>To Company</th>
                                          <th>Store Date</th>
                                          <th>Dispatch Date</th>
                                          <th>Description</th>
                                          <th>Store Qty</th>
                                          <th>Dispatch Qty</th>
                                          
                                    </tr>
                                </thead>    
                                <tbody>
                                <?php 
								// $product_view = $obj_product_code->getView($_GET['product_id']);
								//printr($product_view);
								$tot_store = 0;
								$tot_dis=0;
                                 foreach($product_view as $product){ ?>
                                
                                	<tr>
                                    	<td> <?php echo $product['proforma_no']; ?> </td>
                                        
                                        <td>
                                        <?php if ($product['status']==0)
													{
														 $purchase_invoice_no=$product['invoice_no'];
														 $sales_invoice_no='Na';
													}
													else
													{
														$sales_invoice_no=$product['invoice_no'];
														$purchase_invoice_no='Na';
													}	 
														  ?> 
                                        
                                        
                                         <?php echo $purchase_invoice_no; ?> </td>
                                        
                                        <td> <?php echo $sales_invoice_no; ?> </td>
                                        
                                        <td> <?php echo $product['order_no']; ?> </td>
                                        
                                        <td> <?php if ($product['status']==0)
													{
														 $form_comp=$product['company_name'];
														 $to_comp='Na';
													}
													else
													{
														$to_comp=$product['company_name'];
														$form_comp='Na';
													}	 
														  ?>
                                                          
                                                       <?php echo $form_comp; ?>   
                                                           </td>
                                                       
                                         <td> <?php echo $to_comp; ?> </td>                   
                                        
                                         <td> <?php if ($product['status']==0)
													{
														 $s_date=$product['date_added'];
														 $d_date='Na';
													}
													else
													{
														$d_date=$product['date_added'];
														$s_date='Na';
													}	 
														  ?>
                                                          
                                                       <?php echo $s_date; ?>  </td>
										
									    <td> <?php echo $d_date; ?> </td>
                                        
                                        <td> <?php  if ($product['description']==0)
													{
														 $des="store";
													}
													else if ($product['description']==1)
													{
														$des="goods return";
													}	 
													else
													{
														$des="dispatch";
													}
										
													 echo $des; ?> </td>
                                        
                                        <td> <?php if ($product['status']==0)
													{
														 $store_qty=$product['qty'];
														 $dis_qty='Na';
													}
													else
													{
														$dis_qty=$product['qty'];
														$store_qty='Na';
													}	 
														  ?>
										
													<?php echo  $store_qty;  ?> </td>
                                        
                                        <td> <?php echo $dis_qty; ?> </td>
                                       <?php $tot_store= $tot_store+$store_qty; 
									   		$tot_dis = $tot_dis + $dis_qty; ?> 
									 </tr>
                                     <?php } ?>
                                     <tr>
                                     	<td colspan="9" ></td>
                                     	<td><?php echo $tot_store;?></td>
                                        <td><?php echo $tot_dis;?></td>
                                     </tr>
							 </tbody> 
						 </table>
              		 </div>
                   </div>
                  
                  <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">             
                          <a class="btn btn-default" name="btn_cancel" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
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
		include(DIR_ADMIN.'access_denied.php');
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo HTTP_SERVER;?>admin/controller/rack_master/css/sidebar.css" />
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
  