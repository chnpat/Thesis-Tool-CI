<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> User
        <small>Management</small>
      </h1>
    </section>
    <section class="content">
    	<div class="col-12">
			<?php if($this->session->flashdata("user_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("user_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("user_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("user_error"); ?>
				</div>
		    <?php } ?>
		</div>
	    <div class="box box-danger">
	    	<div class="box-body table-responsive">
		    	<div class="panel panel-default panel-noborder">
					<div class="panel-body">
				    	<div class="col-md-8"></div>
				    	<div class="col-md-4 text-right no-padding">
			    			<a href="<?php echo base_url(); ?>cUserManagement/user_detail" class="btn btn-success btn-sm">
			    				<i class="fa fa-plus"> Add a user</i>
			    			</a>
				    	</div>
					</div>
				</div>
				<div class="panel-body">
		    		<table class="table table-striped">
		    			<tr style="background-color: #abb0ba;">
		    				<th class="col-md-1">No.</th>
		    				<th class="col-md-3">Username</th>
		    				<th class="col-md-3">Email Address</th>
		    				<th class="col-md-2">Role</th>
		    				<th class="col-md-1">Status</th>
		    				<th class="col-md-2"></th>
		    			</tr>
		    			<?php 
		    			if(!empty($rows)){
			    			foreach($rows as $row){ 
			    				echo "<tr>";
			    				echo "<td>".$row['id']."</td>";
			    				echo "<td>".$row['user_name']."</td>";
			    				echo "<td>".$row['user_email']."</td>";
			    				
			    				if($row['user_role'] == 'Admin'){
			    					echo "<td><span class='label label-info'>System Administrator</span></td>";
			    				}
			    				else if($row['user_role'] == 'Assessor'){
			    					echo "<td><span class='label label-warning'>Pattern Assessor</span></td>";
			    				}
			    				else{
			    					echo "<td><span class='label label-primary'>Pattern Developer</span></td>";
			    				}

			    				if($row['user_status']){
			    					echo "<td><span class='label label-success'>Active</span></td>";
			    				}
			    				else{
			    					echo "<td><span class='label label-danger'>Disable</span></td>";	
			    				}
			    				echo "<td>";
			    		?>
			    			<a href="<?php echo base_url(); ?>cUserManagement/user_detail/<?php echo $row['id']; ?>" class="btn btn-warning btn-xs">
			    				<i class="fa fa-edit"></i> Edit
			    			</a>
			    			<a href="javascript:void(0);" onclick="deleteThis(<?php echo $row['id']; ?>);" class="btn btn-danger btn-xs">
			    				<i class="fa fa-trash"></i> Delete
			    				</a>
			    		<?php
			    				echo "</td></tr>";
			    			} 
		    			}
		    			else { ?>
		    			<tr class="active">
		    				<td></td>
		    				<td></td>
		    				<td class="text-center">No Data Provided</td>
		    				<td></td>
		    				<td></td>
		    				<td></td>
		    			</tr>
		    			<?php } ?>
		    		</table>
					<script type="text/javascript">
					    var url="<?php echo base_url();?>";
					    function deleteThis(id){
					       	var r=confirm("Do you want to delete this?");
					        if (r==true) {
					          	window.location = url+"cUserManagement/delete_user/"+id;
					        } else {
					          return false;
					        }
					    }
					</script>
				</div>
	    	</div>
	    </div>
    </section>
</div>