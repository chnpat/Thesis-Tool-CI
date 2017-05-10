<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-check-square-o" aria-hidden="true"></i> Quality Attributes
        <small>Choices</small>
      </h1>
    </section>
    <section class="content">
    	<div class="box box-danger">
	    	<div class="box-body table-responsive no-padding">
	    		<div class="panel panel-default panel-noborder">
					<div class="panel-body">
    					<?php echo "Pattern ID: <span class='text-danger'><b>".$pattern['pattern_id']." - ".$pattern['pattern_name']."</b></span> on version: <span class='text-danger'><b>".$pattern_description['desc_version']."</b></span>";?>
					</div>
					<legend></legend>
					<div class="panel-body">
						<table class="table table-striped">
							<thead>
								<tr style="background-color: #abb0ba;">
									<th class="col-md-2 text-center">Quality Attribute</th>
									<th class="col-md-1 text-center">Quality-Carrying Property</th>
									<th class="col-md-3 text-center">Metric Name</th>
									<th class="col-md-2 text-center">Status</th>
									<th class="col-md-2 text-center">Score</th> 
									<th class="col-md-2 text-center"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach (array('DKT', 'CSD', 'PAP', 'CRE') as $metr) { ?>
								<tr>
									<td><?= $metrics[$metr]['metric_QA'];?></td>
									<td class="text-center"><?= $metrics[$metr]['metric_QCP'];?></td>
									<td><?= $metrics[$metr]['metric_name'];?> (<?= $metrics[$metr]['metric_abberv']?>)</td>
									<td class="text-center">
										<?php 
										echo ($result[$metr] == NULL)? "<div class='label label-danger'>Unassessed</div>": "<div class='label label-success'>Assessed</div>";
										?>
									</td>
									<td class="text-center">
										<?php if($result[$metr] != NULL) {  ?> 
										<div class="progress progress-striped active">
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
										<?php } else { echo "-"; }?>
									</td>
									<td>
										<?php if($result[$metr] != NULL) { ?>
											<a href="<?php echo base_url(); ?>/cAssess/assess_detail/<?php echo $pattern['pattern_id']; ?>/<?php echo $pattern['pattern_assess_version']; ?>/<?php echo $metr; ?>" class="btn btn-primary btn-xs"><i class="fa fa-book"></i> Detail</a>
										<?php } else { ?>
											<a href="<?php echo base_url(); ?>/cAssess/assess_detail/<?php echo $pattern['pattern_id']; ?>/<?php echo $pattern['pattern_assess_version']; ?>/<?php echo $metr; ?>" class="btn btn-warning btn-xs"><i class="fa fa-check-square-o"></i> Assess</a>
										<?php } ?>
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