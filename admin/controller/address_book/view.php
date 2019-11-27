<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
    'text' => 'Dashboard',
    'href' => $obj_general->link('dashboard', '', '', 1),
    'icon' => 'fa-home',
    'class' => '',
);

$bradcums[] = array(
    'text' => $display_name . ' List',
    'href' => $obj_general->link($rout, '', '', 1),
    'icon' => 'fa-list',
    'class' => '',
);

$bradcums[] = array(
    'text' => $display_name . ' Detail',
    'href' => '',
    'icon' => 'fa-edit',
    'class' => 'active',
);
//Close : bradcums
//Start : edit
$edit = '';
if (isset($_GET['address_book_id']) && !empty($_GET['address_book_id'])) {
    if (!$obj_general->hasPermission('edit', $menuId)) {
        $display_status = false;
    } else {
        $address_book_id = base64_decode($_GET['address_book_id']);
        $address_book_details = $obj_address->all_customer_address($address_book_id);
        //printr($address_book_details);
    }
}
$address_id = '0';
$add_url='';
if (isset($_GET['address_book_id'])) {
    $address_id = decode($_GET['address_book_id']);
    $add_url='&address_book_id='.$_GET['address_book_id'];
}
//Close : edit
if ($display_status) {
    ?>

    <section id="content">
        <section class="main padder">
            <div class="clearfix">
                <h4><i class="fa fa-edit"></i> <?php echo $display_name; ?></h4>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <?php include("common/breadcrumb.php"); ?>
                </div>
                <div class="col-lg-8">
                    <section class="panel">
                        <header class="panel-heading bg-white"><span><?php echo $display_name ?> Detail</span> 
                                <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
									<span class="text-muted m-l-small pull-right"><a href="<?php echo $obj_general->link($rout, 'mod=add&address_book_id='.encode($address_book_details['address_book_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a></span>
							<?php }?>
                        
                        </header>

                        <?php
                        if ($address_book_details) {
                            // printr($address_book_details);<br />
                            ?>
                            <div class="row">

                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Company Name</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ucwords($address_book_details['company_name']); ?> </label>
                                            </div>
                                            
                                             <label class="col-lg-2 control-label">Company Since</label> 
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo dateFormat(4,$address_book_details['date_added']); ?> </label>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Contact Name</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ucwords($address_book_details['contact_name']); ?> </label>
                                            </div>
                                            <label class="col-lg-2 control-label">Contact Owner</label> 
                                            <div class="col-lg-4">
												<?php $postedByData = $obj_address->getUser($address_book_details['user_id'],$address_book_details['user_type_id']); ?>
                                                <label class="control-label normal-font"> <span style="color:red"><?php echo $postedByData['user_name']; ?></span> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Vat No.</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo $address_book_details['vat_no']; ?> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">&nbsp;&nbsp;Designation</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ucwords($address_book_details['designation']); ?> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">&nbsp;&nbsp;Department</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ucwords($address_book_details['department']); ?> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Website</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ($address_book_details['website']); ?> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Remark</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <?php echo ucwords($address_book_details['remark']); ?> </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Logo</label>
                                            <div class="col-lg-4">
                                                <label class="control-label normal-font"> <img src="<?php echo isset($address_book_details) ? $address_book_details['logo'] : ''; ?>" height="70" width="70" alt=""> </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Company</label>
                                            <div class="col-lg-9">
                                                <section class="panel">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped b-t text-small">
                                                            <thead>
                                                                <tr>
                                                                    <th>City</th>
                                                                    <th>State</th>
                                                                    <th>Country</th>
                                                                    <th>Pincode</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Email</th>
                                                                    <th>Email - 2</th>
                                                                </tr>
                                                            </thead>
                                                            <?php foreach ($address_book_details['company'] as $company) {
                                                                ?>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><?php echo ucwords($company['city']); ?></td>
                                                                        <td><?php echo ucwords($company['state']); ?></td>
                                                                        <?php $country = $obj_address->get_country_selected($company['country']); ?>  
                                                                        <td><?php echo ucwords($country['country_name']); ?></td>
                                                                        <td><?php echo $company['pincode']; ?></td>
                                                                        <td><?php echo $company['phone_no']; ?></td>
                                                                        <td><?php echo $company['email_1']; ?></td>
                                                                        <td><?php echo $company['email_2']; ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Factory</label>
                                            <div class="col-lg-9">
                                                <section class="panel">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped b-t text-small">
                                                            <thead>
                                                                <tr>
                                                                    <th>City</th>
                                                                    <th>State</th>
                                                                    <th>Country</th>
                                                                    <th>Pincode</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Email</th>
                                                                    <th>Email - 2</th>
                                                                </tr>
                                                            </thead>

                                                            <?php
                                                            foreach ($address_book_details['factory'] as $factory) {
                                                                ?>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><?php echo ucwords($factory['city']); ?></td>
                                                                        <td><?php echo ucwords($factory['state']); ?></td>
                                                                        <?php $country = $obj_address->get_country_selected($factory['country']); ?>         
                                                                        <td><?php echo ucwords($country['country_name']); ?></td>
                                                                        <td><?php echo $factory['pincode']; ?></td>
                                                                        <td><?php echo $factory['phone_no']; ?></td>
                                                                        <td><?php echo $factory['email_1']; ?></td>
                                                                        <td><?php echo $factory['email_2']; ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>
                    
                    <?php 
					$n = 1;
						$menu_id = $obj_address->getMenuPermission(55,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
						if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
						{
					?>
                    
                    <div class="col-lg-4" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Enquiry</b></span>
                                <?php $address_id = $address_book_details['address_book_id']; ?>
                                <span class="text-muted m-l-small pull-right">
                                  
                                    <a class="label bg-info" href="<?php echo $obj_general->link('enquiry', 'mod=index&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>																				

                                </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Enquiry No.</th>
                                        <th>Customer Name</th>
                                        <th>Email/Phone</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $enquiries = $obj_address->getLatestEnquiries($address_id);
									//printr($enquiries);
                                    if ($enquiries) {
                                        foreach ($enquiries as $enquiry) {
                                            	//printr($enquiry);
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($enquiry['enquiry_id']).$add_url, '', 1); ?>"><?php echo $enquiry['enquiry_number']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($enquiry['enquiry_id']).$add_url, '', 1); ?>"><?php echo $enquiry['name']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($enquiry['enquiry_id']).$add_url, '', 1); ?>"><?php echo $enquiry['email']; ?><br/>
                                                        <small class="text-muted"><?php echo $enquiry['mobile_number']; ?></small></a>
                                                </td>
                                                <td>
                                                 <?php
                                                    $postedByData = $obj_address->getUser($enquiry['user_id'], $enquiry['user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($enquiry['user_type_id'], $enquiry['user_id'], '100_');
                                                    $postedByInfo = '';
                                                    $postedByInfo .= '<div class="row">';
                                                    $postedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $postedByInfo .= '<div class="col-lg-9">';
                                                    if ($postedByData['city']) {
                                                        $postedByInfo .= $postedByData['city'] . ', ';
                                                    }
                                                    if ($postedByData['state']) {
                                                        $postedByInfo .= $postedByData['state'] . ' ';
                                                    }
                                                    if (isset($postedByData['postcode'])) {
                                                        $postedByInfo .= $postedByData['postcode'];
                                                    }
                                                    $postedByInfo .= '<br>Telephone : ' . $postedByData['telephone'] . '</div>';
                                                    $postedByInfo .= '</div>';
                                                    $postedByName = $postedByData['first_name'] . ' ' . $postedByData['last_name'];
                                                    str_replace("'", "\'", $postedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo; ?>' title="" data-original-title="<b><?php echo $postedByName; ?></b>"><?php echo $postedByData['user_name']; ?></a>
                                                
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>             
                        </section>
                    </div> 
			<?php } ?>
            	</div>
                 <div class="row">	
            <?php
            //offline id : 191 & online : 181
			
						$menu_id = $obj_address->getMenuPermission(181,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
						if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
						{
					?>
                    
                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                             <span><b>Upcoming Followup's</b></span> 
                                <?php $address_id = $address_book_details['address_book_id']; ?>
                                <span class="text-muted m-l-small pull-right">
                                    <a class="label bg-info" href="<?php echo $obj_general->link('enquiry', 'mod=followup_history&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>

                                </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                               <thead>
                                    <tr>
                                      <th>Enquiry No.</th>
                                      <th>Customer Name</th>
                                      <th>Followup Date</th>
                                      <th>Created By</th>                          
                                    </tr>
                                 </thead>
                               <tbody>
										<?php 
                                        $follow_ups = $obj_address->getUpcomingFollowup($address_id);
										//printr($follow_ups);
                                        if($follow_ups){
                                        foreach($follow_ups as $follow_up) {
                                        ?>
                                        <tr data-href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id='.encode($follow_up['enquiry_id']), '',1);?>">
                                       			 <td><a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($follow_up['enquiry_id']).$add_url, '', 1); ?>"><?php echo $follow_up['enquiry_number']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($follow_up['enquiry_id']).$add_url, '', 1); ?>"><?php echo $follow_up['name']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('enquiry', '&mod=view&enquiry_id=' . encode($follow_up['enquiry_id']).$add_url, '', 1); ?>"><?php echo date("d-M-y",strtotime($follow_up['followup_date'])); ?></a>
                                                </td>
                                                <td>
                                                
                                                 <?php
                                                    $postedByData = $obj_address->getUser($follow_up['user_id'], $follow_up['user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($follow_up['user_type_id'], $follow_up['user_id'], '100_');
                                                    $postedByInfo = '';
                                                    $postedByInfo .= '<div class="row">';
                                                    $postedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $postedByInfo .= '<div class="col-lg-9">';
                                                    if ($postedByData['city']) {
                                                        $postedByInfo .= $postedByData['city'] . ', ';
                                                    }
                                                    if ($postedByData['state']) {
                                                        $postedByInfo .= $postedByData['state'] . ' ';
                                                    }
                                                    if (isset($postedByData['postcode'])) {
                                                        $postedByInfo .= $postedByData['postcode'];
                                                    }
                                                    $postedByInfo .= '<br>Telephone : ' . $postedByData['telephone'] . '</div>';
                                                    $postedByInfo .= '</div>';
                                                    $postedByName = $postedByData['first_name'] . ' ' . $postedByData['last_name'];
                                                    str_replace("'", "\'", $postedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo; ?>' title="" data-original-title="<b><?php echo $postedByName; ?></b>"><?php echo $postedByData['user_name']; ?></a>
                                                
                                                
                                                </td>
                                        </tr>
                                        <?php } ?>
                                        <?php 
                                              }else{
                                                  echo "<tr> No record found! </tr>";
                                              }?>
                                     </tbody>
                            </table>             
                        </section>
                    </div> 
			<?php } 
			 
				$menu_id = $obj_address->getMenuPermission(108,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
				if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
				{
				?>
                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Proforma Invoice</b></span>
                                <span class="text-muted m-l-small pull-right">
                                    <a class="label bg-info" href="<?php echo $obj_general->link('proforma_invoice', 'mod=index&is_delete=0&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>
                                </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Proforma Invoice No.</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //[kinjal] modify on (13-4-2017)
                                    $proformas = $obj_address->get_proforma_invoice($obj_session->data['ADMIN_LOGIN_SWISS'], $obj_session->data['LOGIN_USER_TYPE'], $address_id);
								
                                    if ($proformas) {
                                        foreach ($proformas as $proforma) {
                                            $userInfo = $obj_address->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('proforma_invoice', '&mod=view&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0'.$add_url, '', 1); ?>"><?php echo $proforma['pro_in_no']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('proforma_invoice', '&mod=view&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0'.$add_url, '', 1); ?>"><?php echo $proforma['customer_name']; ?></a></td>

                                                <td>
                                                    <a href="<?php echo $obj_general->link('proforma_invoice', '&mod=view&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0'.$add_url, '', 1); ?>">
														<?php echo $proforma['email']; ?></a>

                                                </td>
                                                <td>
                                                    <?php
                                                    $addedByImage = $obj_general->getUserProfileImage($proforma['added_by_user_type_id'], $proforma['added_by_user_id'], '100_');
                                                    $addedByInfo = '';
                                                    $addedByInfo .= '<div class="row">';
                                                    $addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $addedByInfo .= '<div class="col-lg-9">';
                                                    if ($userInfo['city']) {
                                                        $addedByInfo .= $userInfo['city'] . ', ';
                                                    }
                                                    if ($userInfo['state']) {
                                                        $addedByInfo .= $userInfo['state'] . ' ';
                                                    }
                                                    if (isset($userInfo['postcode'])) {
                                                        $addedByInfo .= $userInfo['postcode'];
                                                    }
                                                    $addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
                                                    $addedByInfo .= '</div>';
                                                    $addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
                                                    str_replace("'", "\'", $addedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>            
                        </section>
                    </div>
                    
                    
			  <?php } ?>
            	</div>
                <div class="row">	
			  <?php 
			  $menu_id = $obj_address->getMenuPermission(84,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
						if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS']  == 1)
						{
					?>
                         <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                 <span><b>Latest 5 Quotation</b></span>
                                <span class="text-muted m-l-small pull-right"> 
                                <a class="label bg-info" href="<?php echo $obj_general->link('multi_product_quotation', 'address_book_id=' . encode($address_id), '', 1); ?>">
                                    <i class="fa fa-list"></i> View All</a>
                                 
                                </span>                
                            </header>
                              <table id="quotation-row" class="table b-t text-small table-hover">
                                 <thead>
                                    <tr>
                                      <th>Quotation No. / Date</th>
                                      <th>Customer Name</th>
                                      <th>Product</th> 
                                      <th>Created By</th>                          
                                    </tr>
                                 </thead>	
                                 
                     			<tbody>
                     	  <?php
						   $latest_quotations = $obj_address->getLatestQuotation($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'], $address_id);
							//printr( $latest_quotations);	
                               
						  if(isset($latest_quotations) && !empty($latest_quotations)){ 
						  		foreach($latest_quotations as $quotation) { 
							
								if($quotation['quotation_status']==0){
									?>
									 <tr  style="background-color:#f2dede" data-href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>">
									<?php
								}else{
									?>
									 <tr data-href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>" <?php echo ($quotation['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?>> 
									<?php
								}
								?>
                                    <td><?php echo $quotation['multi_quotation_number']; ?><br/>
                                        <small class="text-muted"><?php echo dateFormat(4,$quotation['date_added']);?></small>
                                    </td>
                                    
                                    <td><?php echo $quotation['customer_name']; ?><br/>
                                        <small class="text-muted"><?php echo $quotation['country_name']; ?></small>
                                    </td>
                                    <td>
                                        <a href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>">
										<?php echo $quotation['product_name'];?></a><br />
                                    <small class="text-muted"><?php echo $quotation['layer'].' Layer';?></small><br />
                                    
                                    </td>
                                    
                                    <td> 
                                        <?php
                                        $postedByData = $obj_address->getUser($quotation['added_by_user_id'], $quotation['added_by_user_type_id']);
                                        $addedByImage = $obj_general->getUserProfileImage($quotation['added_by_user_type_id'], $quotation['added_by_user_id'], '100_');
                                        $postedByInfo = '';
                                        $postedByInfo .= '<div class="row">';
                                        $postedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                        $postedByInfo .= '<div class="col-lg-9">';
                                        if ($postedByData['city']) {
                                            $postedByInfo .= $postedByData['city'] . ', ';
                                        }
                                        if ($postedByData['state']) {
                                            $postedByInfo .= $postedByData['state'] . ' ';
                                        }
                                        if (isset($postedByData['postcode'])) {
                                            $postedByInfo .= $postedByData['postcode'];
                                        }
                                        $postedByInfo .= '<br>Telephone : ' . $postedByData['telephone'] . '</div>';
                                        $postedByInfo .= '</div>';
                                        $postedByName = $postedByData['first_name'] . ' ' . $postedByData['last_name'];
                                        str_replace("'", "\'", $postedByName);
                                        ?>
                                        <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo; ?>' title="" data-original-title="<b><?php echo $postedByName; ?></b>"><?php echo $postedByData['user_name']; ?></a>
                                    </td>
                                </tr>    
                                <?php
                                }
						  }else{
							  echo "<tr> No record found! </tr>";
						  }
						  	?>
                            
                     </tbody>   
                  </table>
                 
               </section>
                    </div>
                                         
			 <?php } 
					
						$menu_id = $obj_address->getMenuPermission(63,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
						if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS']  == 1)
						{
					?>

                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Custom Order</b></span>
                                <span class="text-muted m-l-small pull-right">
                                <a class="label bg-info" href="<?php echo $obj_general->link('custom_order', 'address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>
                                  <?php /*?>  <a class="label bg-info" href="<?php echo $obj_general->link($rout, 'mod=index&address_book_id=' . encode($address_book_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a><?php */?>

                                </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $custom_orders = $obj_address->latestCustomOrders($obj_session->data['LOGIN_USER_TYPE'], $obj_session->data['ADMIN_LOGIN_SWISS'], $address_id);
									
                                    if ($custom_orders) {
                                        foreach ($custom_orders as $custom_order) {
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id=' . encode($custom_order['multi_custom_order_id']) . '&filter_edit=0'.$add_url, '', 1); ?>"><?php echo $custom_order['multi_custom_order_number']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id=' . encode($custom_order['multi_custom_order_id']).$add_url, '', 1); ?>"><?php echo $custom_order['customer_name']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id=' . encode($custom_order['multi_custom_order_id']).$add_url, '', 1); ?>"><?php echo $custom_order['email']; ?><br/>
                                                        <small class="text-muted"><?php echo $custom_order['country_name']; ?></small></a>
                                                </td>

                                                <td> 
                                                    <?php
                                                    $postedByData = $obj_address->getUser($custom_order['added_by_user_id'], $custom_order['added_by_user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($custom_order['added_by_user_type_id'], $custom_order['added_by_user_id'], '100_');
                                                    $postedByInfo = '';
                                                    $postedByInfo .= '<div class="row">';
                                                    $postedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $postedByInfo .= '<div class="col-lg-9">';
                                                    if ($postedByData['city']) {
                                                        $postedByInfo .= $postedByData['city'] . ', ';
                                                    }
                                                    if ($postedByData['state']) {
                                                        $postedByInfo .= $postedByData['state'] . ' ';
                                                    }
                                                    if (isset($postedByData['postcode'])) {
                                                        $postedByInfo .= $postedByData['postcode'];
                                                    }
                                                    $postedByInfo .= '<br>Telephone : ' . $postedByData['telephone'] . '</div>';
                                                    $postedByInfo .= '</div>';
                                                    $postedByName = $postedByData['first_name'] . ' ' . $postedByData['last_name'];
                                                    str_replace("'", "\'", $postedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo; ?>' title="" data-original-title="<b><?php echo $postedByName; ?></b>"><?php echo $postedByData['user_name']; ?></a>
                                                </td>
                                                <td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>             
                        </section>
                    </div>
		<?php } ?>
            	</div>
                <div class="row">	
			  <?php 
						$menu_id = $obj_address->getMenuPermission(75,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'], $n );
						if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
						{//style="margin-left:66.5%;"
					?>

                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Stock Order</b></span>
                                <span class="text-muted m-l-small pull-right">
                                   
                                    <a class="label bg-info" href="<?php echo $obj_general->link('template_order', 'mod=cartlist_view&status=0&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>

                                </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Order Type</th>
                                        <th>Transport</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $_GET['status'] = 0;
                                    $dis_cond = $dis_table = $dis_select = $sen = '';
                                    if (isset($_GET['status'])) {
                                        $cond = 'AND (sos.status="' . $_GET['status'] . '" ' . $dis_cond . ') AND t.status=1';
                                        if ($_GET['status'] == 3) {
                                            $dis_cond = ' (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)';
                                            //'OR (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id)';
                                            $dis_table = ', stock_order_dispatch_history as sodh';
                                            $dis_select = 'sum(sodh.dis_qty) as  dis_qty,sum(sodh.dis_qty*t.price) as dis_total_price,';
                                            $sen = 'Dispatched';
                                            $cond = 'AND (' . $dis_cond . ') AND t.status=1';
                                            //$dis_cond = 'OR (sos.status = 1) OR (sos.status = 2)';
                                        } else if ($_GET['status'] == 2) {
                                            $dis_cond = 'AND (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=2)';
                                            $dis_table = ', stock_order_dispatch_history as sodh';
                                            $dis_select = 'sum(sodh.decline_qty) as  dis_qty,sum(sodh.decline_qty*t.price) as dis_total_price,';
                                            $sen = 'Declined';
                                            $cond = 'AND (sos.status="' . $_GET['status'] . '" ' . $dis_cond . ') AND t.status=1';
                                        }


                                        if ($_GET['status'] == 0)
                                            $mod = 'index';
                                        if ($_GET['status'] == 1)
                                            $mod = 'in_process';
                                        if ($_GET['status'] == 2)
                                            $mod = 'decline';
                                        if ($_GET['status'] == 3)
                                            $mod = 'dispatch';
                                        $tot_status = 1;
                                        $s = $_GET['status']; //Define for status
                                        $page_s = '&status=' . $s;
                                    }
                                    else {
                                        $cond = 'AND t.status="0"';
                                        $mod = 'cart_list';
                                        $tot_status = 0;
                                        $s = '0';
                                        $page_s = '';
                                    }
                                    if (isset($_GET['temp_status']) && $_GET['temp_status'] != '6') {
                                        if ($_GET['temp_status'] == '1')
                                            $interval = 5;
                                        if ($_GET['temp_status'] == '2')
                                            $interval = 10;
                                        if ($_GET['temp_status'] == '3')
                                            $interval = 15;
                                        if ($_GET['temp_status'] == '4')
                                            $interval = 20;
                                        if ($_GET['temp_status'] == '5')
                                            $interval = 30;
                                    }
                                    else {
                                        $interval = '';
                                    }
                                    $stock_orders = $obj_address->GetLatestCartOrderList($obj_session->data['ADMIN_LOGIN_SWISS'], $obj_session->data['LOGIN_USER_TYPE'], $cond, isset($_GET['status']), $interval, $dis_table, $dis_select, $s, $address_id);
									//printr($stock_orders);
                                    if ($stock_orders) {
                                        foreach ($stock_orders as $stock_order) {
                                            //printr($stock_orders);
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('template_order', 'mod=index&client_id=' . encode($stock_order['client_id']) . '&stock_order_id=' . encode($stock_order['stock_order_id']).$add_url, '', 1); ?>"><?php echo $stock_order['gen_order_id']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('template_order', 'mod=index&client_id=' . encode($stock_order['client_id']) . '&stock_order_id=' . encode($stock_order['stock_order_id']).$add_url, '', 1); ?>"><?php echo $stock_order['order_type']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('template_order', 'mod=index&client_id=' . encode($stock_order['client_id']) . '&stock_order_id=' . encode($stock_order['stock_order_id']).$add_url, '', 1); ?>"><?php echo $stock_order['transport']; ?></a><br/>
                                                </td>
                                                <td>  <?php
                                                    $postedByData = $obj_address->getUser($stock_order['user_id'], $stock_order['user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($stock_order['user_type_id'], $stock_order['user_id'], '100_');
                                                    $postedByInfo = '';
                                                    $postedByInfo .= '<div class="row">';
                                                    $postedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $postedByInfo .= '<div class="col-lg-9">';
                                                    if ($postedByData['city']) {
                                                        $postedByInfo .= $postedByData['city'] . ', ';
                                                    }
                                                    if ($postedByData['state']) {
                                                        $postedByInfo .= $postedByData['state'] . ' ';
                                                    }
                                                    if (isset($postedByData['postcode'])) {
                                                        $postedByInfo .= $postedByData['postcode'];
                                                    }
                                                    $postedByInfo .= '<br>Telephone : ' . $postedByData['telephone'] . '</div>';
                                                    $postedByInfo .= '</div>';
                                                    $postedByName = $postedByData['first_name'] . ' ' . $postedByData['last_name'];
                                                    str_replace("'", "\'", $postedByName);
                                                    ?>
                                                    <a  data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo; ?>' title="" data-original-title="<b><?php echo $postedByName; ?></b>">
                                                        <span class="label bg-info" style="font-size: 100%; "><?php echo $postedByData['user_name']; ?></span>
                                                    </a> </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>             
                        </section>
                    </div>
  <?php }
			$menu_id = $obj_address->getMenuPermission(122,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
			if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
			{ // style="margin-left:66.5%;"
		?>

                    <div class="col-lg-6"> 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Sales Invoice</b></span>
                                <span class="text-muted m-l-small pull-right">
                                 
                                   
                                    <a class="label bg-info" href="<?php echo $obj_general->link('sales_invoice', 'mod=index&is_delete=0&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>     </span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Sales Invoice No.</th>
                                        <th>Proforma No.</th>
                                        <th>Customer Name</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                
                                    <?php
									//sejal 14-04
                                    $sales = $obj_address->all_Invoice($obj_session->data['LOGIN_USER_TYPE'], $obj_session->data['ADMIN_LOGIN_SWISS'],$address_id);
                                
                                    if ($sales) {
                                        foreach ($sales as $sale) {
                                            ?>

                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['invoice_no']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['proforma_no']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['customer_name']; ?><br />
                                                        <small class="text-muted">[<?php echo $sale['invoice_date']; ?>]</small></a>
                                                </td>

                                                <td>
                                                    <?php
                                                    $userInfo = $obj_address->getUser($sale['user_id'], $sale['user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($sale['user_type_id'], $sale['user_id'], '100_');
                                                    //printr($proforma['added_by_user_id'],'100_');
                                                    $addedByInfo = '';
                                                    $addedByInfo .= '<div class="row">';
                                                    $addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $addedByInfo .= '<div class="col-lg-9">';
                                                    if ($userInfo['city']) {
                                                        $addedByInfo .= $userInfo['city'] . ', ';
                                                    }
                                                    if ($userInfo['state']) {
                                                        $addedByInfo .= $userInfo['state'] . ' ';
                                                    }
                                                    if (isset($userInfo['postcode'])) {
                                                        $addedByInfo .= $userInfo['postcode'];
                                                    }
                                                    $addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
                                                    $addedByInfo .= '</div>';
                                                    $addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
                                                    str_replace("'", "\'", $addedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>


                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>            
                        </section>
                    </div>
	
<?php } ?>
            	</div>
                <div class="row">	
			  <?php 
				$menu_id = $obj_address->getMenuPermission(122,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
				
				if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
				{ //style="margin-left:66.5%;"
					?>
                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Payments</b></span>
                                <?php $company_name = $address_book_details['company_name']; ?>
                                <span class="text-muted m-l-small pull-right">
                                    <a class="label bg-info" href="<?php echo $obj_general->link('sales_invoice', 'mod=index&is_delete=0&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a>      	</span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                <thead>
                                    <tr>
                                        <th>Sales Invoice No.</th>
                                        <th>Proforma No.</th>
                                        <th>Payment</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sales = $obj_address->all_Invoice($obj_session->data['LOGIN_USER_TYPE'], $obj_session->data['ADMIN_LOGIN_SWISS'], $address_id);
                                   //printr($address_id);
                                    if ($sales) {
                                        foreach ($sales as $sale) {
                                            ?>

                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['invoice_no']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['proforma_no']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('sales_invoice', '&mod=view&invoice_no=' . encode($sale['invoice_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $sale['amount_paid']; ?></a><br/>
                                                </td>
                                                <td>
                                                    <?php
                                                    $userInfo = $obj_address->getUser($sale['user_id'], $sale['user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($sale['user_type_id'], $sale['user_id'], '100_');
                                                    //printr($proforma['added_by_user_id'],'100_');
                                                    $addedByInfo = '';
                                                    $addedByInfo .= '<div class="row">';
                                                    $addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $addedByInfo .= '<div class="col-lg-9">';
                                                    if ($userInfo['city']) {
                                                        $addedByInfo .= $userInfo['city'] . ', ';
                                                    }
                                                    if ($userInfo['state']) {
                                                        $addedByInfo .= $userInfo['state'] . ' ';
                                                    }
                                                    if (isset($userInfo['postcode'])) {
                                                        $addedByInfo .= $userInfo['postcode'];
                                                    }
                                                    $addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
                                                    $addedByInfo .= '</div>';
                                                    $addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
                                                    str_replace("'", "\'", $addedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>            
                        </section>
                    </div>

                

            <?php }
            //offline : 157 & online : 152
				$menu_id = $obj_address->getMenuPermission(152,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$n);
				
				if(!empty($menu_id) || $_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
				{ //style="margin-left:66.5%;"
					?>
                    <div class="col-lg-6" > 
                        <section class="panel">
                            <header class="panel-heading bg-white">  
                                <span><b>Latest 5 Proforma Invoice Product Code Wise</b></span>
                                <?php $company_name = $address_book_details['company_name']; ?>
                                <span class="text-muted m-l-small pull-right">
                                    <a class="label bg-info" href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=index&is_delete=0&address_book_id=' . encode($address_id), '', 1); ?>"><i class="fa fa-list"></i> View All</a> 	</span>                
                            </header>
                            <table id="enquiry-row" class="table b-t text-small table-hover">
                                 <thead>
                                    <tr>
                                        <th>Proforma Invoice No.</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Created By</th>                          
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $proforma_pro_wise = $obj_address->getLatestProforma_pro_wise( $obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],  $address_id);
                                   //printr($proforma_pro_wise);
                                    if ($proforma_pro_wise) {
                                        foreach ($proforma_pro_wise as $pro_code_wise) {
                                            ?>

                                            <tr>
                                                <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id=' . encode($pro_code_wise['proforma_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $pro_code_wise['pro_in_no']; ?></a></td>
                                                <td><a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id=' . encode($pro_code_wise['proforma_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $pro_code_wise['customer_name']; ?></a></td>
                                                <td>
                                                    <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', '&mod=view&proforma_id=' . encode($pro_code_wise['proforma_id']) . '&status=1&is_delete=0'.$add_url, '', 1); ?>"><?php echo $pro_code_wise['email']; ?></a><br/>
                                                </td>
                                                <td>
                                                    <?php
                                                    $userInfo = $obj_address->getUser($pro_code_wise['added_by_user_id'], $pro_code_wise['added_by_user_type_id']);
                                                    $addedByImage = $obj_general->getUserProfileImage($pro_code_wise['added_by_user_type_id'], $pro_code_wise['added_by_user_id'], '100_');
                                                    //printr($proforma['added_by_user_id'],'100_');
                                                    $addedByInfo = '';
                                                    $addedByInfo .= '<div class="row">';
                                                    $addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
                                                    $addedByInfo .= '<div class="col-lg-9">';
                                                    if ($userInfo['city']) {
                                                        $addedByInfo .= $userInfo['city'] . ', ';
                                                    }
                                                    if ($userInfo['state']) {
                                                        $addedByInfo .= $userInfo['state'] . ' ';
                                                    }
                                                    if (isset($userInfo['postcode'])) {
                                                        $addedByInfo .= $userInfo['postcode'];
                                                    }
                                                    $addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
                                                    $addedByInfo .= '</div>';
                                                    $addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
                                                    str_replace("'", "\'", $addedByName);
                                                    ?>
                                                    <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        echo "<tr> No record found! </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>            
                        </section>
                    </div>

                

            <?php } ?>
            </div>
            <?php
			} else { ?>
                <div class="text-center">No Data Available</div>
            <?php } ?>
        </section>
    </section>





    <script>

        $('#add-history').click(function () {

            var history_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addHistory', '', 1); ?>")
            $('.date-error').html('');
            $('.note-error').html();
            //var reminder  = $('select#reminder option:selected').val();

            //alert(reminder);
            var error = 0;

            if ($('#fol-date').val() == '') {


                $('.date-error').html('Please Select Date');
                error++;

            }

            if ($('#enq-note').val() == '') {
                $('.note-error').html('Please Enter Note');
                error++;
            }
            if (error == 0) {
                $('#loading').show();
                $.ajax({
                    url: history_url,
                    type: 'post',
                    data: $('.history-form input,.history-form textarea ,.history-form select'),
                    success: function (response) {
                        $('#loading').remove();
                        $('#no-history').remove();
                        $('#history-body').append(response);
                        $(this).removeAttr('disabled');
                        $(this).html('<i class="fa fa-plus"></i> Add History');

                    }
                });
            }
        });

    </script> 
    <!-- Close : validation script -->

    <?php
} else {
    include(DIR_ADMIN . 'access_denied.php');
}
?>
