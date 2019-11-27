<?php
include("mode_setting.php");


if(isset($_GET['user_type']) && $_GET['user_type'] && isset($_GET['user_id']) && $_GET['user_id']){
    $user_type_id = decode($_GET['user_type']);
    $user_id = decode($_GET['user_id']);
    $queryString = '&user_type='.$_GET['user_type'].'&user_id='.$_GET['user_id'];
}else{
    $user_type_id = $obj_session->data['LOGIN_USER_TYPE'];
    $user_id = $obj_session->data['ADMIN_LOGIN_SWISS'];
    $queryString = '';
}

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
    'text' 	=> 'Dashboard',
    'href' 	=> $obj_general->link('dashboard','','',1),
    'icon' 	=> 'fa-home',
    'class'	=> '',
);

$bradcums[] = array(
    'text' 	=> 'Leave Details List',
    'href' 	=>$obj_general->link($rout,'', '',1),
    'icon' 	=> 'fa-list',
    'class'	=> '',
);

$bradcums[] = array(
    'text' 	=> 'Leave Application',
    'href' 	=>'',
    'icon' 	=> 'fa-edit',
    'class'	=> 'active',
);
//Close : bradcums



if($display_status){
    //insert
    if(isset($_POST['btn_save'])){
        $post = post($_POST);
        //printr($post);die;
        $insert_id = $obj_leave->Add_Leave($post);
        //die;
        $obj_session->data['success'] = ADD;
       page_redirect($obj_general->link($rout,'', '',1));
    }

?>
    <section id="content">
        <section class="main padder">
            <div class="clearfix">
                <h4><i class="fa fa-edit"></i>  Leave Application</h4>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php include("common/breadcrumb.php");?>
                </div>
                <div class="col-sm-8">
                    <section class="panel">
                        <header class="panel-heading bg-white"> Leave Application</header>
                        <div class="panel-body">
                            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                              


                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Leave Type</label>
                                        <div class="col-lg-4">
                                        
                                            <select name="leave_type_name" id="Leave_type" class="form-control validate">
                                                <option selected="selected">Select Leave Type</option>
                                                <?php
                                                $leaves = $obj_leave->getleave();
                                                foreach($leaves as $leave){?>

                                              <option value="<?php echo $leave['leave_type_name']?>"> <?php echo $leave['leave_type_name'] ?></option>


                                               <?php  } ?>
                                            </select>
                                        </div>
                                    </div>


                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Leave Title</label>
                                    <div class="col-lg-4">
                                        <input type="text" name="leave_title" value="" class="form-control validate[required]">
                                    </div>
                                </div>

                              <div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
								<div class="col-lg-3">
									<input type="text" name="f_date"  value="" placeholder="From Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="TextBox1"/>
									</div>
							  </div>
							  
							  <div class="form-group">
								<label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
								<div class="col-lg-3">
								  <input type="text" name="t_date"  value="" placeholder="To Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="TextBox2" />
								</div>
							  </div>

                       <input type="hidden" name="ending_date" readonly data-date-format="yyyy-mm-dd"  value="" placeholder="Date" id="TextBox3" class="day"/ >

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Massege</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control validate[required]" style="width:550px;height:200px;" id="message" name="messag"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">



                                            <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary" >send </button>

                                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
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
	jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#TextBox1').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			
				var newDate = new Date(ev.date);
				//alert(newDate);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		checkin.hide();
    		$('#TextBox2')[0].focus();
    	}).data('datepicker');
    	var checkout = $('#TextBox2').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
	});

	</script>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
				
			jQuery("#form").validationEngine();
			
			});
		
	</script>
	
    <script>
	 //jQuery(document).ready(function(){
			//CKEDITOR.replace( 'messag' );
		
   // });
		</script>
		<script>
		
        $(document).ready(function() {
		
            $("#TextBox1").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function (e) {
                $(this).datepicker('hide');
            });
            $("#TextBox2").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function (e) {
                $(this).datepicker('hide');
            });
        });
            $('#btn_save').click(function(){

            var oneDay = 24*60*60*1000;
            var fdate=$('#TextBox1').val();
            var sdate=$('#TextBox2').val();
            //alert($firstdate);
            //alert($seconddate);
            var firstDate = new Date(fdate);
            var secondDate = new Date(sdate);
			
			var start = new Date(firstDate);
			var finish = new Date(secondDate);
			var dayMilliseconds = 1000 * 60 * 60 * 24;
			var weekendDays = 0;
			while (start <= finish) {
			  var day = start.getDay()
			  if (day == 0) {
				weekendDays++;
			  }
			  start = new Date(+start + dayMilliseconds);
			}
			//alert(weekendDays);
			
			 var totaldaysss=Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
            var diffDays = (Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)))-weekendDays)+1;
			//alert(weekendDays);
            //alert(totaldaysss);
            document.getElementById("TextBox3").value = diffDays;
			
			
			
        })
 
			
			
		
	
    </script>
	
          



    <!-- Close : validation script -->

    <?php
}
else
{
    include(DIR_ADMIN.'access_denied.php');

}
?>


