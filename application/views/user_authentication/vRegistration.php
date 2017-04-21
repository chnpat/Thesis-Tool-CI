<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<section class="login-form">
				<form method="post" action="<?php echo base_url()."cUser/register_process"; ?>" role="login">
					<h4 class="text-center lead"><b>Account</b> Registration</h4>
					<div class="form-group">
						<input class="form-control input-lg" id="regis_username" name="regis_username" placeholder="Ex. Abc" value="<?php echo set_value('regis_username') ?>" required>
					</div>
					<div class="form-group">
				    	<input type="email" class="form-control input-lg" id="regis_email" name="regis_email" placeholder="example@abc.com" value="<?php echo set_value('regis_email') ?>"  required>
				  	</div>
				 	<div class="form-group">
				    	<input type="password" class="form-control input-lg" id="regis_password" name="regis_password" placeholder="Password" required="">
				  	</div>
				  	<div class="form-group">
				    	<input type="password" class="form-control input-lg" id="regis_confirm_password" name="regis_confirm_password" placeholder="Confirm Password" required="">
				  	</div>
				  	<?php if($this->session->flashdata('registration_error')) { 
				  		echo '<div class="form-group">';
				  		echo '<div class="alert alert-danger">';
				  		echo validation_errors();
				  		echo '</div></div>';
				  	} ?>
				  	<button type="submit" class="btn btn-lg btn-primary btn-block">Register</button>
				  	<a href="<?php echo base_url()."cUser/index" ?>">Go back</a>
				</form>
			</section>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>