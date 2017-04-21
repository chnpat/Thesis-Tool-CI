<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<section class="login-form">
				<form method="post" action="<?php echo base_url()."cUser/login_process"; ?>" role="login">
					<h4 class="text-center lead"><b>QAM-OODP</b> Support Tool</h4>
					<div class="form-group">
			    		<input type="email" class="form-control input-lg" id="input_email" name="input_email" placeholder="example@abc.com" required>
				  	</div>
				 	<div class="form-group">
				    	<input type="password" class="form-control input-lg" id="input_password" name="input_password" placeholder="Password" required="">
				  	</div>
				  	<button type="submit" class="btn btn-lg btn-primary btn-block">Log in</button>
				  	<a href="register">Create an account</a> or <a href="forgot">forgot password</a>
				</form>
			</section>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>