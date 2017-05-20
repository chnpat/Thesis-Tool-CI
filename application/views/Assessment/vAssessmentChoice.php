<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-check-square-o" aria-hidden="true"></i> Quality Attributes
        <small>Choices</small>
      </h1>
    </section>
    <section class="content">
    	<div class="col-12">
			<?php if($this->session->flashdata("choice_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("choice_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("choice_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("choice_error"); ?>
				</div>
		    <?php } ?>
		</div>
    	<div class="box box-danger">
	    	<div class="box-body table-responsive no-padding">
	    		<div class="panel panel-default panel-noborder">
					<div class="panel-body">
    					<?php echo "Pattern ID: <span class='text-danger'><b>".$pattern['pattern_id']." - ".$pattern['pattern_name']."</b></span> on version: <span class='text-danger'><b>".$pattern_description['desc_version']."</b></span>";?>
					</div>
					<legend></legend>
					<div class="panel-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr style="background-color: <?=TBL_GREY?>;">
									<th class="col-md-2 hidden-sm hidden-xs text-center">Quality Attribute</th>
									<th class="col-md-1 hidden-sm hidden-xs text-center">Quality-Carrying Property</th>
									<th class="col-md-3 col-sm-4 col-xs-4 text-center">Metric Name</th>
									<th class="col-md-2 col-sm-2 col-xs-2 text-center">Status</th>
									<th class="col-md-2 col-sm-4 col-xs-4 text-center">Score</th> 
									<th class="col-md-2 col-sm-2 col-xs-2 text-center"></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach (array('DKT', 'CSD', 'PAP', 'CRE') as $metr) { ?>
								<tr>
									<td class="col-md-2 hidden-sm hidden-xs"><?= $metrics[$metr]['metric_QA'];?></td>
									<td class="col-md-1 hidden-sm hidden-xs text-center"><?= $metrics[$metr]['metric_QCP'];?></td>
									<td class="col-md-3 col-sm-4 col-xs-4"><?= $metrics[$metr]['metric_name'];?> (<?= $metrics[$metr]['metric_abberv']?>)</td>
									<td class="col-md-2 col-sm-2 col-xs-2 text-center">
										<?php 
										echo ($result[$metr] == NULL)? "<div class='label label-danger'>Unassessed</div>": "<div class='label label-success'>Assessed</div>";
										?>
									</td>
									<td class="col-md-2 col-sm-4 col-xs-4 text-center">
										<?php if($result[$metr] != NULL) {  ?> 
										<div class="hidden-sm hidden-xs progress progress-striped active">
											<?php 
												$score = $result[$metr]['score']; 
												$color = "progress-bar progress-bar-";
												

												if($metr == "CRE"){
													if($score < 30){
														$color = $color."danger";
													}
													else if($score <= 60){
														$color = $color."success";
													}
													else {
														$color = $color."warning";
													}
												}
												else{
													if($score >= 90){
														$color = $color."success";
													} else if ($score >= 50){
														$color = $color."warning";
													} else {
														$color = $color."danger";
													}
												}

												echo "<div class='".$color."' style='width:".$score."%;'><span style='color: #000000;'>".$score."%</span></div>";

											?>
										</div>
										<div class="hidden-md hidden-lg">
											<?php
											$color = "badge bg-";
											if($metr == "CRE"){
												if($score < 30){
													$color = $color."red";
												}
												else if($score <= 60){
													$color = $color."green";
												}
												else {
													$color = $color."yellow";
												}
											}
											else{
												if($score >= 90){
													$color = $color."green";
												} else if ($score >= 50){
													$color = $color."yellow";
												} else {
													$color = $color."green";
												}
											}

											echo "<span class='".$color."'>".$score."%</span>";
											?>
										</div>
										<?php } else { echo "-"; }?>
									</td>
									<td>
										<div>
											<?php if($result[$metr] != NULL) { ?>
												<a href="<?php echo base_url(); ?>/cAssess/update_detail/<?php echo $pattern['pattern_id']; ?>/<?php echo $pattern['pattern_assess_version']; ?>/<?php echo $metr; ?>" class="btn bg-purple btn-xs"><i class="fa fa-book"></i> Detail</a>
											<?php } else { ?>
												<a href="<?php echo base_url(); ?>/cAssess/assess_detail/<?php echo $pattern['pattern_id']; ?>/<?php echo $pattern['pattern_assess_version']; ?>/<?php echo $metr; ?>" class="btn bg-orange btn-xs"><i class="fa fa-check-square-o"></i> Assess</a>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
    </section>
</div>