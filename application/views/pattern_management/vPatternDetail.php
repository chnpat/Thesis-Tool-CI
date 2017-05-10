<div class="content-wrapper">
	<section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Pattern
        <small>Detail</small>
      </h1>
    </section>
    <section class="content">
    	<div class="col-12">
			<?php if($this->session->flashdata("pattern_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("pattern_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("pattern_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("pattern_error"); ?>
				</div>
		    <?php } ?>
		</div>
    	<div class="row">
            <!-- left column -->
            <div class="col-md-12">
              	<!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Add/Edit Pattern Details</h3>
                    </div><!-- /.box-header -->
	                <form role="form" id="addUser" action="
	                    <?php if(empty($rows)) { 
	                    		echo base_url(); ?>cPattern/add_pattern
	                    <?php } else {
	                    		echo base_url(); ?>cPattern/edit_pattern/<?=$rows['pattern_id']; ?>
	                    <?php } ?>" method="post" role="form">
	                	<div class="box-body">
	                        <div class="row">
	                        	<div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_id">Pattern ID</label>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                                <div class="form-group">
	                                	<?php if(empty($rows)){ ?>
	                                		<input type="text" class="form-control" id="pattern_id" name="pattern_id" value="<?php echo $rows['pattern_id']; ?>" placeholder="e.g. P-01" required>
	                                	<?php } else { ?>
	                                		<input type="text" class="form-control" id="pattern_id" name="pattern_id" value="<?php echo $rows['pattern_id']; ?>" placeholder="e.g. P-01" readonly required>
	                                	<?php } ?>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_name">Pattern Name</label>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                	<input type="text" class="form-control" id="pattern_name" name="pattern_name" value="<?php echo $rows['pattern_name']; ?>" placeholder="e.g. ABC Pattern" required>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="row">
	                        	<?php if($userObj['user_role'] == 'Admin') {?>
	                            <div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_creator_id">Creator</label>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                	<select class="form-control form-control-sm" id="pattern_creator_id" name="pattern_creator_id">
	                                		<?php $this->load->model("mLogin");
	                                			$user = $this->mLogin->get_user_all();
	                                			foreach ($user as $usr) {
	                                				if($usr['user_role'] != 'Assessor'){
	                                					$select = ($rows['pattern_creator_id'] == $usr['id'])?'selected':'';
	                                					switch ($usr['user_role']) {
	                                						case 'Admin':
	                                							$role = "System Administrator";
	                                							break;
	                                						default:
	                                							$role = "Pattern Developer";
	                                							break;
	                                					}
	                                					echo "<option value='".$usr['id']."' ".$select.">".$usr['id']." - ".$usr['user_name']." (".$role.")</option>";
	                                				}
	                                			}
	                                		?>
	                                	</select>
	                                </div>
	                            </div>
	                            <?php } ?>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_status">Status</label>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="form-group">
	                                	<select class="form-control form-control-sm" id="pattern_status" name="pattern_status">
	                                		<?php 
	                                		if($rows['pattern_status'] == 'Ready'){
	                                			echo "<option value='".$rows['pattern_status']."' selected>Ready to be Assessed</option>";
	                                			echo "<option value='Assessed'>Assessed</option>";
	                                			echo "<option value='Disable'>Disable</option>";
	                                		}
	                                		else if ($rows['pattern_status'] == 'Assessed'){
	                                			echo "<option value='Ready'>Ready to be Assessed</option>";
	                                			echo "<option value='".$rows['pattern_status']."' selected>Assessed</option>";
	                                			echo "<option value='Disable'>Disable</option>";
	                                		}
	                                		else if ($row['pattern_status'] == 'Disable'){
	                                			echo "<option value='Ready'>Ready to be Assessed</option>";
	                                			echo "<option value='Assessed'>Assessed</option>";
	                                			echo "<option value='".$rows['pattern_status']."' selected>Disable</option>";
	                                		}
	                                		else{
	                                			echo "<option value='Ready'>Ready to be Assessed</option>";
	                                			echo "<option value='Assessed'>Assessed</option>";
	                                			echo "<option value='Disable' selected>Disable</option>";
	                                		}
	                                		?>
	                                	</select>
	                                </div>
	                            </div>
	                            <div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_status">Assess on Version</label>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                            	<div class="form-group">
	                            		<select class="form-control" id="pattern_assess_version" name="pattern_assess_version">
	                            		<?php 
	                            			if(!empty($desc_by_pattern)){
	                            				foreach ($desc_by_pattern as $d) {
	                            					if($d['desc_version'] == $rows['pattern_assess_version']){
	                            						echo "<option value='".$d['desc_version']."' selected>".$d['desc_version']."</option>";
	                            					}
	                            					else{
	                            						echo "<option value='".$d['desc_version']."'>".$d['desc_version']."</option>";
	                            					}
	                            				}
	                            			}
	                            			else{
	                            				echo "<option value='1.0'>1.0</option>";
	                            			}
	                            		?>
	                            		</select>
	                            	</div>
	                            </div>
	                        </div>
	                        <div class="row">
	                        	<div class="col-md-2">
	                            	<div class="form-group">
	                                	<label for="pattern_status">Assess Limit Count</label>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                            	<div class="form-group">
	                            		<input type='text' class='form-control' id='pattern_assess_limit' name='pattern_assess_limit' value='<?php echo $rows['pattern_assess_limit']; ?>' placeholder="e.g. 10" required>
	                            		<small class="text text-muted">Enter '<span class="text-danger"><b>0</b></span>' if you want to <span class="text-danger"><b>'Unlimit'</b></span> the number of assessment</small>
	                            	</div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <?php if(!empty($rows)) { ?>
                            	<a href="<?php echo base_url()."cPattern/index"; ?>" class="btn btn-default">Go back</a>
                            <?php } else { ?>
                            	<input type="reset" class="btn btn-default" value="Reset" />
                            <?php } ?>
                        </div> 
	                </form>
                </div>
            </div>
        </div>
    </section>
</div>