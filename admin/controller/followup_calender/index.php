
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
$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$edit = '';
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$filter_data=array();
$filter_value='';

$class = 'collapse';

if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		//$insert_id = $obj_industry->insert_data($post);
		
		$obj_session->data['success'] = ADD;
		//page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update']) ){
		$post = post($_POST);
//	print_r($post);die;
//$obj_industry->updateFollowup($post);
		$obj_session->data['success'] = UPDATE;
		//page_redirect($obj_general->link($rout, '', '',1));
	}
	


if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_industry->updateStatus($status,$_POST['post']);
		//$obj_session->data['success'] = UPDATE;
	//	//page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
//$obj_industry->updateStatus(2,$_POST['post']);
		//$obj_session->data['success'] = UPDATE;
//page_redirect($obj_general->link($rout, '', '',1));
	}
}

	
?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/calendar_css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/calendar_css/style.css">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/calendar_css/calendar.css">
<script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.min.js"></script>
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/tether.min.js"></script>
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/bootstrap.min.js"></script>

 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.nicescroll.js"></script>
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery-ui.min.js"></script>
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/moment.js"></script>
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/cal.js"></script>
  <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.app.js"></script>
   <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.core.js"></script>
   
<section id="content">
  <section class="main padder">
    <div class="clearfix" style="margin-left:150px;">
      <h4><i class="fa fa-list"></i> <?php echo "Calendar"?>
	  
    </div>
	
    <div class="row" style="margin-left:150px;">
		  <table style="align:left;top:13px;right:13px;;font-size:15px;color:#545454"><tbody><tr><td class="legendColorBox"><div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(241,181,61);overflow:hidden"></div></div></td><td class="legendLabel">&nbsp;&nbsp;&nbsp; Followup</td></tr><tr><td class="legendColorBox"><div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(100,176,242);overflow:hidden"></div></div></td><td class="legendLabel">&nbsp;&nbsp;&nbsp; Enquiry</td></tr></tbody></table>

    	<div class="col-lg-12">
	      
	
		   </div>   
        
                        <div class="card-box"  >
                            <div class="row">

                                <div class="col-md-12">
                                    <div id="calendar"></div>
                                </div> <!-- end col -->
                            </div>  <!-- end row -->
                        </div>
	
                        <form action="" method="post">
                        <!-- BEGIN MODAL -->
                        <div class="modal fade none-border" id="event-modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: #e2e2e2;>
                                        <h5 class="modal-title"><strong>Reminder Followup</strong></h5>
										
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body p-20"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal" style="font-size: 14px;">Close</button>
                                        <button type="submit" name="btn_save" class="btn btn-success save-event waves-effect waves-light" style="font-size: 14px;">Create event</button>
                                       <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal" style="font-size: 14px;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Add Category -->
                        <div class="modal fade none-border" id="add-category">
                            <div class="modal-dialog">
                                <div class="modal-content">
                           <!--         <div class="modal-header">
                                        <h5 class="modal-title">Add a category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body p-20">
                                        <form role="form">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Category Name</label>
                                                    <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="control-label">Choose Category Color</label>
                                                    <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                        <option value="success">Success</option>
                                                        <option value="danger">Danger</option>
                                                        <option value="info">Info</option>
                                                        <option value="pink">Pink</option>
                                                        <option value="primary">Primary</option>
                                                        <option value="warning">Warning</option>
                                                        <option value="inverse">Inverse</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>-->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" name="btn_update" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal"  >Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <!-- END MODAL -->
                  

            </div> <!-- container -->



        </div> <!-- End wrapper -->



 <script>
   
 </script>
    
    <script type="text/javascript">




        !function($) {
            "use strict";

            var CalendarApp = function() {
                this.$body = $("body")
                this.$modal = $('#event-modal'),
                    this.$event = ('#external-events div.external-event'),
                    this.$calendar = $('#calendar'),
                    this.$saveCategoryBtn = $('.save-category'),
                    this.$categoryForm = $('#add-category form'),
                    this.$extEvents = $('#external-events'),
                    this.$calendarObj = null
            };


            /* on drop */
            CalendarApp.prototype.onDrop = function (eventObj, date) {
                var $this = this;
                // retrieve the dropped element's stored Event Object
                var originalEventObject = eventObj.data('eventObject');
                var $categoryClass = eventObj.attr('data-class');
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                // assign it the date that was reported
                copiedEventObject.start = date;
                if ($categoryClass)
                    copiedEventObject['className'] = [$categoryClass];
                // render the event on the calendar
                $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    eventObj.remove();
                }
            },
                /* on click on event */
                CalendarApp.prototype.onEventClick =  function (calEvent, jsEvent, view) {
                    var $this = this;
					var oldtitle=calEvent.title;
                    var getid=calEvent.id;
                    var addeddate=calEvent.start;
                    var monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
                    var nowget=new Date(addeddate);

                   var fndt='  '+nowget.getDate() + '  ' + monthNames[(nowget.getMonth())] + '  ' + nowget.getFullYear();

					//alert(oldtitle);
                 //   alert(addeddate);
					
                    var form = $("<form method='post' ></form>");
					form.append("<label >Posted By : </label>");
	                form.append("<label><span class='btn btn-inverse waves-effect waves-light ' style='font-size: 12px;'>" + calEvent.name+"<span></label>");
                    form.append("<br><br>");
                    form.append("<label>Date : </label>");
                    form.append("<label><span>"+ fndt+"<span></label>");
                    form.append("<br><br>");
					
                    form.append("<label>Change event name</label>");
                    form.append("<div class='input-group '  ><textarea rows='4' cols='50'  required  name='title' id ='note' >"+calEvent.title+"</textarea><span class='input-group-btn'><button type='submit' name='btn_update' class='btn btn-success waves-effect waves-light' style='font-size: 12px;'><i class='fa fa-check'></i> Save</button></span></div>");


                    $this.$modal.modal({
                        backdrop: 'static'
                    });
                    $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.delete-event').unbind('click').click(function () {
                        $this.$calendarObj.fullCalendar('removeEvents', function (ev) {
                            return (ev._id == calEvent._id);
							
                        });
						var deletetitleid=calEvent.id;
						//alert(rmtitle);
						var delete_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=delete_data', '',1);?>");
							 

						$.ajax({
							  url: delete_url,
							  method : 'post',
							  data: {id:deletetitleid},
							  success: function(json) {
								location.reload();
								}
							});

							$this.$modal.modal('hide');
                    });
                    $this.$modal.find('form').on('submit', function () {
                        calEvent.title = $('textarea#note').val();
                        $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                        $this.$modal.modal('hide');

						var newtitle=calEvent.title;
						//alert(newtitle);
						//alert(oldtitle);
						var update_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_data', '',1);?>");
						$.ajax({
							  url: update_url,
							  method : 'post',
							  data: {newt:newtitle,id:getid},
							  success: function(json) {
								console.log(oldtitle);
								console.log(newtitle);
									location.reload();
							  }
							});

																

                        return false;
					
                    });
					
                },
                /* on select */
                CalendarApp.prototype.onSelect = function (start, end, allDay) {
                    var $this = this;
					var insdate=new Date(start);
                    var monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
					var fndt='  ' +insdate.getDate() + '  ' + monthNames[(insdate.getMonth())] + '  ' + insdate.getFullYear();
                    $this.$modal.modal({
                        backdrop: 'static'
                    });
                    var form = $("<form method='post'></form>");
					form.append("<label>Date: </label>");
	                form.append("<label><span>'"+ fndt+"'<span></label>");
                    form.append("<br><br>");
					
                    form.append("<label>Time: </label>");
                    form.append("<input class='form-control ' style='height: 26px;font-size: 12px;width: 25%;' name='cal_time' type='time' name='' id ='gotime'/>");
                    form.append("<br>");
                    form.append("<div class='row'></div>");
                    form.find(".row")
                        .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Event Name</label><textarea rows='4' cols='50' placeholder='Insert Event Name' required  name='title' id ='note'></textarea></div></div>")
                        //.append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>")
                        		
						.find("select[name='category']")
                        .append("<option value='bg-danger'>Danger</option>")
                        .append("<option value='bg-success'>Success</option>")
                        .append("<option value='bg-purple'>Purple</option>")
                        .append("<option value='bg-primary'>Primary</option>")
                        .append("<option value='bg-pink'>Pink</option>")
                        .append("<option value='bg-info'>Info</option>")
                        .append("<option value='bg-warning'>Warning</option></div></div>");
                    $this.$modal.find('.delete-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
                        form.submit();
				   // var getdateg=document.getElementById("gotime").value;
                        //alert(getdateg);
                    });
					

					
				    $this.$modal.find('form').on('submit', function () {
                        var title=$('textarea#note').val();
                       // var title = form.find("input[name='title']").val();
                        //alert(title);

                        var beginning = form.find("input[name='beginning']").val();
                        var ending = form.find("input[name='ending']").val();
                        var categoryClass = form.find("select[name='category'] option:checked").val();
                        if (title !== null && title.length != 0) {
                            $this.$calendarObj.fullCalendar('renderEvent', {
                                title: title,
                                start:start,
                                end: end,
                                allDay: false,
                                className: categoryClass
                            }, true);
							
							
                            $this.$modal.modal('hide');
							 $.post(".save-event",
								{
								name: "Donald Duck",
								city: "Duckburg"
								},
								function(data,status){
									alert("Data: " + data + "\nStatus: " + status);
									});

							var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=insert_data', '',1);?>");
							var date = new Date(start);
							var a=date.getMonth() + 1;
							var b=date.getDate();
							var c=date.getFullYear();
                            //var getmnthname=monthNames[a];
                            //alert(getmnthname);
							var mn=String("0" + a).slice(-2);

							var dt=String("0" + b).slice(-2);
							var finaldate=c + '-' + mn + '-' +dt;
							//alert(finaldate);
							//var a=crrdate.getMonth() + 1 '-'crrdate.getDate() '-'crrdate.getFullYear();
							//var b=crrdate.getDate();
							//var c=crrdate.getFullYear();
							//alert(crrdate);
							//var mn=String("0" + a).slice(-2);

							//var dt=String("0" + b).slice(-2);
							//var finaldate=c + '-' + mn + '-' +dt;							
                            //var gettime=$('#gotime').value();
                           // alert(gettime);
                            var getdateg = form.find("input[name='cal_time']").val();
                            //alert(getdateg);
							$.ajax({
							  url: status_url,
							  method : 'post',
							  data: {title:title,date:finaldate,time:getdateg},
							  success: function(json) {
								
							  }
							});

							
                        }
                        else{
                            alert('You have to give a title to your event');
                        }
                        return false;

                    });
					
				
                    $this.$calendarObj.fullCalendar('unselect');
                },
			
               /* CalendarApp.prototype.enableDrag = function() {
                    //init events
                    $(this.$event).each(function () {
                        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                        // it doesn't need to have a start or end
                        var eventObject = {
                            title: $.trim($(this).text()) // use the element's text as the event title
                        };
                        // store the Event Object in the DOM element so we can get to it later
                        $(this).data('eventObject', eventObject);
                        // make the event draggable using jQuery UI
                        $(this).draggable({
                            zIndex: 111,
                            revert: false,      // will cause the event to go back to its
                            revertDuration: 0  //  original position after the drag
                        });
                    });
                }*/
            /* Initializing */
            CalendarApp.prototype.init = function() {
               // this.enableDrag();
                /*  Initialize the calendar  */
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                var form = '';
				var defaultEvents="";
												
                var today = new Date($.now());

				var defaultEvents  =  [			
			<?php $getdata=$obj_industry->view_Event_detail();
			if($getdata){
				for ($i=0; $i <count($getdata); $i++) {
					if($getdata[$i]['enquiry_id']==0){
						
				?>
						
						{
					
						title:' <?php echo str_replace(array("\r\n","\r","\n"),'<br>',$getdata[$i]['enquiry_note']); ?>',
						start: '<?php echo $getdata[$i]['followup_date']; ?>',
                        id: '<?php echo $getdata[$i]['enquiry_followup_id']; ?>',
						name: '<?php echo $getdata[$i]['user_name']; ?>',
						className: 'bg-danger1'

						},
						
				<?php 
				
				}elseif(!empty($getdata[$i]['enquiry_note'])){ ?>


						{
						title: '<?php echo str_replace(array("\r\n","\r","\n"),'<br>',$getdata[$i]['enquiry_note']); ?>',
						start: '<?php echo $getdata[$i]['followup_date']; ?>',
                        id: '<?php echo $getdata[$i]['enquiry_followup_id']; ?>',
						name: '<?php echo $getdata[$i]['user_name']; ?>',
						className: 'bg-purple1'

						},



			<?php	} }  }?>
						];
					

             
                var $this = this;
                $this.$calendarObj = $this.$calendar.fullCalendar({
                    slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
                    minTime: '08:00:00',
                    maxTime: '19:00:00',
                    defaultView: 'month',
                    handleWindowResize: true,
                    height: $(window).height() - 200,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    events: defaultEvents,
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    eventLimit: true, // allow "more" link when too many events
                    selectable: true,
                    drop: function(date) { $this.onDrop($(this), date); },
                    select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
                    eventClick: function(calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); }

                });

                //on new event
                this.$saveCategoryBtn.on('click', function(){
                    var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
                    var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
                    if (categoryName !== null && categoryName.length != 0) {
                        $this.$extEvents.append('<div class="external-event bg-' + categoryColor + '" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-move"></i>' + categoryName + '</div>')
                      //  $this.enableDrag();
                    }
                });
            },

                //init CalendarApp
                $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

        }(window.jQuery),

//initializing CalendarApp
            function($) {
                "use strict";
                $.CalendarApp.init()
            }(window.jQuery);

        var dates =new Date("2017/06/29").getTime();


    </script>
		
		
		
		
      

                   
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
<style>
.bg-danger1 {
    background-color:#f1b53d !important;
}
.bg-purple1 {
    background-color:#61a1e1 !important;
}
</style>