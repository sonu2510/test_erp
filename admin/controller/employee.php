<?php
include('model/employee.php');
$obj_employee = new employee;

/*$employee_id = 1;
$employee = $obj_employee->getEmployee($employee_id);
printr($employee);die;*/
		
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-users"></i> Employee</h4>
    </div>
    
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> Employee Listing </header>
          <div class="panel-body">
            <div class="row text-small">
              <div class="col-sm-4 m-b-mini">
                  <a href="<?php ADMIN_URL;?>index.php?rout=addemployee" class="btn btn-sm btn-white">
                 	Add Employee
                 </a>
                  <a href="<?php ADMIN_URL;?>index.php?rout=" class="btn btn-sm btn-white">
                 	Active
                 </a>
                  <a href="<?php ADMIN_URL;?>index.php?rout=" class="btn btn-sm btn-white">
                 	Inactive
                 </a>
                  <a href="<?php ADMIN_URL;?>index.php?rout=" class="btn btn-sm btn-white">
                 	Delete
                 </a> 
              </div>
             
              <div class="col-sm-4">
                <div class="input-group">
                  <input type="text" class="input-sm form-control" placeholder="Search">
                  <span class="input-group-btn">
                  <button class="btn btn-sm btn-white" type="button">Go!</button>
                  </span> </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $data = $obj_employee->getEmployees();
			  if($data){
				  foreach($data as $employee){ 
					?>
					<tr>
					  <td><input type="checkbox" name="post[]" value="<?php echo $employee['employee_id'];?>"></td>
					  <td><?php echo $employee['name'];?></td>
					  <td><?php echo $employee['user_name'];?></td>
					  <td><?php echo ($employee['name']==1)?'Active':'Inactive';?></td>
					  <td>
                      	<a href="<?php echo ADMIN_URL;?>index.php?rout=addemployee&employee_id=<?php echo base64_encode($employee['employee_id']);?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                        <a href="#" class="btn btn-danger btn-xs">Delete</a>
                      </td>
					</tr>
					<?php
				  }
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
                
              </tbody>
            </table>
          </div>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs">
              </div>
              <div class="col-sm-3 text-center"> <small class="text-muted inline m-t-small m-b-small">showing 20-30 of 50 items</small> </div>
              <div class="col-sm-5 text-right text-center-sm">
                <ul class="pagination pagination-small m-t-none m-b-none">
                  <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li><a href="#">5</a></li>
                  <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                </ul>
              </div>
            </div>
          </footer>
        </section>
      </div>
      
    </div>
  </section>
</section>