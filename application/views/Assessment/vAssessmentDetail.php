<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-check-square-o" aria-hidden="true"></i> <?php echo ($result != NULL)? "Edit Assessment":"New Assessment"; ?>
      <small>Details</small>
    </h1>
  </section>
  <section class="content">
  	<div class="box box-danger">
    	<div class="box-body table-responsive no-padding">
    		<div class="panel panel-default panel-noborder">
				<div class="panel-body">
					<?php echo "Pattern ID: <span class='text-danger'><b>".$pattern['pattern_id']." - ".$pattern['pattern_name']."</b></span> on version: <span class='text-danger'><b>".$description['desc_version']."</b></span>";?>
				</div>
				<legend></legend>
	        	<div class="panel-body">
	        		<div class="row">
	        			<div class="col-md-12">
	        				<h4><b><?= $result[$metr]['metric_name']; ?> (<?= $result[$metr]['metric_abberv']; ?>)</b></h4>
	        			</div>
	        		</div>
	        		<div class="row">
	        			<div class="col-md-10">
	        				<?= $result[$metr]['metric_description'];?>
	        			</div>
	        		</div>
	        		<legend></legend>
	        		<div class="row">
        				<div class="col-md-6">
        					<b><u>Pattern Description</u></b><br/><br/><br/>
        					<div class="well well-sm pre-scrollable">
        						<?php 
        						$detail_only = array_filter($description, function($v, $k){
        							if(substr($k, 0, 5) == "desc_"){
        								if($k == "desc_version" || $k == "desc_assess_on" || $k == "desc_assess_count"){
        									return false;
        								}
        								return true;
        							}
        							return false;
        						}, ARRAY_FILTER_USE_BOTH);
        						echo "<b>Pattern Name:</b> ".$pattern['pattern_name']."<br/>";
        						foreach ($detail_only as $key => $value) {
        							$title = substr(str_replace('_', ' ', $key), 5);
        							if( $title == "aka"){
        								echo "<b>Also Known As:</b><br/>";
        							}
        							else{
	        							$title_arr = explode(' ', $title, 5);
	        							foreach ($title_arr as $k => $piece) {
	        								$title_arr[$k] = ucfirst($piece);
	        							}
	        							echo "<b>".implode(' ', $title_arr).":</b><br/>";
        							}
        							echo "<div class='text-justify'>";
        							echo ($value == "")?"-":$value;
        							echo "</div>";
        							echo "<br/>";
        						}
        						?>
        					</div>
        					<?php if ($metr == "PAP") { ?>
	        					<b><u>Example Design Image</u></b><br/><br/>
	        					<?php if (!empty($design_file)) { ?>
		        					<div class="well well-sm pre-scrollable">
		        						<?php
		        						$count = 1; 
		        						foreach ($design_file as $file): ?>
		        							<?php echo "Project ".$count." :<br/>"; ?>
		        							<img src="<?php echo base_url(); ?>images/DesignImg/<?php echo $pattern['pattern_id']; ?>/<?php echo $description['id']; ?>/<?php echo $file['name'];?>" class="img-thumbnail">	
		        						<?php endforeach ?>
		        					</div>
		        				<?php } else { ?>
		        					<div class="well well-sm text-center">
		        						No Design Images are provided. 
		        					</div>
		        				<?php } ?>
	        				<?php } ?>
        				</div>
        				<div class="col-md-6">
        					<b><u>Assessment Criteria</u></b><br/>
    						<div class='label label-danger'>Please enter the scores and scroll down until reach the add/save button.</div><br/><br/>
        					<div class="panel panel-body pre-scrollable" style="<?php echo ($metr == "PAP")? 'max-height: 750px;': ''; ?>">
        						<form  role="form" id="assessPattern" action="<?php echo base_url();?>cAssess/update_result_proc/<?= $pattern['pattern_id'];?>/<?= $description['desc_version'];?>/<?= $metr; ?>/" method="post" role="form">
	        						<?php 
	        						$all_criteria = 0;
	        						foreach ($result[$metr]['detail'] as $key => $value) {
	        							if($value['variable_diagram'] != "" OR $value['variable_diagram'] != NULL){
		        							$diag_arr = explode('|', $value['variable_diagram']);
		        							$c = 0;
		        							foreach ($diag_arr as $diag) {
		        								if($description['is_'.$diag]){
		        									$c++;
		        								}
		        							}
		        							if($c == count($diag_arr)){
		        								echo "<span class='text text-info'><b>Criteria ".($key+1).":</b></span><br/>";
			        							echo "<div class='col-md-12'>";
			        							echo "<b>Name: ".$value['variable_name']."</b><br/>";
			        							echo "<b>Description:</b> ".$value['variable_description']."";
			        							echo "</div>";
			        							echo "<div class='col-md-2'><div class='form-group text-left'><label for='".$value['id']."_var'>Score:</label></div></div>";
			        							echo "<div class='col-md-10'>";
		        								echo "<div class='form-group'>";
			        							if(array_key_exists('var_score', $value)){
			        								echo "<input id='".$value['id']."_var' name='".$value['id']."_var' type='text' value='".(int)$value['var_score']."' class='form-control' required />";
			        							}
			        							else{
			        								echo "<input id='".$value['id']."_var' name='".$value['id']."_var' type='text' class='form-control' required />";	
			        							}
			        							echo "</div></div><br/><br/><legend></legend>";
			        							$all_criteria++;
		        							}
	        							}
	        							else{
		        							
		        							echo "<span class='text text-info'><b>Criteria ".($key+1).":</b></span><br/>";
		        							echo "<div class='col-md-12'>";
		        							echo "<b>Name: ".$value['variable_name']."</b><br/>";
		        							echo "<b>Description:</b> ".$value['variable_description']."";
		        							echo "</div>";
		        							echo "<div class='col-md-2'><div class='form-group text-left'><label for='".$value['id']."_var'>Score:</label></div></div>";
		        							echo "<div class='col-md-10'>";
		        							echo "<div class='form-group'>";
		        							if(array_key_exists('var_score', $value)){
		        								echo "<input id='".$value['id']."_var' name='".$value['id']."_var' type='text' value='".(int)$value['var_score']."' class='form-control' required />";
		        							}
		        							else{
		        								echo "<input id='".$value['id']."_var' name='".$value['id']."_var' type='text' class='form-control' required />";	
		        							}
		        							echo "</div></div><br/><br/><legend></legend>";
		        							$all_criteria++;
	        							}        							
	        						}
	        						if($all_criteria == 0){
	        							echo "<div class='well well-sm text-center'>No criteria are applicable</div>";
	        						}
	        						else{ ?>
	        							<input type="submit" value="Save/Update" class="btn btn-warning btn-sm" />
	        						<?php }?>
        						</form>
        					</div>
        				</div>
	        		</div>
	        	</div>
	        </div>
      	</div>
    </div>
  </section>
</div>