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

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

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
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">         			
               <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>" ><i class="fa fa-plus"></i> New Tin Tie </a>
            </span>	
          </header>
          
          <!--<div class="panel-body">
            
          </div>-->
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th>Name </th>
                      <th>Price </th>
                      <th>Wastage (%)</th>
                      <!--<th><span>By Air</span><br />Will be multiplied with total courier charges</th>
                      <th><span>By Sea</span><br />Will be added to packing per pouch</th>-->
                      <th>Status </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php
					$count = $obj_tin_tie->getcount();
					$pagination_data = '';
					if($count){
							if (isset($_GET['page'])) {
								$page = (int)$_GET['page'];
							} else {
								$page = 1;
							}
						$results = $obj_tin_tie->getvalue();	
						foreach($results as $result){
						?>
						<tr>
						  <td><?php echo $result['tintie_name'];?></td>
						  <td><?php echo $result['price'];?></td>
                          <td><?php echo $result['wastage']; ?></td>
                          <?php /*?><td><?php echo $result['by_air'];?> </td>
                          <td><?php echo $result['by_sea'];?></td><?php */?>
						  <td><?php echo ($result['status']==1 ? '<label class="label label-success">Active</label>' : '<label class="label label-danger">Inactive</label>');?></td>
						  <td>
                          	<a href="<?php echo $obj_general->link($rout,'mod=add&product_tintie_id='.$result['product_tintie_id'],'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
						   </td>
						</tr>
						<?php
						}
						//pagination
                        $pagination = new Pagination();
                        $pagination->total = $count;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
					}else{
						echo "<tr><td colspan='5'>No record found !</td></tr>";
					} ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php echo $pagination_data;?>
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
 <script type="application/javascript">
 $('input[name=status]').change(function(){
	var tintie_id = $(this).attr('id');
	var tinetie_status = this.value;
	var tintie_url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfunc=UpdateTintieStatus','',1);?>");
	 $.ajax({
		 url : tintie_url,
		 type : 'post',
		 data : {tintie_id:tintie_id,tintie_status:tintie_status},
		 success : function(responce){
			 alert(responce);
			 alert("Updated Successfully");
		 },
		 error : function()
		 {
			 alert("Error In Updation");
		 }
	 });
 });
 
 /*$('input[type=radio][name=status]').change(function(){
	 var spout_id = $(this).attr('id');
	 alert($(this).attr('id'));
	 var spout_status = this.value;
	 var spout_url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfunc=UpdateTintieStatus','',1);?>
	 
	 $.ajax({
		 url : spout_url,
		 type : 'post',
		 data : {spout_id:spout_id,spout_status:spout_status},
		 success : function()
		 {
			 alert("Updated Successfully");
		 }
		 error : function()
		 {
			 alert("Error In Updation");
		 }
	 });
	 
 });*/
 </script>    
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>