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

if($display_status) {

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
    
    <div class="col-lg-12" >
    <section class="panel">
     <!-- <header class="panel-heading bg-white"> <span><?php echo $display_name;?> Listing</span> <span class="text-muted m-l-small pull-right"> <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add Colors </a> </span> </header>-->
      <div class="panel-body">
      <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
            <div class="form-group">
              <label class="col-lg-3 control-label">Product</label>
              <div class="col-lg-4">
                <select name="product" class="form-control" id="product">
                  <option value="">Select Product</option>
                  <?php
                                  $products = $obj_color->all_product();
                                foreach($products as $product){  ?>
                  <option value="<?php echo $product['product_id']?>"><?php echo $product['product_name']?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
           
            <div class="form-group">
              <label class="col-lg-3 control-label"><span class="required">*</span>Volume</label>
              <div class="col-lg-3">
                <select name="volume" id="volume" class="form-control">
                  <?php $all_volume = $obj_color->all_volume(); ?>
                  <option value="">Select Volume</option>
                  <?php foreach($all_volume as $volume){ ?>
                  <option value="<?php echo $volume['pouch_volume_id']?>"> <?php echo $volume['volume']?> </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
            <div id="available_colors"></div>
            <h4 class="color"><i class="fa fa-plus-circle color"></i> All Colors</h4>
            <div class="form-group color">
              <?php $colors = $obj_color->all_colors();
                             foreach($colors as $color) { ?> 
               <div style="display:inline-block;text-align:center;margin:5px 2px 10px 10px;border-style:solid;border-width:1px;">              
              <div style="display:inline-block;padding:10px 10px;text-align:center"><?php echo $color['color'] ?><br />
              <div style="display:inline-block;margin:5px;"">
              <div class="btn " style="margin:5px 5px 5px 20px;background-color:<?php echo $color['color_1']?>"></div>
              <div class="btn " style="margin:5px 5px 5px -4px;background-color:<?php echo $color['color_2']?>"></div>
             </div>
              </div>
              </div>               
            <?php } ?>  
           </div>
        </div>
        </div>
        </div>
      </form>
    </section>
  </section>
</section>
<style>


.btn {
    border-radius: 1px;
	color: rgb(16,120,149);
	padding: 25px 35px;
	/*padding: 20px 16px;*/
	display: inline-block;
	float:left;
}
.green{
	color:green;	
}
.red{
	color:red;	
}

.button {
    background-color: rgb(101,207,44); /* Green */
    border: none;
    color: white;
    padding: 5px 15px;
    text-align: center;
    /*text-decoration: none;*/
	
    display: inline-block;
    font-size: 15px;
	margin-left:18px;
    /*margin: 4px 2px;*/
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    cursor: pointer;
}
.add{
	background-color: rgb(255,135,132); 
    border: none;
    color: white;
    padding: 5px 15px;
    text-align: center;
    /*text-decoration: none;*/
	
    display: inline-block;
    font-size: 15px;
	margin-left:18px;
    /*margin: 4px 2px;*/
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    cursor: pointer;
}
.add:hover {
    background-color: #555555;
    color: white;
}
.remove:hover {
    background-color: #555555;
    color: white;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script> 
<script type="application/javascript">

function view_get()
{
		var product_id = $("#product").val();
		var volume_id = $("#volume").val();
			if(volume_id!='')
			{
				$('.color').hide();	
				var color_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaycolor', '',1);?>");
				$.ajax({
				type: 'post',
				url : color_url,
				data:{product_id:product_id,volume_id:volume_id},	
				success: function(response){
					$("#available_colors").html(response);
				}
			 });
			}
} 

$(document).ready(function(){
			
	$(document).change(function(){
		view_get();
	})
});

function add(color_id,product_id,volume_id)
{
	var color_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_color', '',1);?>");
			$.ajax({
			type: 'post',
			url : color_url,
			data:{product_id:product_id,volume_id:volume_id,color_id:color_id},	
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				view_get();
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning"); 
			}
		 });
}

function remove(color_v_id)
{
	var color_v_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=delete_color', '',1);?>");
			$.ajax({
			type: 'post',
			url : color_v_url,
			data:{color_v_id:color_v_id},	
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				view_get();
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning"); 
			}
		 });
}


</script> 
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
