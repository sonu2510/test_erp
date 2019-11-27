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
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if($display_status) {
		
		// delete
		 if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
		{
			if(!$obj_general->hasPermission('delete',$menuId)){
				$display_status = false;
			} else {
				$obj_dms->updateStatus(2,$_POST['post']);
				$obj_session->data['success'] = UPDATE;
				page_redirect($obj_general->link($rout, '', '',1));
			}
		}

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
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New DMS </a> &nbsp;
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                       
                         <a class="label bg-danger" onClick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                      
                    
            </span>
          </header>
        
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th>Date</th>
                      <th>Title</th>
                      <th>Download</th>
                      <th>User</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php
				  $dms_total =$obj_dms->getTotalDms();
				   $pagination_data = '';
				 
				  if($dms_total){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'dms_id',
                            'order' => 'ASC',
                            'start' => ($page - 1) *  $limit,
                            'limit' =>  $limit
                      );	
                      
					    $dmses = $obj_dms->getDms($option);
						$i=1;
					  foreach($dmses as $dms){ 
					 	 $postedByData = $obj_dms->getUser($dms['user_id'],$dms['user_type_id']);
                        ?>
                       <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $dms['dms_id'];?>"></td>
                          <td><?php echo dateFormat(4,$dms['date_added']);?></td>
                          <td><?php echo $dms['title'];?></td>
                          <td>
                          <strong>
                          <?php $str=strrchr($dms['document_name'],'.');
						  if($str=='.doc')
						  	{
						  ?>
                          	<i class="fa fa-print"></i>
                            
                           <?php } else if($str=='.xls') {
						   ?>
                           <i class="fa fa-list-alt"></i>
                           <?php } else if($str=='.pdf') {
						   ?>
                           <i class="fa fa-pencil-square-o"></i>
                           <?php } else if($str=='.jpg') { ?>
                           <i class="fa fa-smile-o"></i>
                           <?php } else
						   {
						   ?>
                           <i class="fa fa-folder"></i>
                           <?php } ?>
                          <a href="<?php echo HTTP_UPLOAD."admin/dms/".$dms['dms_id'].'/100_'.$dms['document_name'];?>" target="_blank" ><?php echo $dms['document_name'];?></a>
                          </strong>
                          </td>
                          <td>
						  	<?php
									$addedByImage = $obj_general->getUserProfileImage($dms['user_type_id'],$dms['user_id'],'100_');
									$postedByInfo = '';
									$postedByInfo .= '<div class="row">';
										$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
										$postedByInfo .= '<div class="col-lg-9">';
										if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
										if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
										if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
										$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
									$postedByInfo .= '</div>';
									$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
									str_replace("'","\'",$postedByName);
								?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a>
                          
                          </td>
                        </tr>
                        <?php
                   			$i++;  }
                        
                      // pagination
                        $pagination = new Pagination();
                        $pagination->total = $dms_total;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                  } 
				  else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
                <?php echo $pagination_data; ?>
             
            </div>
          </footer>
          
        </section>
      </div>
    </div>
  </section>
</section>

<style>
	.inactive{
		background-color:#999;	
	}
</style>  
 
<?php 
}
 else {
	include(DIR_ADMIN.'access_denied.php');
}
?>