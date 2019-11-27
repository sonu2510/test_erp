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
	'text' 	=> $display_name.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$click = '';
if(isset($_GET['indent_id']) && !empty($_GET['indent_id'])){
	
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$indent_id = base64_decode($_GET['indent_id']);
		$cond='AND ph.cancle_qty!=0 GROUP BY purchase_indent_items_id';
		$inventory = $obj_inventory->getPen($indent_id,$cond);
		$click = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>  <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span>Cancel Indent Listing</span>
          	<span class="text-muted m-l-small pull-right">
            	
                
            </span>
          </header>
        <?php if($inventory)
		  {
			  ?>
               
               
	<div class="panel-body">
    <div class="well m-t">
    	 <div class="row"> 
         	<div class="col-xs-6">
            <strong>Vender Name:</strong>
           	  <h4><?php echo $inventory[0]['vander_first_name'].' '.$inventory[0]['vander_last_name'];?></h4>
                    	 <p> <?php echo $inventory[0]['address'];?>
                         <br><?php echo $inventory[0]['email_id'];?> <br> </p> 
       	   </div> 
              
            <div class="col-xs-6  text-right">
           <?php
								$addedByData = $obj_inventory->getUser($inventory[0]['added_by_id'],$inventory[0]['added_by_type_id']);
								$addedByImage = $obj_general->getUserProfileImage($inventory[0]['added_by_id'],$inventory[0]['added_by_type_id'],'100_');
								$addedByInfo = '';
								$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if($addedByData['city']){ $addedByInfo .= $addedByData['city'].', '; }
									if($addedByData['state']){ $addedByInfo .= $addedByData['state'].' '; }
									if(isset($addedByData['postcode'])){ $addedByInfo .= $addedByData['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$addedByData['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $addedByData['first_name'].' '.$addedByData['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>
       	   </div> 
                            	
                                
</div></div>
                                 <p class="m-t m-b">Receivable date: 
                                 <strong><?php echo dateFormat(4,$inventory[0]['due_date']);?></strong>    <i class="fa fa-clock-o"></i>  
                                 </p> 
                                 <div class="line"></div>
                           <table class="table"> 
                           <thead>
                           <tr>
                            <th>S.NO</th>
                             <th>ITEMS</th> 
                            <th>Date</th>
                            <th>QTY</th>
                            <th></th>
                             </tr> 
                              </thead> 
                              <tbody> 
                              
                              <?php $n = 1; $total=0; foreach($inventory as $items) {?>
                              <tr>
                              
                              <td><?php echo $n;?></td>
                              <td>
                              <?php 
							  $item_id = $items['item_id'];
							
							  if($items['table_name'] == 'product_zipper')
							  {
								
								$cond = 'product_zipper_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Zipper-</b>'.$inv['zipper_name'];
									$product_unit = $inv['zipper_unit'];
							  }
							  
							   if($items['table_name'] == 'product_spout')
							  {
								
								$cond = 'product_spout_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Spout-</b>'.$inv['spout_name'];
									$product_unit = $inv['spout_unit'];
							  }
							  
							   if($items['table_name'] == 'product_accessorie')
							  {
								
								$cond = 'product_accessorie_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Accessorie-</b>'.$inv['product_accessorie_name'];
									$product_unit = $inv['product_accessorie_unit'];
							  }
							  
							   if($items['table_name'] == 'product_material')
							  {
								
								$cond = 'product_material_id ='.$item_id;
								$inv = $obj_inventory->getName($cond,$items['table_name']);
								    echo '<b>Material-</b>'.$inv['material_name'];
									$product_unit = $inv['material_unit'];
							  }
							  
							   if($items['table_name'] == 'ink_master')
							  {
								$cond = 'ink_master_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo $inv['make_name'];
									$product_unit = $inv['ink_master_unit'];
							  }
							   if($items['table_name'] == 'ink_solvent')
							  {
								$cond = 'ink_solvent_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Ink Solvent-</b>'.$inv['make_name'];
									$product_unit = $inv['ink_solvent_unit'];
							  }
							   if($items['table_name'] == 'adhesive')
							  {
								$cond = 'adhesive_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Adhesive-</b>'.$inv['make_name'];
									$product_unit = $inv['adhesive_unit'];
							  }
							   if($items['table_name'] == 'adhesive_solvent')
							  {
								$cond = 'adhesive_solvent_id ='.$item_id;
								$inv = $obj_inventory->getNameR($cond,$items['table_name']);
								    echo '<b>Adhesive Solvent-</b>'.$inv['make_name'];
									$product_unit = $inv['adhesive_solvent_unit'];
							  }
							  ?>
                            
                              </td>
                             <td> <span> <?php echo dateFormat(4,$inventory[0]['date']); ?></span></td>
                              <td><?php echo $items['can_qty']; $total=$total+$items['can_qty'];?>&nbsp;&nbsp;
							  <?php echo $product_unit;?></td>
                              <?php $pen=$items['purchase_indent_items_id'];
							 $cond='AND cancle_qty!=0';
							 $can=$obj_inventory->getHistory($pen,$cond);
							?>
							  
                              <td>
							 <?php 
							 $username='';
							foreach($can as $cancle)
							{	
								 $process_by = $obj_inventory->getUser($cancle['user_id'],$cancle['user_type_id']);
				$username.='<span style="color:#26B756">'.$process_by['user_name'].'</span> On <span>'.dateFormat(4,$inventory[0]['date']).'</span>    <span style="color:#00F">'.$cancle['cancle_qty'].'</span>&nbsp;'.$product_unit.' <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#show_'.$cancle['history_id'].'"><span style="color:#F00"><u>Review</u></span> </a></div>
				<div id="show_'.$cancle['history_id'].'" class="panel-collapse collapse" style="color:#C3F">'.$cancle['review'].'</span></div><br>';
							}
							?>
<style>.popover 
{
	position: absolute;
	top: 0;
	left: 0;
	z-index: 1060;
	display: none;
	max-width:350px; 
	padding: 1px;
	text-align: left;
	white-space: normal;
	background-color: #fff;
	-webkit-background-clip: padding-box;
	background-clip: padding-box;
	border: 1px solid #ccc;
	border: 1px solid rgba(0,0,0,.2);
	border-radius: 6px;
	-webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
	box-shadow: 0 5px 10px rgba(0,0,0,.2);
}</style>   
                              <button class="btn btn-sm btn-info" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $username; ?>' title="" data-original-title="<button type=&quot;button&quot; class=&quot;close pull-right&quot; data-dismiss=&quot;popover&quot;><i class=&quot;fa fa-times&quot;></i></button>Cancel" aria-describedby="popover286640">View History</button>
                              </td>
                              </tr>
                              <tr>
                  
                  </tr>
							  <?php
							  
							   $n++;}?>
                              <tr> <td colspan="3" class="text-right no-border"><strong>Total</strong></td>
                                  <td><strong><?php echo $total;?></strong></td> 
                                  </tr> 
                                  </tbody> 
                                  </table>
                                <p class="m-t m-b">Discription: 
                                 <strong><?php echo isset($inventory[0]['description'])?$inventory[0]['description']:'';?></strong>
                                 <br> Reminder Date: 
                                 <span class="label bg-success"><?php echo dateFormat(4,$inventory[0]['reminder_date']);?></span><a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=index&status='."2", '',1);?>" style="float: right;">Cancel</a>
                               </p> 
                        </div>           
              </section>
              </div>    
              </div>
                   <?php }
			else
			echo '<div>NO RECORD FOUND...!</div>';  ?>       
</section></section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
</script>