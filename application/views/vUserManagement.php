<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> User
        <small>Management</small>
      </h1>
    </section>
    <section class="container">
    <?php if($this->session->flashdata('user_manage_msg')){ ?>
    	<div class="alert alert-dismissible alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Saved!</strong> <?php echo $this->session->flashdata('user_manage_msg'); ?>
		</div>
    <?php } ?>
    <?php if($this->session->flashdata('user_manage_error')){ ?>
    	<div class="alert alert-dismissible alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Error!</strong> <?php echo $this->session->flashdata('user_manage_error'); ?>
		</div>
    <?php } ?>
    <div class="col-xs-12">
	    <div class="box box-primary">
	    	<div class="box-body table-responsive no-padding">
		    	<div class="panel panel-default panel-noborder">
					<div class="panel-body">
				    	<div class="col-md-4"></div>
				    	<div class="col-md-4"></div>
				    	<div class="col-md-4 text-right">
				    		<a href="<?php echo base_url(); ?>cUserManagement/user_detail" class="btn btn-success btn-sm "><i class="fa fa-plus"> Add a user</i></a>
				    	</div>
					</div>
				</div>
	    		<table class="table table-hover">
	    			<tr class="info">
	    				<th class="col-md-1">ID</th>
	    				<th class="col-md-3">Username</th>
	    				<th class="col-md-3">email</th>
	    				<th class="col-md-2">role</th>
	    				<th class="col-md-1">status</th>
	    				<th class="col-md-2"></th>
	    			</tr>
	    			<?php 
	    			if(isset($rows)){
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
		    			<a href="<?php echo base_url(); ?>cUserManagement/user_detail/<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
		    				<i class="fa fa-edit"></i> Edit
		    			</a>
		    			<a href="javascript:void(0);" onclick="deleteThis(<?php echo $row['id']; ?>);" class="btn btn-danger">
		    				<i class="fa fa-trash"></i> Delete
		    				</a>
		    		<?php
		    				echo "</tr>";
		    			} 
	    			}?>
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
</div>