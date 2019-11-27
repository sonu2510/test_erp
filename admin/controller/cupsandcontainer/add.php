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
$edit=false;
if(isset($_GET['cup_id']) && !empty($_GET['cup_id']))
{
  $cup_id=decode($_GET['cup_id']);
  $cup = $obj_cupsandcontainer->getcup($cup_id);
  $edit = true;
  
}


//Close : edit

if($display_status){
  //insert user
  if(isset($_POST['btn_save'])){
    $post = post($_POST);   
    //printr($post);die;
    $insert_id = $obj_cupsandcontainer->addcup($post);
    $obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, '', '',1));
  }
  
  //edit
  if(isset($_POST['btn_update']) && $edit){
    $post = post($_POST);
    //
  //printr($post);die;
    $cup_id=decode($_GET['cup_id']);
    $obj_cupsandcontainer->updatecup($cup_id,$post);
    $obj_session->data['success'] = UPDATE;
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
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                 <label class="col-lg-3 control-label"><span class="required">*</span>Product</label>
                <div class="col-lg-8">
                  <select class="form-control" name="product" id="product"class="form-control validate[required]" >
                    <option value="">Select product</option>
                  <?php $product = $obj_cupsandcontainer->getproduct();
                        foreach($product as $pro)
                        {?>
                            <option value="<?php echo $pro['product_id'];?>" <?php if(isset($cup['product_id']) && $pro['product_id']==$cup['product_id']){ echo 'selected=selected';}?>><?php echo $pro['product_name'];?></option>
                        <?php } ?>
                        </select>
                </div>
              </div>
              
              <div class="form-group">
                 <label class="col-lg-3 control-label"><span class="required">*</span>Product Name</label>
                <div class="col-lg-8">
                  <input type="text" name="cup_name" value="<?php echo isset($cup['cup_name'])?$cup['cup_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Rmc Price </label>
                <div class="col-lg-4">
                    <input type="text" name="basic_price" id="basic_price" value="<?php echo isset($cup['basic_price'])?$cup['basic_price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Wastage </label>
                <div class="col-lg-4">
                    <input type="text" name="wastage" id="wastage" value="<?php echo isset($cup['wastage'])?$cup['wastage']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              
              
              <?php if($edit) {
                        $size = $obj_cupsandcontainer->getsize($cup['product_id']); ?>
                        <div class="form-group" id='volume_div1'>
                            <label class="col-lg-3 control-label"><span class="required">*</span>Select Volume</label>
                                <div class="col-lg-4">
	                             <select class="form-control" name="select_volume" id="select_volume"class="form-control validate[required]" >
                                    <option value="">Select Volume</option>
                                        <?php foreach($size as $s)
                                        {?>
                                            <option value="<?php echo $s['volume'];?>" <?php if(isset($cup['select_volume']) && $s['volume']==$cup['select_volume']){ echo 'selected=selected';}?> ><?php echo $s['volume'];?></option>
                                        <?php }?>
                                </select>
	
	                        </div>
	                   </div>
              <?php }
              else {?>  
                    <div class="form-group" id='volume_div'>
                
                    </div>
              <?php } ?>
              

              <div class="form-group">
                 <label class="col-lg-3 control-label"><span class="required">*</span>Transport Price</label>
                <div class="col-lg-4">
                  <input type="text" name="transport_price" value="<?php echo isset($cup['transport_price'])?$cup['transport_price']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Packing Price </label>
                <div class="col-lg-4">
                    <input type="text" name="packing_price" value="<?php echo isset($cup['packing_price'])?$cup['packing_price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
        <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Weight In Gm</label>
                <div class="col-lg-4">
                    <input type="text" name="weight" value="<?php echo isset($cup['weight'])?$cup['weight']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>


                <?php $gress_quantity = $obj_cupsandcontainer->getQuantity();?>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Sea Profit Price (Rich)</label>
                <div class="col-lg-8">
                
                       <div >
                          <?php //printr($gress_quantity);
                          if(isset($_GET['cup_id']) && !empty($_GET['cup_id']))
                          {
                            $qty_per = $obj_cupsandcontainer->getprofit($cup_id,'rich','sea');
                          }
                         foreach($gress_quantity as $key=>$qty){
                              $val ='0';
                            if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                              $val = $qty_per[$key]['profit'];
                            echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[sea][rich]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                          }?>                             
                    </div>



                </div>
              </div>
               
               
               <div class="form-group">
                <label class="col-lg-3 control-label">Sea Profit Price (Poor)</label>
                <div class="col-lg-8">
                
                  <div >
                      <?php if(isset($_GET['cup_id']) && !empty($_GET['cup_id']))
                             {
                         $qty_per = $obj_cupsandcontainer->getprofit($cup_id,'poor','sea');
                       }
                        foreach($gress_quantity as $key=>$qty){
                          $val ='0';

                          if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                            $val = $qty_per[$key]['profit'];
                          echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[sea][poor]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                        }?>                             
                    </div>

                
              </div>
               
                <div class="form-group">
                <label class="col-lg-3 control-label">Air Profit Price (Rich)</label>
                <div class="col-lg-8">
                
                       <div >
                          <?php 
                          if(isset($_GET['cup_id']) && !empty($_GET['cup_id']))
                          {
                           $qty_per = $obj_cupsandcontainer->getprofit($cup_id,'rich','air');
                          }
                         foreach($gress_quantity as $key=>$qty){
                              $val ='0';
                            if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                              $val = $qty_per[$key]['profit'];
                            echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[air][rich]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                          }?>                             
                    </div>



                </div>
              </div>
               
               
               <div class="form-group">
                <label class="col-lg-3 control-label">Air Profit Price (Poor)</label>
                <div class="col-lg-8">
                
                  <div >
                      <?php if(isset($_GET['cup_id']) && !empty($_GET['cup_id']))
                             {
                         $qty_per = $obj_cupsandcontainer->getprofit($cup_id,'poor','air');
                       }
                        foreach($gress_quantity as $key=>$qty){
                          $val ='0';

                          if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                            $val = $qty_per[$key]['profit'];
                          echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[air][poor]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                        }?>                             
                    </div>

                
              </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Status</label>
                    <div class="col-lg-4">
                      <select class="form-control" name="status">
                            <?php if($cup['status']==1){ ?>
                                <option value="1" selected="selected">Active</option>
                                <option value="0" >Inactive</option>
                                <?php
                            }else { ?>
                                <option value="1" selected="selected">Active</option>
                                <option value="0" >Inactive</option>
                            <?php } ?>
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
  
    $('#product').change(function(){
        var id = $(this).val();
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfun=getsize','',1);?>");
	    $.ajax({
    		 url : url,
    		 type : 'post',
    		 data : {id:id},
    		 success : function(responce){
    			<?php if($edit) {?>
    			    $("#volume_div1").html(responce);
    			<?php } else { ?>
    			    $("#volume_div").html(responce);
    			    <?php } ?>
    			 
    		 },
    		 
	 });
    });
//Close : wastage
</script> 
<!-- Close : validation script -->

<?php } else { 
    include(SERVER_ADMIN_PATH.'access_denied.php');
  }
?>