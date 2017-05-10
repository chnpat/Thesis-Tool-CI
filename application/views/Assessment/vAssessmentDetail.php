<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-check-square-o" aria-hidden="true"></i>Assessment Detail<!-- <?php echo ($result != NULL)? "Edit Assessment":"New Assessment"; ?> -->
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
	        				<!-- <h4><b><?= $metrics['metric_name']; ?> (<?= $metrics['metric_abberv']; ?>)</b></h4> -->
	        			</div>
	        		</div>
	        		<div class="row">
	        			<div class="col-md-10">
	        				<!-- <?= $metrics['metric_description'];?> -->
	        			</div>
	        		</div>
	        		<!-- <div class="row">
	        			<div class="col-md-6">
	        				<div class="well well-sm pre-scrollable">
	        					<?php foreach (array('Pattern Name',
	        						'Pattern Classification',
	        						'Intent',
	        						'Also Known As',
	        						'Motivation',
	        						'Applicability',
	        						'Structure',
	        						'Participants',
	        						'Collaborations',
	        						'Consequences',
	        						'Implementation',
	        						'Sample Code',
	        						'Known Uses',
	        						'Related Patterns') as $topic ) {
	        							echo "<b>".$topic."</b><br/>";
	        							switch ($topic) {
	        									case 'Pattern Name':
	        										echo $pattern['pattern_name']."<br/><br/>";
	        										break;
	        									case 'Pattern Classification':
			            							echo $pattern_description['desc_classification']."<br/><br/>";
			            							break;
			            						case 'Also Known As':
			            							echo $pattern_description['desc_aka']."<br/><br/>";
			            							break;
			            						case 'Known Uses':
			            							echo $pattern_description['desc_known_uses']."<br/><br/>";
			            							break;
			            						case 'Sample Code':
			            							echo $pattern_description['desc_sample_code']."<br/><br/>";
			            							break;
			            						case 'Related Patterns':
			            							echo $pattern_description['desc_related_pattern']."<br/><br/>";
			            							break;
			            						default:
			            							echo $pattern_description["desc_".strtolower($topic)]."<br/><br/>";
			            							break;
	        								}	
	        						}
	        					?>
	        				</div>
	        			</div>
	        			<div class="col-md-6">
	        				<div class="well well-sm pre-scrollable">
	        					<?php 
	        					if($pattern_diagram != NULL){
		        					foreach ($metrics['detail'] as $d) { 
		        						$diag_arr = explode('|', $d['variable_diagram']);
		        						if(!empty($diag_arr)){ 
		        							$count = 0;
		        							foreach ($diag_arr as $dia) {
		        								if($pattern_diagram[$dia."_topic"]){
		        									$count++;
		        								}
		        							}
		        							if($count == count($diag_arr)){ ?>
				        						<div class="list-group">
													<div class="list-group-item">
														<h4 class="list-group-item-heading"><b><?=$d['variable_name'];?></b></h4>
														<p class="list-group-item-text"><?=$d['variable_description'];?></p>
														<input type="text" id="<?php echo $d['id']; ?>" name="<?php echo $d['id']; ?>"/>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
		        					<?php }
		        					} 
		        				else { ?>
		        					<div class="list-group">
		        						<div class="list-group-item">
		        							<h4 class="list-group-item-heading">Not applicable criteria to this pattern.</h4>
		        						</div>
		        					</div>
		        				<?php } ?>
	        				</div>
	        			</div>
	        		</div> -->
		            <!-- <?php if($result != NULL) { ?>
		            	<?php foreach ($metrics['detail'] as $d) {?>
			            	<div class="row">
			            		<div class="col-md-6">
			            			<?php echo "<b>".$d['variable_name']."</b>&nbsp;".$d['variable_description']."<br/>";?>
			            		</div>
			            		<div class="col-md-6">
			            			<div><b>Associated Pattern Description Topic:</b></div>
			            			<div class="well well-sm pre-scrollable">
			            				<div style="height: 100px;">
			            				<?php if($d['variable_assess_on'] != NULL) { 
			            					echo "<b>".$d['variable_assess_on']."</b><br/>";
			            					switch ($d['variable_assess_on']) {
			            						case 'Pattern Name and Classification':
			            							echo $pattern['pattern_name']." (".$pattern_description['desc_classification'].")";
			            							break;
			            						case 'Also Known As':
			            							echo $pattern_description['desc_aka'];
			            							break;
			            						case 'Known Uses':
			            							echo $pattern_description['desc_known_uses'];
			            							break;
			            						case 'Sample Code':
			            							echo $pattern_description['desc_sample_code'];
			            							break;
			            						case 'Related Patterns':
			            							echo $pattern_description['desc_related_pattern'];
			            							break;
			            						default:
			            							echo "desc_".strtolower($d['variable_assess_on']);
			            							break;
			            					}
			            				} else {
			            					$diag_arr = explode('|', $d['variable_diagram']);
			            					$txt = "";
			            					$count = 0;
			            					foreach ($diag_arr as $dia) {
			            						$top = $pattern_diagram[0][$dia.'_topic'];
			            						if($top != ""){
			            							$txt = $txt."<b>".$top."</b><br/>";
				            						switch ($top) {
				            							case 'Pattern Name and Classification':
					            							$txt = $txt.$pattern['pattern_name']." (".$pattern_description['desc_classification'].")";
					            							break;
					            						case 'Also Known As':
					            							$txt = $txt.$pattern_description['desc_aka'];
					            							break;
					            						case 'Known Uses':
					            							$txt = $txt.$pattern_description['desc_known_uses'];
					            							break;
					            						case 'Sample Code':
					            							$txt = $txt.$pattern_description['desc_sample_code'];
					            							break;
					            						case 'Related Patterns':
					            							$txt = $txt.$pattern_description['desc_related_pattern'];
					            							break;
					            						default:
					            							$txt = $txt.$pattern_description["desc_".strtolower($top)];
					            							break;
				            						}
				            						$txt = $txt."<br/>";
				            						$count++;
			            						}
			            					}
			            					echo ($count == count($diag_arr))? $txt: "";
			            				}
			            				?>
			            				</div>
			            			</div>
			            		</div>
			            	</div>
		            	<?php } ?>
		            <?php } else { ?>
		            	<?php foreach ($metrics['detail'] as $d) {?>
			            	<div class="row">
			            		<div class="col-md-6">
			            			<?php echo "<b>".$d['variable_name']."</b>&nbsp;".$d['variable_description']."<br/>";?>
			            		</div>
			            		<div class="col-md-6">
			            			<div><b>Associated Pattern Description Topic:</b></div>
			            			<div class="well well-sm pre-scrollable">
			            				<div style="height: 100px;">
			            				<?php for($x = 0; $x <= 100; $x++) { echo $x."<br/>"; } ?>
			            				</div>
			            			</div>
			            		</div>
			            	</div>
		            	<?php } ?>
		            <?php } ?> -->
	        	</div>
	        </div>
      	</div>
    </div>
  </section>
</div>