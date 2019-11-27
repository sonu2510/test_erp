<?php
include("mode_setting.php");
$edit = '';
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('view',311)){
    	$display_status = false;
    }else{
		
		$product_id= base64_decode($_GET['product_id']);
    	$color_details = $obj_catalogue_category->getCatalogue_category_Color_Details($product_id);	
		$edit = 0;
	//	printr($color_details);
	} 
	
}else{ 
    if(!$obj_general->hasPermission('view',311)){
	$display_status = false;
    }
} 


//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);


$bradcums[] = array(
	'text' 	=> 'Product  List',
	'href' 	=> $obj_general->link($rout, 'mod=list_product', '',1),
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

 
if($display_status){ 

	
	

?> 

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row"> 
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");		?>	
        </div> 
      
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail
             <span class="text-muted m-l-small pull-right">
                 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                   <a class="label bg-info " href="javascript:void(0);" onclick="print()"><i class="fa fa-print" ></i> Print</a>
                 </span>
          </header>
          
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            <!-- manirul 11-2-17 -->
              <div class="row" id="print_data">
              <div class="col-lg-12" > 	<?php 
           //   $i=2;
              foreach($color_details as $details){
                 $style='';
                /*  if($i%2==0){
                      $style='style="page-break-before: always;"';
                    
                  }*/
                    //printr($style);
              	$color = $obj_catalogue_category->getCatalogue_categoryDetails($details['catalogue_category_id']);	
              ?>
            <table id="MyStretchGrid" class="table table-striped b-t text-small " <?php echo $style;?>>
                 	<tr>
                 	   <center><b><u> <header class="panel-heading bg bg-inverse"> <?php echo $details['catalogue_category_name'];?> </header></b></u></center>
                 	 
                	</tr>	
        	<?php   $selected_color = array();
                     if(isset($color['color']) && $color['color']){
                        $selected_color=explode(",",$color['color']);
                     }
                     $selected_size = array();
                     
                     if(isset($color['size_master_id']) && $color['size_master_id']){
                         
                        $selected_size=explode(",",$color['size_master_id']);
                     }
                     
                     ?>
                     
                     
               	<tr>
               	     <th>No</th>
               	     <th>Color Name</th>
               	    <?php foreach($selected_size as $size){
               	         $size_volume=$obj_catalogue_category->getSizeData($size);
               	        echo'<th>'.$size_volume['volume'].'<br>'.$size_volume['zipper_name'].'</th>';
               	    }?>
               	 
               	</tr>
               	 <?php 
               	  $i=1;
               	 foreach($selected_color as $color){
               	    $color_name=$obj_catalogue_category->color_name($color);
               	 //  printr($category_color_details);
               	 ?>
               	        <tr >  
               	            <td><?php echo $i;?></td>
               	            <td><?php echo $color_name;?></td>   
               	               <?php 
               	               
               	               foreach($selected_size as $size){
               	                     $size_volume=$obj_catalogue_category->getSizeData($size);
               	                     $category_color_details = $obj_catalogue_category->getCategoryColorDetails($details['catalogue_category_id'],$size,$color);
               	                     $product_code_id = $obj_catalogue_category->getProductcode($size,$color);	
               	                $result = $obj_catalogue_category->getdomesticStock($product_code_id); 
               	                       $rm_qty='NA'; 
               	                        if(isset($result['grouped_s_id'])){
                                    		$dispatch_qty=$obj_catalogue_category->getdomesticStockDispatch($product_code_id);
                                    		$rm_qty= ($result['qty']-$dispatch_qty);
                                    		if($rm_qty!='0'){
                                    	    	$rm_qty=$rm_qty;
                                    		}
               	                        }
               	                         $sel_color = array();								   
                                            if(isset($category_color_details['color']) && $category_color_details['color']){
                                                $sel_color=explode(",",$category_color_details['color']);
                                            }
                                        
               	                         
               	                        if(($size ==$category_color_details['size_master_id']) && (in_array($color,$sel_color))){
               	                            
               	                               $check=$rm_qty .'<b style="color:blue"> ('.$result['grouped_box_id'].')</b>';
               	                              // $style='style="background-color:#bdceab" ';
               	                             
               	                        }else{
               	                               $check='<i class="fa fa-times text-danger"></i>';
               	                             //  $style='';
               	                        }
               	                     ?> 
               	                       <td <?php //echo $style;?>><b><u><?php echo $check;?></u></b></td>
               	         <?php    }?> 
               	         
               	             
               	       
               	        </tr>
               	        
               	        
               	       
               	  <?php  $i++;  }?>
               	</table>
             	   
              <?php $i++;}?>
         
         
            
              </div>
              </div>
               <div class="form-group">
              <div class="col-lg-9 col-lg-offset-3">
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=list_product', '',1);?>">Cancel</a>
             
                </div>
                </div>
      
                
                
              </div>
            </form>
          </div>
        </section>
         
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script>
$("#excel_link").click(function(){

        var html='';
        html+= '<style>  table, th, td {   border: 1px solid black; }</style>'; 
        html+= $('#print_data').html(); 
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Daily-stock.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() { 
						$('#downloadFile').get(0).click();
					});
      /*  }
		
    });*/


});	
function print() {
	
    var html="<html>";
	html+='<head>';
	
 html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";
	



    html+= $('#print_data').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}
</script>
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>