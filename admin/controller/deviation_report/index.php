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

	
	
 $details = $obj_deviation_report->deviationdetail();
//printr($details);
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
		  			<span><?php echo $display_name;?> Listing</span>
                    </header>  
                   
                       <div class="panel-body">
           
          </div>
          <form name="form_list" id="form_list" method="post" class="">
           <input type="hidden" id="action" name="action" value="" />
            <div class="table-responsive">
		               <table class="table b-t text-small table-hover">
                          <thead>
                            <tr>
                                 <th>Invoice No</th>
                                 <th>Custom Duty Charge</th>
                                
                                 <th> Clarification / Explanation</th>
                                 <th></th>
                            </tr>
                          </thead>
                          <tbody>
                          	
							   <?php
							   
                            if(!empty($details))
                            { 
                              foreach($details as $detail)
                              {
                              ?>
                              
                                     <tr>
                                     <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_id='.encode($detail['invoice_id']),'',1);?>"><?php echo $detail['invoice_no'];?></a></td> 
                                     <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_id='.encode($detail['invoice_id']),'',1);?>"><?php echo $detail['custom_duty_deviation_per'];?></a></td>
                                         <?php
                                          if($detail['close_status']=='0')
                                          {?>
                                              <td> 
                                                     
                                                  <textarea class="form-control validate[required]"  name="clarification" id="clarification" value="" rows="2" cols="45" onchange="getdetail(<?php echo $detail['invoice_id'] ;?>)" ><?php echo $detail['need_clarification'] ; ?></textarea>
                                           </td>
                                           <td><button type="button" id="btn_close" name="btn_close" class="btn  bg-danger" onclick="changestatus(<?php echo $detail['invoice_id'] ;?>)">close </button></td>
                                         <?php }else{?>
                                            
                                                 <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_id='.encode($detail['invoice_id']),'',1);?>"><?php echo $detail['need_clarification'] ;?> </a></td>
                                                 <td><a class=" bg-primary btn-sm">Is Closed</a> <td>
                                                                          
                                           
                                           <?php }?>
                                       
                             
                  		 </tr>
                    <?php
					 }
					} else{
						echo "<tr><td colspan='4'>no record found</td> </tr>";
						
					}?>
                   
                     </tbody>
                	  </table>
                  </div>
           </form>
                 
        </div>
        </section> 
        </section>
 <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">
function getdetail(invoice_id)
{

	var clarification=$("#clarification").val();
	 var detail_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateclarification', '',1);?>");
		 $.ajax({
				url : detail_url ,
				method : 'post',
				data : {clarification: clarification, invoice_id:invoice_id},
				success: function(response){
					//console.log(response);
					
					set_alert_message('Successfully Inserted',"alert-success","fa-check");
					//window.setTimeout(function(){location.reload()},500);
				
				},
				error: function(){
					return false;	
				}
			});
}
function changestatus(invoice_id)
{ 
  var change_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=change', '',1);?>");
	 $.ajax({
			url : change_url ,
			method : 'post',
			data : {invoice_id:invoice_id},
			success: function(response){
				set_alert_message('Successfully Inserted',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},500);
			
			},
			error: function(){
				return false;	
			}
		});
}
	 
	 

    
    </script>         