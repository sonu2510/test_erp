<?php
$alert_success = '';
$alert_warning = '';
if(isset($obj_session->data['success']) && $obj_session->data['success'] != ''){
	$alert_success = $obj_session->data['success'];
	unset($obj_session->data['success']);
}

if(isset($obj_session->data['warning']) && $obj_session->data['warning'] != ''){
	$alert_warning = $obj_session->data['warning'];
	unset($obj_session->data['warning']);
}
//echo $alert_warning ."===";
include_once('model/followup_calender.php');

$obj_industry = new followup_calendar;
//echo $alert_warning ."===";
?>
<!-- for open alert popup all pages active, inactive and delete start-->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Title</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg">Message</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  


<!-- for open alert popup all pages active, inactive and delete close-->
<footer id="footer">
  <div class="text-center padder clearfix">
    <p> <small>&copy; Swiss ERP <?php echo date("Y"); ?></small>
    </p>
  </div>
</footer>
	
    <!-- fuelux --> <script src="<?php echo HTTP_SERVER;?>js/fuelux/fuelux.js"></script>
    <!-- datepicker --> <script src="<?php echo HTTP_SERVER;?>js/datepicker/bootstrap-datepicker.js"></script>
    <!-- slider --> <script src="<?php echo HTTP_SERVER;?>js/slider/bootstrap-slider.js"></script>
    <!-- file input --> <script src="<?php echo HTTP_SERVER;?>js/file-input/bootstrap.file-input.js"></script>
    <!-- combodate --> <script src="<?php echo HTTP_SERVER;?>js/combodate/moment.min.js"></script>
	<script src="<?php echo HTTP_SERVER;?>js/combodate/combodate.js"></script>
    <!-- parsley --> <script src="<?php echo HTTP_SERVER;?>js/parsley/parsley.min.js"></script>

   
  
      <script src="<?php echo HTTP_SERVER;?>jQuery_translate/src/jquery.localizationTool.js"></script>

	<!-- select2  <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>-->
    <?php /* <script type="text/javascript" src="<?php echo HTTP_SERVER;?>js/check_browser_close.js"></script> */ ?>
    
    <script type="text/javascript">
		$('#selectLanguageDropdown').localizationTool({
'defaultLanguage' : 'en_GB',
/* do not throw error if a selector doesn't match */
'ignoreUnmatchedSelectors': false,
/* show the flag on the widget */
'showFlag' : true,
/* show the language on the widget */
'showLanguage': true,
/* show the country on the widget */
'showCountry': true,
/* format of the language/country label */
'labelTemplate': '{{country}} {{(language)}}',
'languages' : {
    /*
     * The format here is <country code>_<language code>.
     * - list of country codes: http://www.gnu.org/software/gettext/manual/html_node/Country-Codes.html
     * - list of language codes: http://www.gnu.org/software/gettext/manual/html_node/Usual-Language-Codes.html#Usual-Language-Codes
     */
},
/*
 * Strings are provided by the user of the plugin. Each entry
 * in the dictionary has the form:
 *
 * [STRING_IDENTIFIER] : {
 *      [LANGUAGE] : [TRANSLATION]
 * }
 *
 * STRING_IDENTIFIER:
 *     id:<html-id-name>           OR
 *     class:<html-class-name>     OR
 *     element:<html-element-name> OR
 *     <string>
 *
 * LANGUAGE: one of the languages defined above (e.g., it_IT)
 *
 * TRANSLATION: <string>
 *
 */
'strings' : {},
/*
 * A callback called whenever the user selects the language
 * from the dropdown menu. If false is returned, the
 * translation will not be performed (but just the language
 * will be selected from the widget).
 *
 * The countryLanguageCode is a string representing the
 * selected language identifier like 'en_GB'
 */
'onLanguageSelected' : function (/*countryLanguageCode*/) { return true; }
});
		$(document).ready(function() {

			$(".selectall").click(function(){
				$(this).parent().children().find('i').addClass('checked');
				$(this).parent().children().find(':checkbox').attr('checked', true);
			});
			$(".unselectall").click(function(){
				$(this).parent().children().find('i').removeClass('checked');
				$(this).parent().children().find(':checkbox').attr('checked', false);
			});
			
			/*$(window).bind("beforeunload", function() { 
				$.ajax({
					url : '<?php HTTP_ADMIN;?>signout.php',
					type: 'post',
					success: function(data){
					}
				});
			})*/
			
			// Wire up the events as soon as the DOM tree is ready
			wireUpEvents();  
        });
		
		var validNavigation = false;
		function endSession() {
			// Browser or broswer tab is closed
			// Do sth here ...
			//alert("bye");
			window.location = "<?php echo HTTP_SERVER;?>admin/signout.php";
		}
		
		function wireUpEvents() {
			/*
			* For a list of events that triggers onbeforeunload on IE
			* check http://msdn.microsoft.com/en-us/library/ms536907(VS.85).aspx
			*/
			window.onbeforeunload = function() {
				  if (!validNavigation) {
					 endSession();
				  }
			}
		
			// Attach the event keypress to exclude the F5 refresh
			$(document).bind('keypress', function(e) {
				if (e.keyCode == 116){
				  validNavigation = true;
				}
			});
		
			// Attach the event click for all links in the page
			$("a").bind("click", function() {
				validNavigation = true;
			});
		
			 // Attach the event submit for all forms in the page
			 $("form").bind("submit", function() {
				validNavigation = true;
			 });
		
			 // Attach the event click for all inputs in the page
			 $("input[type=submit]").bind("click", function() {
				validNavigation = true;
			 });
		
		}
		
		<?php
		if ($alert_success) {
			echo 'set_alert_message("'.$alert_success.'","alert-success","fa-check")';
		}
		if($alert_warning){
			echo 'set_alert_message("'.$alert_warning.'","alert-warning","fa-warning")';
		}
		?>
//[kinjal]: made this functon for check or uncheck to permission(30june 2015)
		function selecctall(n){//on click
		    if($('#selectall_'+n).prop('checked')){
				
				$('.check_'+n).each(function() { //loop through each checkbox
					this.checked = true;  //select all checkboxes with class "checkbox1"               
				});
			}else{ 
				$('.check_'+n).each(function() { //loop through each checkbox
					this.checked = false; //deselect all checkboxes with class "checkbox1"                       
				});         
			}
		}
	</script>
	
</div>
</body>
</html>

