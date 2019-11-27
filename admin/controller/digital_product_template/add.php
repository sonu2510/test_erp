 <?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
  'text'  => 'Dashboard',
  'href'  => $obj_general->link('dashboard', '', '',1),
  'icon'  => 'fa-home',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' List',
  'href'  => $obj_general->link($rout, '', '',1),
  'icon'  => 'fa-list',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' Detail',
  'href'  => '',
  'icon'  => 'fa-edit',
  'class' => 'active',
);
//Close : bradcums
//Start : edit
$edit = '';
if(!$obj_general->hasPermission('add',$menuId)){
  $display_status = false;
}
//Close : edit
if(isset($_GET['status'])){
	$price_status = $_GET['status'];	
}else{
	$price_status= 0;
}
$quantity_error = '';
if($display_status){
  $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
  $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
  if(isset($_POST['btn_save'])){
    //printr($_POST);die;
    //echo "Sdada";die;
    $order_id = $obj_template->addTemplate($_POST['templateid'],$price_status);
    page_redirect($obj_general->link($rout, 'index&status='.$price_status, '',1));
  }
  if(isset($_POST['btn_update'])){
    //echo "Sdada";die;
    
    $order_id = $obj_template->updateTemplate($_POST,$price_status);
    page_redirect($obj_general->link($rout, 'index&status='.$price_status, '',1));
  }
  
  
$template_id = 0;
if(isset($_GET['template_id']) && !empty($_GET['template_id'])){
  //if(!$obj_general->hasPermission('edit',$menuId)){
  //  $display_status = false;
  //}else{
    $template_id = base64_decode($_GET['template_id']);
    $product = $_GET['product_id'];
    $template = $obj_template->getTemplateInfo($template_id,$price_status);
    
    $detailslist = $obj_template->getaddProductDetails($template_id,$product,$price_status);
   // printr($detailslist);//die;
  //  printr($template);//die;
    //$edit = 1;
  //}
//}else{
  //if(!$obj_general->hasPermission('add',$menuId)){
    //$display_status = false;
  //}
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
        
        <div class="col-lg-10">
          <section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                      <div class="col-lg-6" style="width:100%">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                             
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                          
                           <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Title</label>
                        <div class="col-lg-8">
                             <input type="text" name="title" placeholder="Template Title" value="<?php echo isset($template['title']) ? $template['title'] : '';?>" class="form-control validate[required]">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                         
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_template->getActiveProduct();
                            ?>
                            <select name="product" id="product" class="form-control validate[required]" >
                                <?php
                $sel_product= isset($template['product_name'])?$template['product_name']:'';
                      foreach($products as $product){
                                    if($sel_product && $sel_product == $product['product_id']){
                                        echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                      </div>
                    
                       <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Shipment Country</label>
                        <div class="col-lg-8">
                       
                          <?php /*?><?php
              $selCountry = ''; 
              if(isset($template['country']) && $template['country']){
                $selCountry = $template['country'];
              }
              echo $obj_general->getCountryCombo($selCountry);//214?><?php */?>
                            <?php 
              $countries=$obj_template->getCountry();
              ?>
                            
                            <select name="country_id[]" id="country_id"  multiple="multiple" >
                            
                             <?php 
               $country_val= isset($template['country'])?$template['country']:'';
               $country_id=array();
               $country_id = json_decode($country_val);
              
               foreach($countries as $country)
               {    if(in_array( $country['country_id'],$country_id)){
                      echo '<option value="'.$country['country_id'].'" selected="selected" >'.
                    $country['country_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                    }
               
              }
               ?>
                            </select>
                        </div>
                        
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label" id="countrylabel" style="display:none"> </label>
                        <div style="display:none" id="country_span" name="country_span">
                        
                         </div>
                      </div>
                      
                      
                        <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> For User</label>
                        <div class="col-lg-8">
                          <?php
                $users = $obj_template->getInternational();
              //printr($users);
               //die;
                            ?>
                            <select name="user" id="user" class="form-control validate[required]" >
                               <option value="">Select User</option>
                                <?php
                $sel_user= isset($template['user'])?$template['user']:'';
                      foreach($users as $user){
                                    if($sel_user && $sel_user == $user['international_branch_id']){
                                        echo '<option value="'.$user['international_branch_id'].'" selected="selected" >'.
                    $user['first_name'] . " " .$user['last_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$user['international_branch_id'].'">'.$user['first_name'] . " " .$user['last_name'].'</option>';
                                    }
                                }
                              ?>  
                            </select>
            </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-8">
                          <?php
                $currency = $obj_template->getCurrency();
              // printr($internationalassociates);
               //die;
                            ?>
                            <select name="currency" id="currency" class="form-control validate[required]" >
                               <option value="">Select Currency</option>
                                <?php
                $sel_curr= isset($template['currency'])?$template['currency']:'';
                      foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo '<option value="'.$curr['currency_id'].'" selected="selected" >'.$curr['currency_code'].'</option>';
                                    }else{
                                        echo '<option value="'.$curr['currency_id'].'">'.$curr['currency_code'].'</option>';
                                    }
                                }
                               // foreach($currency as $curr){
                  //printr($curr);
                ?>
                            </select>
            </div>
                      </div>
                           
                   
                      
                      <div class="col-lg-12" id="add-product-div">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                           
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                           <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Size</label>
                                    <div class="col-lg-8 table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                                <th width="20%" class="text-center">Width</th>
                                                <th width="20%" class="text-center">Height</th>
                                              
                             <th width="20%" class="text-center">Gusset</th>
                         
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="width" id="width" value="" class="form-control validate[required,custom[onlyNumberSp]] test"></td>
                                                <td><input type="text" name="height" id="height" value="" class="form-control validate[required,custom[onlyNumberSp]]"></td>
                                             <td><input type="text" name="gusset" id="gusset" class="form-control validate[required,custom[onlyNumberSp]] gusset"></td>    
                                                
                                                 
                                            </tr>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>
                                   <?php
                                  $volumes = $obj_template->getActiveProductVolume();
                                  if($volumes){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Volume</label>
                                        <div class="col-lg-8" id="squantity">
                                           <select name="volume" id="volume" class="form-control">
                                                <?php 
                                                  foreach($volumes as $volume) {
                                                ?>
                                                    <option value="<?php echo $volume['volume']; ?>"><?php echo $volume['volume']; ?></option>
                                                <?php } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                              
                            
              
                      </div>
                      
                     <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                                    <div class="col-lg-8 table-responsive" style="width: 75%;">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                          
                                                  
                                                   
                                                    <th class="text-center"> 200+</th>
                                                    <th class="text-center">500+</th>
                                                     <th class="text-center">1000+</th>
                                                     <th class="text-center">2000+</th>
                      
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                 
                                                <td><input type="text" name="second" id="second" value="" class="form-control validate" ></td>        
                                                <td><input type="text" name="third" id="third" class="form-control validate" ></td>
                                                <td><input type="text" name="fourth" id="fourth" class="form-control validate"></td>
                                                <td><input type="text" name="fifth" id="fifth" class="form-control validate"></td>
                                               
                                            </tr>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>  
                                  
                         
                      
                            
                             <?php
                      $colors = $obj_template->getActiveColor();
            if($colors){
              ?>
                          <div class="form-group option">
                                <label class="col-lg-3 control-label">Color</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
                   $i=0;
                                    foreach($colors as $color){
                    //printr($color);
                    //die;
                                       $spoutsTxt .= '<div style="float:left;width: 150px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
                      if($color['pouch_color_id'] == 1 )
                      {
                                                $spoutsTxt .= '<input type="radio" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" selected="selected" class="colortemp" >';
                      }
                      else 
                      {
                        $spoutsTxt .= '<input type="radio" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" class="colortemp" >';
                      }
                                            $spoutsTxt .= ''.$color['color'].'</label>';
                                        $spoutsTxt .= '</div>';
                    $i++;
                                    }
                                    echo $spoutsTxt;
                                    ?>
                                </div>
                            </div>
                            <?php
              } ?>
                             <div class="file-preview" style="display:none">
                                      <div class="file-preview-thumbnails">
                                    </div>
                                      <div class="clearfix"></div>
                                      <div class="file-preview-status text-center text-success"></div>
                                      <div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                  </div>
                                </div>
                              </div>
                              <div><input type="hidden" name="templateid" id="templateid" value="<?php echo $template_id;?>" /></div>
                             <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="button" id="btn-add-product" class="btn btn-primary">Add Product</button> 
                                    
                                  <button type="button" style="display:none" name="btn-update-product" id="btn-update-product" class="btn btn-primary">Update Product</button>                    </div>
                             </div>
                               <div id="display-product">
                             <?php if(isset($template['digital_template_id']))
                {
               ?>
          <table class="table table-bordered">
              <thead>
                                <tr> 
                                   
                                    <th>Size</th>
                                    <th >Dimension<br />
                                    WxLxG
                                    </th>
                                    <?php 
                    
                     
                        $qty200 = ' Qty200+';
                        $qty500 = ' Qty500+';;
                        $qty1000 = ' Qty1000+';
                        $qty2000 = ' Qty2000+';
                     
                   ?>
                                    
            
                                    <th>Price <?php 
                            $currency = $obj_template->getCurrency();
                   foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
                    echo '<br>'.$qty200;
                   ?> 
                                   
                                    </th>
                                    <th>Price <?php 
                  $currency = $obj_template->getCurrency();
                   foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
                    echo '<br>'.$qty500;
                   ?> 
                                    </th>  
                                    <th >Price <?php 
                  $currency = $obj_template->getCurrency();
                   foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
                    echo '<br>'.$qty1000;
                   ?>
                                    </th>
                                     <th >Price <?php 
                  $currency = $obj_template->getCurrency();
                   foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
                    echo '<br>'.$qty2000;
                   ?>
                                    </th>
                                    <th>
                                    Color
                                    </th>
                                    <th>Action</th>
                                </tr> 
              </thead>
              <tbody>
                            <?php  
              foreach($detailslist as $details){
              
                
            //    printr($details);
                  $qty200 = $details['quantity200'];
                  $qty500 = $details['quantity500'];
                  $qty1000 = $details['quantity1000'];
                  $qty2000 = $details['quantity2000'];
               
            ?>
                                                 
            <tr id='<?php echo $details['digital_template_size_id'];?>'>                           
                            <td><?php echo $details['volume'];?></td>
                            <td><?php echo $details['width'].'X'.$details['height'].'X'.$details['gusset'];?></td>
                         
                            <td><?php echo $qty200; ?></td> 
                            <td><?php echo $qty500; ?></td> 
                            <td><?php echo $qty1000; ?></td> 
                            <td><?php echo $qty2000; ?></td> 
                            <td>      
                            <?php $colorval=json_decode($details['color']); 
                            $color_detail='';
                            ?>
                            <select  name="color_combo" id="color_combo" class="form-control">
                            <?php foreach($colorval as $value)
                            {
                                //printr($value);
                                $color_detail=$obj_template->getColor($value);
                            ?>
                                <option value="<?php echo $color_detail[0]['pouch_color_id'];?>"><?php echo $color_detail[0]['color'];?></option>
                            <?php 
                            }
                            ?>
                            </select></td>
                            <td class="del-product">
                              
                                <a class="btn btn-danger btn-sm" href="javascript:void(0);" 
                                onClick="removeTemplate(<?php echo $details['digital_template_size_id'];?>)"><i class="fa fa-trash-o"></i></a>
                                <a href="javascript:void(0);" 
                                onClick="getTemplate(<?php echo $details['digital_template_size_id'];?>)" class="btn btn-info btn-xs">Edit</a>
                            </td>
                        </tr> 
                        <?php }
                        ?>
                         </tbody>
                        </table>
                             <?php 
                }
                ?><input type="hidden" name="digital_template_size_id" id="digital_template_size_id" 
                                value="" />
                                 </div> 
                            <!-- <div id="display-product"></div>-->
                             <div class="line line-dashed m-t-large"></div>
                             <div class="form-group" id="footer-div" style="display:none">
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'index&status='.$price_status, '',1);?>">Cancel</a>  
                                </div>
                             </div>
                             <?php if(isset($template['digital_template_id']))
               {
                 ?>
                             <div class="form-group" id="update-div" style="display:inline">
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'index&status='.$price_status, '',1);?>">Cancel</a>  
                                </div>
                             </div>
              <?php  }?>
                         </div> 
                    </div>
                </form>
          </section>
        </div>
     </div>
  </section>
</section>
<script>
$("#product").change(function(){
    
      var val = $(this).val();
      var text = $("#product option[value='"+val+"']").text().toLowerCase();
        $(".gusset").show();
        $(".option").show();
        $(".heightb").html("Height");
        $("#btn_generate").attr('name','btn_generate');
      checkGusset();
});
    
function checkGusset(){
  //alert("hui");
  var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkGussets', '',1);?>");
  //alert(gusset_url);
  $.ajax({
    method : 'post',
    url: gusset_url,
    data:'product_id='+$('#product').val(),
    success: function(response) {
      //alert(response);
      if(response==1){
        $('.gusset').show();  
      }else{
        $('.gusset').hide();
      }
    }
  });
}
</script>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/multiple-select.css" />
<script src="<?php echo HTTP_SERVER;?>js/jquery.multiple.select.js"></script>
<script>
    $(function() {
        $('#country_id').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '100%',
        });
    });
</script>
<script>
var count=0;
var product_count = 0;

function removeTemplate(digital_template_size_id){
  //alert('#'+template_size_id);
  var remove_template_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplate', '',1);?>");
  $.ajax({
    url : remove_template_url,
    method : 'post',
    data : {digital_template_size_id : digital_template_size_id},
    success: function(){
      $('#'+digital_template_size_id).hide(); 
    },
    error: function(){
      return false; 
    }
  });
}

function getTemplate(digital_template_size_id){
  //alert('sf');
  var edit_template_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getTemplate', '',1);?>");
  $.ajax({
    url : edit_template_url,
    method : 'post',
    data : {digital_template_size_id : digital_template_size_id},
    success: function(response){
    //  alert(response);
   //  console.log(digital_template_size_id);
      var val = $.parseJSON(response);  

      $('#btn-add-product').hide();
      $('#btn-update-product').show();
       $('#width').val(val.width);
       $('#height').val(val.height);
       $('#gusset').val(val.gusset);
       $("#volume").val(val.volume);
      
       
         $('#second').val(val.quantity200);
         $('#third').val(val.quantity500);
         $("#fourth").val(val.quantity1000);
        
     
       
        $("#digital_template_size_id").val(val.digital_template_size_id);
  
      var color = $.parseJSON(val.color);
    
     
      var colorclass = $(".colortemp");
     

//alert(color);

 for (var i = 0; i < colorclass.length; ++i)
{
   $('#'+colorclass[i].id).prop("checked", false);
}

//alert(color[i]);
 for (var i = 0; i < color.length; ++i) {

for (var k = 0; k < colorclass.length; ++k) {
 // alert(colorclass.length);
    if(colorclass[k].value == color[i])
    {
      $('#'+colorclass[k].id).prop("checked", true);
    }   
}
 }
  },
    error: function(){
      return false; 
    }
  });
}
$("#order-form").submit(function(event){
  
  var country_id = $("#country_id").val();
    if(country_id==null)
    {
      $("#countrylabel").show();
      $("#country_span").show();
      
      $("#country_span").html("<span class='btn btn-danger btn-xs'>Please select Shipment Country</span>");
      event.preventDefault();
  
    }
  
});
$('#btn-add-product').click(function(){
  if($("#order-form").validationEngine('validate')){
    var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addHistory', '',1);?>");
    var country_id = $("#country_id").val();
    if(country_id!=null)
    {
    var str = $("form").serialize();
    
    $.ajax({
      url : add_product_url,
      method : 'post',
           data:{str:str},  
      success: function(response){
        //alert(response);
        var val = $.parseJSON(response);  
           $('#display-product').html(val.response);
         //  alert( $('#display-product'));
           $('#templateid').val(val.result);
           $('#footer-div').show();
      },
      error: function(){
        return false;
      }
    });
    }
    else
    {
      $("#countrylabel").show();
      $("#country_span").show();
      
      $("#country_span").html("<span class='btn btn-danger btn-xs'>Please select Shipment Country</span>");
      
    }
    
  }else{
    return false;
  }
});
function reloadPage(){
  location.reload();
}
$('#btn-update-product').click(function(){
  if($("#order-form").validationEngine('validate')){
    var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateTemplate', '',1);?>");
    //alert(add_product_url);
    var str = $("form").serialize();
    //alert($('.valve').val());
    $.ajax({
      url : add_product_url,
      method : 'post',
           data:{str:str},  
      success: function(response){
        //alert(response);
       set_alert_message('Successfully Updated',"alert-success","fa-check");
        //$("form").serialize();
       reloadPage();

      },
      error: function(){
        set_alert_message('Error!',"alert-warning","fa-warning"); 
      }
    });
  }else{
    return false;
  }
});
</script> 
<script language="JavaScript">

  
//function checkall(checkEm) {
//  alert('hii');
//    var cbs = document.getElementsByName('color');
//    for (var i = 0; i < cbs.length; i++) {
//        if (cbs[i].type == 'checkbox') {
//            if (cbs[i].name == 'color[]') {
//                cbs[i].checked = checkEm;
//            }
//        }
//    }
//}
//}
//function checkall(color) {
//  alert("hi");
//  
//  checkboxes = document.form.getElementsByName('color');
//  for (var checkbox in checkboxes)
//    checkbox.checked = color.checked;
//}
function checkall(formname, checktoggle)
{
  
  
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
      }
}
function uncheckall(formname, checktoggle)
{
  
  
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = '';
          }
      }
}

</script>

<!-- Close : validation script -->
<?php } else { 
    include(DIR_ADMIN.'access_denied.php');
  }
?>