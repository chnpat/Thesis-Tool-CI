<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Pattern
        <small>List</small>
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
		<div class="box box-primary">
	    	<div class="box-body table-responsive no-padding">
		    	<div class="panel panel-default panel-noborder">
					<div class="panel-body">
				    	<div class="col-md-4"></div>
				    	<div class="col-md-4"></div>
				    	<div class="col-md-4 text-right no-padding">
				    		<a href="<?php echo base_url(); ?>cPattern/pattern_detail" class="btn btn-success btn-sm">
				    			<i class="fa fa-plus"> Add a pattern</i>
				    		</a>
				    	</div>
					</div>
				</div>
	    		<table class="table table-hover">
	    			<tr class="info">
	    				<th class="col-md-1 text-center">Pattern ID</th>
	    				<th class="col-md-3">Name</th>
	    				<th class="col-md-2 text-center">Creator Name</th>
	    				<th class="col-md-1 text-center">Assess on Version</th>
	    				<th class="col-md-2 text-center">Status</th>
	    				<th class="col-md-3 text-center"></th>
	    			</tr>
	    			<?php 
	    			$this->load->model('mLogin');
	    			if(isset($rows)){
		    			foreach($rows as $row){ 
		    				echo "<tr>";
		    				echo "<td class='text-center'>".$row['pattern_id']."</td>";
		    				echo "<td>".$row['pattern_name']."</td>";
		    				echo "<td class='text-center'>".$this->mLogin->get_user_by_id($row['pattern_creator_id'])[0]['user_name']."</td>";
		    				echo "<td class='text-center'>".$row['pattern_assess_version']."</td>";
		    				switch ($row['pattern_status']) {
		    					case 'Ready':
		    						echo "<td class='text-center'><div class='label label-success lable-sm text-center'>"."Ready to be Assessed"."</div></td>";
		    						break;
		    					case 'Assessed':
		    						echo "<td class='text-center'><div class='label label-warning lable-sm text-center'>"."Pattern Assessed"."</div></td>";
		    						break;
		    					default:
		    						echo "<td class='text-center'><div class='label label-danger lable-sm text-center'>"."Assessment Disabled"."</div></td>";
		    						break;
		    				}
		    				echo "<td>"
		    		?>
		    			<a href="<?php echo base_url(); ?>cPattern/desc_version_list/<?php echo $row['pattern_id']; ?>" class="btn btn-primary btn-xs">
		    				<i class="fa fa-newspaper-o"></i> Description
		    			</a>
		    			<a href="<?php echo base_url(); ?>cPattern/pattern_detail/<?php echo $row['pattern_id']; ?>" class="btn btn-warning btn-xs">
		    				<i class="fa fa-edit"></i> Edit
		    			</a>
		    			<a href="javascript:void(0);" onclick="deleteThis('<?php echo $row['pattern_id']; ?>');" class="btn btn-danger btn-xs">
		    				<i class="fa fa-trash"></i> Delete
		    			</a>
		    		<?php
		    				echo "</td></tr>";
		    			} 
	    			} ?>
	    		</table>
				<script type="text/javascript">
				    var url="<?php echo base_url();?>";
				    function deleteThis(id){
				       	var r=confirm("Do you want to delete this?");
				        if (r==true) {
				          	window.location = url+"cPattern/delete_pattern/"+id;
				        } else {
				          return false;
				        }
				    }
				</script>
			</div>	
		</div>
    </section>
</div>