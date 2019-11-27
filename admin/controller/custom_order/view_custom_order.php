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
if(isset($_GET['custom_order_id']) && !empty($_GET['custom_order_id'])){
	$custom_order_id = base64_decode($_GET['custom_order_id']);
	//echo $custom_order_id;die;
	$custom_detail = $obj_custom_order->getFullCustomDetail($custom_order_id);
	//printr($custom_detail);
	//$currency_name = $obj_bank->getCurrencyCode($bank_detail['curr_code']);	
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
        
        	<div class="col-sm-12">
        		<section class="panel">
        			<header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
        			<div class="panel-body form-horizontal">                    
                    	<div class="form-group">
                            <label class="col-lg-3 control-label">Note<br /><small class="text-muted">(for internal purpose)</small></label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo ucwords($custom_detail['multi_order'][0]['product_note']);?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Special Instruction <br /><small class="text-muted">(this will be displayed in order)</small></label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($custom_detail['multi_order'][0]['product_instruction']);?>
                            	</label>
                        	</div>
                      	</div>
                       
                        <div class="form-group">
                            <label class="col-lg-3 control-label">DieLine <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                            <div class="col-lg-5">
                                <label class="control-label normal-font">
								<?php 
                                 $html = '';			
                                 if(isset($custom_detail) && !empty($custom_detail)) { //printr($custom_detail);
									  $html .='<div class="carousel slide auto" id="c-slide-'.$custom_order_id.'" style="width: 150px;">
										<ol class="carousel-indicators out">';
										 for($j=0;$j<(count($custom_detail['multi_order']));$j++){ 
											$html .='<li data-target="#c-slide-'.$custom_order_id.'" data-slide-to="'.$j.'" class=""></li>';
                                     	  }
										$html .='</ol>';
										$html .='<div class="carousel-inner" style="height: 180px;">';
									    $i=0;
                                      
										foreach($custom_detail['multi_order'] as $image){
											$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
											if($i==0){
												$html .=' <div class="item active">';
											}else{
												$html .=' <div class="item">';
											}
											
											if($ext!='pdf')
											{
                                            	$html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/dieline/'.$image['name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/'.$image['name'].'"></a></p><center><a href="'.HTTP_UPLOAD.'admin/dieline/'.$image['name'].'" target="_blank">'.$image['name'].'</a></center>';
												
											}
											else
											{
												$html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/pdfdieline/'.$image['name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/pdf.jpg"></a></p>
															<center><a href="'.HTTP_UPLOAD.'admin/pdfdieline/'.$image['name'].'" target="_blank">'.$image['name'].'</a></center>';	
											}
                                        	$html .='</div>';
                                        	$i++;
                                    	}
									$html .='</div>
                                    <a class="left carousel-control" style="width:0px;" href="#c-slide-'.$custom_order_id.'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
                                    <a class="right carousel-control" style="width:0px;" href="#c-slide-'.$custom_order_id.'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
                                	echo $html;
                                 } else {
                                    echo '<p><img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/blank.jpg" alt="Image"></p>';
                                 }
                                ?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Art Work</label>
                        	<div class="col-lg-5">
                            	<label class="control-label normal-font">
                                	<?php 
                                 $html = '';			
                                 if(isset($custom_detail['artwork']) && !empty($custom_detail['artwork'])) {
									  $html .='<div class="carousel slide auto" id="c-slide-'.$custom_detail['artwork'][0]['order_product_image_id'].'" style="width: 150px;">
										<ol class="carousel-indicators out">';
										 for($j=0;$j<(count($custom_detail['artwork']));$j++){ 
											$html .='<li data-target="#c-slide-'.$custom_detail['artwork'][0]['order_product_image_id'].'" data-slide-to="'.$j.'" class=""></li>';
                                     	  }
										 
										$html .='</ol>';
										$html .='<div class="carousel-inner" style="height: 180px;">';
									    $i=0;
                                      
										foreach($custom_detail['artwork'] as $image){
											$ext = pathinfo($image['image_name'], PATHINFO_EXTENSION); 
											if($i==0){
												$html .=' <div class="item active">';
											}else{
												$html .=' <div class="item">';
											}
											
											if($ext!='pdf')
											{
                  										  $html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'"></a></p><center><a href="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'" target="_blank">'.$image['image_name'].'</a></center>';
											}
											else
											{
												$html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/pdfartwork/'.$image['image_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/pdf.jpg"></a></p><center><a href="'.HTTP_UPLOAD.'admin/pdfartwork/'.$image['image_name'].'" target="_blank">'.$image['image_name'].'</a></center>';		
											}
                                        	$html .='</div>';
                                        	$i++;
                                    	}
									$html .='</div>
                                    <a class="left carousel-control" style="width:0px;" href="#c-slide-'.$custom_detail['artwork'][0]['order_product_image_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
                                    <a class="right carousel-control" style="width:0px;" href="#c-slide-'.$custom_detail['artwork'][0]['order_product_image_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
                                	echo $html;
                                 } else {
                                         echo '<p><img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/blank.jpg" alt="Image"></p>';
                                  }
                                ?>
                            		
                            	</label>
                        	</div>
                      	</div>
                    
        				<div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&custom_order_id='.$_GET['order_id'].'&filter_edit='.$_GET['filter_edit'], '',1);?>">Cancel</a>
        					</div>
        				</div>
        
        			</div>
        		</section>
        	</div>
        </div>
    </section>
</section>
<style>
.right{ top: 0px;}
</style>

