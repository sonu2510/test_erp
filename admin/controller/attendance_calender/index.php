
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





if($display_status) {

//active inactive delete


	
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
 <script src="<?php echo HTTP_SERVER;?>js/calendar_js/calatt.js"></script>
  <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.app.js"></script>
   <script src="<?php echo HTTP_SERVER;?>js/calendar_js/jquery.core.js"></script>
   
<section id="content">
  <section class="main padder">
    <div class="clearfix" style="margin-left:150px;">
      <h4><i class="fa fa-list"></i> <?php echo "Calendar"?>
	  
    </div>
	
    <div class="row" style="margin-left:150px;">
		

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

                   var fndt=''+nowget.getDate()+'-'+(nowget.getMonth()+1)+'-'+ nowget.getFullYear();
				   var fndate=''+nowget.getFullYear()+'-'+(nowget.getMonth()+1)+'-'+ nowget.getDate();

					var url = '<?php echo HTTP_SERVER.'admin/index.php?route=attendance_calender&mod=add';?>&date='+fndate;
					
					window.open(url, '');
					//window.location = "<?php echo $obj_general->link($rout,'&mod=add', '',1);?>" + fndt;
					//alert(fndt);
                },
                /* on select */
                CalendarApp.prototype.onSelect = function (start, end, allDay) {
				   var $this = this;
					var insdate=new Date(start);
                    var monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
					var fndt='' +insdate.getFullYear()+'-'+(insdate.getMonth()+1) +'-'+insdate.getDate();			
                	//alert(fndt);
      
						
					var url = '<?php echo HTTP_SERVER.'admin/index.php?route=attendance_calender&mod=add';?>&date='+fndt;
					//alert(url);
					window.open(url, '');
					
				//	window.location = "<?php echo $obj_general->link($rout, 'mod=add&date', '',1);?>" + fndt;
					//alert(fndt);

                },
			
            
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
			<?php $getdata=$obj_attendance->staff_group_detail();
			//printr($getdata);die;
			if($getdata){
				for ($i=0; $i <count($getdata); $i++) {
					
						
				?>
						
						{
					
						title:' <?php echo $getdata[$i]['staff_group_name']; ?>',
						start: '<?php echo json_encode($getdata[$i]['attendance_date']); ?>',
                        id: '<?php echo json_encode($getdata[$i]['group_id']); ?>',
						name: '',<?php if($getdata[$i]['group_id']==1){ ?>
						className: 'bg-inverse'
						<?php }elseif($getdata[$i]['group_id']==2){?>
						className: 'bg-danger1'
						<?php } ?>
						},
						
				<?php 
				
			} } ?>
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