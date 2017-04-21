<div class="content-wrapper">
	<section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> User
        <small>Detail</small>
      </h1>
    </section>
    <section class="container">
    	<div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Add/Edit User Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="addUser" action="
                    <?php if(empty($userDetail)) { 
                    		echo base_url(); ?>cUserManagement/add_user
                    <?php } else {
                    		echo base_url(); ?>cUserManagement/update_user/<?=$userDetail['id']; ?>
                    <?php } ?>" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Full Name</label>
                                        <input type="text" class="form-control required" id="fname" name="fname" maxlength="128" value="<?php echo $userDetail['user_name']; ?>">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="text" class="form-control required email" id="email"  name="email" maxlength="128" value="<?php echo $userDetail['user_email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control required" id="password"  name="password" value="<?php echo $userDetail['user_password']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" value="<?php echo $userDetail['user_password']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <?php 
                                        $this->load->model('mLogin');
                                        $sessionUser = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0]; ?>
                                        <select class="form-control required" id="status" name="status" enabled="<?php ($sessionUser['user_role'] == 'admin')? True: False; ?>">
                                        	<?php if($userDetail['user_status']) { ?>
                                        		<option value="1" selected>Active</option>
                                        		<option value="0">Disable</option>
                                        	<?php } else { ?>
                                        		<option value="1">Active</option>
                                        		<option value="0" selected>Disable</option>
                                        	<?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control required" id="role" name="role">
                                        	<?php if($userDetail['user_role'] == 'Admin') { ?>
												<option value="0">Select Role</option>
                                        		<option value="1" selected>System Administrator</option>
                                        		<option value="2">Pattern Assessor</option>
                                        		<option value="3">Pattern Developer</option>
                                        	<?php } else if ($userDetail['user_role'] == 'Assessor') { ?>
                                        		<option value="0">Select Role</option>
                                        		<option value="1">System Administrator</option>
                                        		<option value="2" selected>Pattern Assessor</option>
                                        		<option value="3">Pattern Developer</option>
                                        	<?php } else if ($userDetail['user_role'] == 'Regular') { ?>
                                        		<option value="0">Select Role</option>
                                        		<option value="1">System Administrator</option>
                                        		<option value="2">Pattern Assessor</option>
                                        		<option value="3" selected="">Pattern Developer</option>
                                        	<?php } else { ?>
                                        		<option value="0" selected>Select Role</option>
                                        		<option value="1">System Administrator</option>
                                        		<option value="2">Pattern Assessor</option>
                                        		<option value="3">Pattern Developer</option>
                                        	<?php } ?>
                                        </select>
                                    </div>
                                </div>    
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>   
    </section>
</div>