<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-users"></i> Quotation Report</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>  
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
          	 <span>User Listing</span>
             <span class="text-muted m-l-small pull-right">
             		<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
   								<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add</a>
                      <?php }
							if($obj_general->hasPermission('edit',$menuId)){ ?>
                        		<a class="label bg-success" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        		<a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					 		if($obj_general->hasPermission('delete',$menuId)){ ?>   
                        		<a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
             </span>
          </header>
          <div class="panel-body">
             
             <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
                
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              
              
                <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">User Name</label>
                                <div class="col-lg-10">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>
                               <div class="form-group">
                                <label class="col-lg-2 control-label">User Type</label>
                                <div class="col-lg-10">
                                 <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                                </div>
                              </div>
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-4 control-label"></label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_user_name" value="<?php echo isset($filter_username) ? $filter_username : '' ; ?>" placeholder="User Name" id="input-price" class="form-control">
                                </div>
                              </div>
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Active</label>
                                <div class="col-lg-8">
                                  <select name="filter_status" id="input-status" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                     
                      </div>
                     
                     
                </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
          </form>
          
          		
               <div class="row">
              	
                <div class="col-lg-3 pull-right">	
                    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
              </div>
 
        </div>
          
          <form name="form_list" id="form_list" method="post">   
          <input type="hidden" id="action" name="action" value="" /> 
          <div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>Name</th>
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      User Name
                      <span class="th-sort">
                       	<a href="<?php echo $obj_general->link($rout, 'sort=user_name'.'&order=ASC', '',1);?>">
                        <i class="fa fa-sort-down text"></i>
                        <a href="<?php echo $obj_general->link($rout, 'sort=user_name'.'&order=DESC', '',1);?>">
                        <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>
                  
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      Email
                      <span class="th-sort">
                       	<a href="<?php echo $obj_general->link($rout, 'sort=email'.'&order=ASC', '',1);?>">
                        <i class="fa fa-sort-down text"></i>
                        <a href="<?php echo $obj_general->link($rout, 'sort=email'.'&order=DESC', '',1);?>">
                        <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>
                 
                  <th>Status</th>
                  <th>Action</th>

                </tr>
              </thead>
              <tbody>
              <?php
              $user_total = $obj_user->getTotalUser($filter_data);
			  $pagination_data = '';
			  if($user_total){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit,
						'sort'  => $sort_name,
						'order' => $sort_order
				  );	
				  $users = $obj_user->getUsers($option,$filter_data);
				  if($users) {	
				  foreach($users as $user){ 
					?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $user['user_id'];?>"></td>
                      <td><?php echo $user['name'];?></td>
                      <td><?php echo $user['user_name'];?></td>
                      <td><?php echo $user['email'];?></td>
                      <td><label class="label   
                        <?php echo ($user['status']==1)?'label-success':'label-warning';?>">
                        <?php echo ($user['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&user_id='.encode($user['user_id']).'&filter_edit='.$filter_edit, '',1);?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                            <?php if($user['user_id'] != 1){?>
                            <a href="<?php echo $obj_general->link($rout, 'mod=permission&user_id='.encode($user['user_id']), '',1);?>"  name="btn_edit" class="btn btn-warning btn-xs">Permission</a>
                            <?php } ?>
                       </td>
                    </tr>
                    <?php
				  }
				  
				  }else{
					echo "<tr><td colspan='5'>No record found !</td></tr>"; 
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $user_total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&page={page}', '',1);
					$pagination_data = $pagination->render();
					
				  
				    //echo $pagination_data;die;
              } else{ 
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