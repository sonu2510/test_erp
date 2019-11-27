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
if(isset($_GET['oxy_silica_id']) && !empty($_GET['oxy_silica_id']))
{
  $oxy_silica_id=decode($_GET['oxy_silica_id']);
  $oxy_silica = $obj_oxy_silica_detail->getoxy_silica($oxy_silica_id);
  //printr($oxy_silica);
  $edit = true;
  
}


//Close : edit

if($display_status){
  //insert user
  if(isset($_POST['btn_save'])){
    $post = post($_POST);   
    //printr($post);die;
    $insert_id = $obj_oxy_silica_detail->addoxy_silica($post);
    $obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, '', '',1));
  }
  
  //edit
  if(isset($_POST['btn_update']) && $edit){
    $post = post($_POST);
    //
  //printr($post);die;
    $oxy_silica_id=decode($_GET['oxy_silica_id']);
    $obj_oxy_silica_detail->updateoxy_silica($oxy_silica_id,$post);
    $obj_session->data['success'] = UPDATE;
    page_redirect($obj_general->link($rout, '', '',1));
  }//printr($oxy_silica['select_volume']);
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
                 <label class="col-lg-3 control-label"><span class="required">*</span>Product Name</label>
                <div class="col-lg-8">
                  <input type="text" name="oxy_silica_name" value="<?php echo isset($oxy_silica['prodct_name'])?$oxy_silica['prodct_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Rmc Price </label>
                <div class="col-lg-4">
                    <input type="text" name="basic_price" id="basic_price" value="<?php echo isset($oxy_silica['basic_price'])?$oxy_silica['basic_price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Wastage </label>
                <div class="col-lg-4">
                    <input type="text" name="wastage" id="wastage" value="<?php echo isset($oxy_silica['wastage'])?$oxy_silica['wastage']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Select Volume</label>
                <div class="col-lg-4">
                <select class="form-control" name="select_volume" class="form-control validate[required]" >
                        <option value="">Select Volume</option>
                        <?php $size = $obj_oxy_silica_detail->getSize();
                foreach($size as $s){ //printr($s);?>
                  <option value="<?php echo $s['volume'];?>" <?php if(isset($oxy_silica['select_volume']) && $oxy_silica['select_volume']==$s['volume']){ echo 'selected=selected';}?>><?php echo $s['volume'];?></option>
             <?php  } ?>
            
                </select>
         </div>
              </div>
              

              <div class="form-group">
                 <label class="col-lg-3 control-label"><span class="required">*</span>Transport Price</label>
                <div class="col-lg-4">
                  <input type="text" name="transport_price" value="<?php echo isset($oxy_silica['transport_price'])?$oxy_silica['transport_price']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Packing Price </label>
                <div class="col-lg-4">
                    <input type="text" name="packing_price" value="<?php echo isset($oxy_silica['packing_price'])?$oxy_silica['packing_price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
        <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Weight</label>
                <div class="col-lg-4">
                    <input type="text" name="weight" value="<?php echo isset($oxy_silica['weight'])?$oxy_silica['weight']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
        
              

                <?php $gress_quantity = $obj_oxy_silica_detail->getQuantity();?>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Profit Price (Rich)</label>
                <div class="col-lg-8">
                
                       <div >
                          <?php 
                          if(isset($_GET['oxy_silica_id']) && !empty($_GET['oxy_silica_id']))
                          {
                           $qty_per = $obj_oxy_silica_detail->getprofit($oxy_silica_id,'rich');
                          }
                         foreach($gress_quantity as $key=>$qty){
                              $val ='0';
                            if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                              $val = $qty_per[$key]['profit'];
                            echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[rich]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                          }?>                             
                    </div>



                </div>
              </div>
               
               
               <div class="form-group">
                <label class="col-lg-3 control-label">Profit Price (Poor)</label>
                <div class="col-lg-8">
                
                  <div >
                      <?php if(isset($_GET['oxy_silica_id']) && !empty($_GET['oxy_silica_id']))
                             {
                         $qty_per = $obj_oxy_silica_detail->getprofit($oxy_silica_id,'poor');
                       }
                        foreach($gress_quantity as $key=>$qty){
                          $val ='0';

                          if(isset($qty_per) && $qty['quantity'] == $qty_per[$key]['qty'])
                            $val = $qty_per[$key]['profit'];
                          echo  '<label style="padding-right: 40px; ">'.$qty['quantity'].'<input type="text" name="profit_price[poor]['.$qty['quantity'].']" value="'.$val.'" class="form-control validate[required]" style="width: 56px;color:black" ></label>';
                        }?>                             
                    </div>

               
              </div>
               
 
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Status</label>
                    <div class="col-lg-4">
                      <select class="form-control" name="status">
                            <?php if($oxy_silica['status']==1){ ?>
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
</script> 
<!-- Close : validation script -->

<?php } else { 
    include(SERVER_ADMIN_PATH.'access_denied.php');
  }
?>