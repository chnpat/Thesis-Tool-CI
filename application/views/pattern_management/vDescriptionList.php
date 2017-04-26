<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Pattern Description
        <small>List</small>
      </h1>
    </section>
    <section class="content">
		<div class="col-12">
			<?php if($this->session->flashdata("desc_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("desc_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("desc_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("desc_error"); ?>
				</div>
		    <?php } ?>
		</div>
		<div class="box box-primary">
	    	<div class="box-body table-responsive">
		    	<div class="panel panel-default panel-noborder">
					<div class="panel-body">
				    	<div class="col-md-4">
				    	<?php 
				    		if(!empty($pat_id)){
				    			echo "Pattern Description for Pattern ID: <span class='text-danger'>".$pat_id."</span>";
				    		}
				    	?>
				    	</div>
				    	<div class="col-md-4"></div>
				    	<div class="col-md-4 text-right no-padding">
				    		<a href="<?php echo base_url(); ?>cPatternDesc/desc_detail/<?php echo $pat_id; ?>" class="btn btn-success btn-sm">
				    			<i class="fa fa-plus"> Add a pattern description</i>
				    		</a>
				    	</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php 
					if(!empty($rows)){
						foreach ($rows as $row) {
							echo "<div class='col-lg-3 col-xs-6'>";
							if($row['desc_assess_count'] > 0){ 
								echo "<div class='small-box bg-aqua'>";
							}
							else { 
				
								echo "<div class='small-box bg-yellow'>";
							}
							echo "<div class='inner'>";
							echo "<small>Ver.</small>";
							echo "<h3>".$row['desc_version']."</h3>";
							echo "</div>";
							echo "<div class='icon'><i class='fa fa-files-o'></i></div>"; 
				?>
							<a href="<?php echo base_url(); ?>cPatternDesc/desc_detail/<?php echo $pat_id;?>/<?php echo $row['id']; ?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
				<?php		echo "</div>";
							echo "</div>";
						}
					} 
				?>
			</div>
		</div>
	</section>
</div>