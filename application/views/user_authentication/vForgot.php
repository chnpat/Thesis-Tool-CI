<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<section class="login-form">
				<form method="post" action="<?php echo base_url()."cUser/forgot_process" ?>" role="login">
					<h4 class="text-center lead"><b>Forgot</b> Password</h4>
					<div class="form-group">
				    	<input type="email" class="form-control input-lg" id="forgot_email" name="forgot_email" placeholder="example@abc.com" required>
				  	</div>
				  	<?php if($this->session->flashdata('forgot_error')) { 
				  		echo '<div class="form-group">';
				  		echo '<div class="alert alert-danger">';
				  		echo $this->session->flashdata('forgot_error');
				  		echo '</div></div>';
				  	} 
				  	if($this->session->flashdata('forgot_success')) { 
				  		echo '<div class="form-group">';
				  		echo '<div class="alert alert-success">';
				  		echo $this->session->flashdata('forgot_success');
				  		echo '</div></div>';
				  	}
				  	?>
				  	<button type="submit" class="btn btn-lg btn-warning btn-block">Send an email</button>
				  	<a href="<?php echo base_url()."cUser/index" ?>">Go back</a>
				</form>
			</section>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>