<?php
include('model/employee.php');
$obj_employee = new employee;
	
if(isset($_GET['employee_id']) && !empty($_GET['employee_id'])){
	$employee_id = base64_decode($_GET['employee_id']);
	$data = $obj_employee->getEmployee($employee_id);
	//printr($data);die;
}
if(isset($_POST['btn_submit'])){
 
   $firstname = $_POST['first_name'];
   $lastname = $_POST['last_name'];
   $Username = $_POST['user_name'];
   $password = $_POST['password'];
   $status =$_POST['status'];
   $obj_employee-> addEmployees($firstname,$lastname, $Username, $password, $status);
}

if(isset($_POST['btn_update'])){
 
   $firstname = $_POST['first_name'];
   $lastname = $_POST['last_name'];
   $Username = $_POST['user_name'];
   $password = $_POST['password'];
   $status =$_POST['status'];
   $obj_employee-> addEmployees($firstname,$lastname, $Username, $password, $status);
}

	
?>

<section id="content">
  <section class="main padder" >
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i>Form</h4>
    </div>
    <div class="row">
      <div class="col-sm-6">
       <section class="panel">
          <header class="panel-heading bg-white"> Employee Detail </header>
        <section class="panel">
          <div class="panel-body">
            <form class="form-horizontal" method="post" data-validate="parsley">
              
              
             
                <div class="form-group">
                   <label class="col-lg-3 control-label">First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" placeholder="First Name"  value="<?php echo isset($data['first_name'])?$data['first_name']:''; ?>"  data-required="true" class="form-control">
                  </div>
                  </div>
                <div class="form-group">
                   <label class="col-lg-3 control-label">Lastname</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" placeholder="Last Name" value="<?php echo isset($data['last_name'])?$data['last_name']:''; ?>""   data-required="true" class="form-control">
                  </div>
                  </div>
                   <div class="form-group">
                <label class="col-lg-3 control-label">Username(Email)</label>
                <div class="col-lg-8">
                  <input type="text" name="user_name" placeholder="test@example.com" value="<?php echo isset($data['user_name'])?$data['user_name']:''; ?>""  class="bg-focus form-control" data-required="true" data-type="email">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Password</label>
                <div class="col-lg-8">
                  <input type="password" name="password" value="<?php echo isset($data['password'])?$data['password']:''; ?>""  placeholder="Password" class="bg-focus form-control">
                 
                </div>
              </div> 
                 <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                 <div class="col-lg-8">
                  <select name="status" id="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                </div>
               </div>
               
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php
                if(isset($_GET['employee_id']) && !empty($_GET['employee_id'])){
					?>
                	<button type="submit"  name="btn_update" class="btn btn-primary">Update </button>
                    <?php
				}else{
					?>
                    <button type="submit"  name="btn_submit" class="btn btn-primary">Save </button>
                    <?php
				}
				?>
                 <button type="submit"  class="btn btn-white">Cancel</button>
                </div>
              </div>
            </form>
          </div>
          </div>
      	</section>
       </section>
     </section>  
      </section>  
     
     