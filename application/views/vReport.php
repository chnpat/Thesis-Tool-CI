<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-bar-chart" aria-hidden="true"></i> Assessment
        <small>Report</small>
      </h1>
    </section>
    <section class="content">
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Assessment Report Generator</b></h4>
    		</div>
    		<div class="box-body">
    			<div class="col-12">
					<?php if($this->session->flashdata("report_msg")){ ?>
				    	<div class="alert alert-dismissible alert-success">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Saved!</strong> <?php echo $this->session->flashdata("report_msg"); ?>
						</div>
				    <?php } ?>
				    <?php if($this->session->flashdata("report_error")){ ?>
				    	<div class="alert alert-dismissible alert-danger">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>Error!</strong> <?php echo $this->session->flashdata("report_error"); ?>
						</div>
				    <?php } ?>
				</div>
    			<form role="form" id="generate_form" method="post" action="<?php echo base_url(); ?>cReport/generate_report/">
	    			<div class="col-md-6">
	    				<label for="pattern_name" class="margin">Pattern: </label>
	    				<select class="form-control margin" id="report_pattern" name="report_pattern">
	    					<?php foreach ($pattern_list as $pattern) {?>
	    						<option value="<?php echo $pattern['pattern_id']; ?>" <?php echo ($report['info']['pattern_id'] == $pattern['pattern_id'])?"selected='selected'":"";?> ><?php echo $pattern['pattern_id'];?>: <?php echo $pattern['pattern_name']; ?></option>
	    					<?php }?>
	    				</select>
	    			</div>
	    			<div class="col-md-6">
	    				<label for="description_version" class="margin">Version: </label>
	    				<div class="input-group margin">
	    					<input class="form-control" type="text" id="report_desc_version" name="report_desc_version" placeholder="e.g. 1.0" value="<?php echo ($report['info']['version'] != NULL)? "".$report['info']['version']:"";?>">
	    					<span class="input-group-btn">
	    						<input type="submit" class="btn bg-blue" value="Generate" />
	    					</span>
	    				</div>
	    				<small class="text text-primary margin">Leave the version <b class="text text-danger"><i>blank</i></b> to select all version</small>
	    			</div>
    			</form>
    		</div>
    	</div>
    	<?php if($report != NULL){ ?>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Assessment Timeline</b> for Pattern ID: <span class="text text-danger"><?php echo $report['info']['pattern_id']; ?></span><?php echo ($report['info']['version'] != NULL)? " on version <span class='text text-danger'>".$report['info']['version']."</span>": ""; ?> </h4>
    		</div>
    		<div class="box-body">
    			<ul class="timeline">
				    <li class="time-label">
				        <span class="bg-red">
				            Assessment History for Pattern ID: <?php echo $report['info']['pattern_id']; ?><?php echo ($report['info']['version'] != NULL)? " on version ".$report['info']['version']: ""; ?>
				        </span>
				    </li>
				    <?php 
				    if($report['info']['version'] != NULL){ ?>
					    <?php foreach ($report['data']['result'] as $m => $var) {?>
					    	<?php if($var != NULL) { ?>
						    	<?php foreach ($var as $index => $result) { ?>
						    	<li>
						    		<i class="fa fa-book bg-blue"></i>
						    		<div class="timeline-item">
							    		<h3 class="timeline-header">
		    							<?php 
		    							$this->load->model('mLogin');
		    							echo "<span class='text text-primary'><b>".$this->mLogin->get_user_by_id($result['assessor_id'])[0]['user_name']."</b></span> are assessed on version ".$result['desc_version']." of this pattern."; ?>
		    							</h3>
		    							<div class="timeline-body">
							    			<div class="box-group" id="accordion_<?=$result['assessor_id']?>_<?=$m?>">
							    				<div class="panel box box-primary">
							    					<div class="box-header with-border">
							    						<div>
							    							<a data-toggle="collapse" data-parent="#accordion_<?=$result['assessor_id']?>_<?=$m?>" href="#collapse_<?=$result['assessor_id']?>_<?=$m?>" aria-expanded="false" class="collapsed">
							    								<b><?php echo "".$report['data']['metric_list'][$m]['metric_name']." (".$report['data']['metric_list'][$m]['metric_abberv']."): "; ?></b><span class="text text-success"><?=$result['score']?>%</span>
							    							</a>
							    						</div>
							    					</div>
							    					<div id="collapse_<?=$result['assessor_id']?>_<?=$m?>" class="panel-collapse collapse" aria-expanded="false">
		    											<div class="box-body">
		    												<?php if($result['detail'] != NULL) { ?>
		    													<table class="table table-responsive table-striped table-bordered">
			    												<?php foreach ($result['detail'] as $k => $value) 
			    												{ ?>
			    													<?php if($m == "CRE") { ?>
			    														<tr>
			    															<td class="info"><b><u><?=$k ?></u></b></td>
			    															<td class="info"></td>
			    															<td class="info"></td>
			    														</tr>
			    													<?php } ?>
			    													<?php foreach ($report['data']['metric_list'][$m]['detail'] as $i => $da) 
			    													{ ?>
			    														<?php if($m == "CRE") 
			    														{ ?>
			    															<?php foreach ($value as $a => $cre_var) { ?>
			    																<?php if($cre_var['variable_id'] == $da['id']) { ?>
			    																	<tr>
				    																	<td><b><?=$da['variable_name']?></b></td>
					    																<td><?=$da['variable_description']?></td>
					    																<td>
					    																<?php if($da['variable_name'] != "Topic Score") { ?>
					    																	<span class="label label-primary">
					    																	<?=substr($cre_var['variable_score'],0,-3)?>
					    																<?php } else { ?>
					    																	<span class="label label-success">
					    																	<?=$cre_var['variable_score']?>%	
					    																<?php } ?>
					    																</span>
					    																</td>
			    																	</tr>
			    																
			    																<?php } ?>
			    															<?php } ?>
			    														<?php 
			    														} 
			    														else 
			    														{ ?>
			    															<?php if($value['variable_id'] == $da['id']) 
			    															{ ?>
			    																<?php if($m == "DKT") 
			    																{ ?>
			    																<tr>
			    																	<td><b><?=$da['variable_name']?></b></td>
				    																<td><?=$da['variable_description']?></td>
			    																	<td>
			    																	<?php if($value['variable_score'] == 0) { ?>
			    																		<span class="label label-danger"><?=$value['variable_score']?></span>
			    																	<?php } else { ?>
			    																		<span class="label label-success"><?=$value['variable_score']?></span>
			    																	<?php } ?>
			    																	</td>
			    																</tr>
			    																<?php 
			    																} 
			    																else 
			    																{ ?>
			    																<tr>
				    																<td><b><?=$da['variable_name']?></b></td>
				    																<td><?=$da['variable_description']?></td>
			    																	<td><span class="label label-success"><?=$value['variable_score']?></span></td>
			    																</tr>
			    																<?php 
			    																} 
			    															}
			    														}
			    													}
			    												} ?>
			    												</table>
		    												<?php } ?>
		    											</div>
		    										</div>
							    				</div>
							    			</div>
							    		</div>
						    		</div>
						    	</li>
						    	<?php } ?>
						    <?php } ?>
					    <?php } ?>
					<?php } else { ?>
						<?php foreach ($report['data']['result'] as $m => $var) {?>
							<?php if($var != NULL) { ?>
						    	<?php foreach ($var as $index => $result) { ?>
						    	<li>
						    		<i class="fa fa-book bg-blue"></i>
						    		<div class="timeline-item">
							    		<h3 class="timeline-header">
		    							<?php 
		    							$this->load->model('mLogin');
		    							echo "<span class='text text-primary'><b>".$this->mLogin->get_user_by_id($result['assessor_id'])[0]['user_name']."</b></span> are assessed on version <b>".$result['desc_version']."</b>";?>
		    							</h3>
		    							<div class="timeline-body">
							    			<div class="box-group" id="accordion_<?=$result['assessor_id']?>_<?=$m?>">
							    				<div class="panel box box-primary">
							    					<div class="box-header with-border">
							    						<div>
							    							<a data-toggle="collapse" data-parent="#accordion_<?=$result['assessor_id']?>_<?=$m?>" href="#collapse_<?=$result['assessor_id']?>_<?=$m?>" aria-expanded="false" class="collapsed">
							    								<b><?php echo "".$report['data']['metric_list'][$m]['metric_name']." (".$report['data']['metric_list'][$m]['metric_abberv']."): "; ?></b><span class="text text-success"><?=$result['score']?>%</span>
							    							</a>
							    						</div>
							    					</div>
							    					<div id="collapse_<?=$result['assessor_id']?>_<?=$m?>" class="panel-collapse collapse" aria-expanded="false">
		    											<div class="box-body">
		    												<?php if($result['detail'] != NULL) { ?>
		    													<table class="table table-responsive table-striped table-bordered">
			    												<?php foreach ($result['detail'] as $k => $value) 
			    												{ ?>
			    													<?php if($m == "CRE") { ?>
			    														<tr>
			    															<td class="info"><b><u><?=$k ?></u></b></td>
			    															<td class="info"></td>
			    															<td class="info"></td>
			    														</tr>
			    													<?php } ?>
			    													<?php foreach ($report['data']['metric_list'][$m]['detail'] as $i => $da) 
			    													{ ?>
			    														<?php if($m == "CRE") 
			    														{ ?>
			    															<?php foreach ($value as $a => $cre_var) { ?>
			    																<?php if($cre_var['variable_id'] == $da['id']) { ?>
			    																	<tr>
				    																	<td><b><?=$da['variable_name']?></b></td>
					    																<td><?=$da['variable_description']?></td>
					    																<td>
					    																<?php if($da['variable_name'] != "Topic Score") { ?>
					    																	<span class="label label-primary">
					    																	<?=substr($cre_var['variable_score'],0,-3)?>
					    																<?php } else { ?>
					    																	<span class="label label-success">
					    																	<?=$cre_var['variable_score']?>%	
					    																<?php } ?>
					    																</span>
					    																</td>
			    																	</tr>
			    																
			    																<?php } ?>
			    															<?php } ?>
			    														<?php 
			    														} 
			    														else 
			    														{ ?>
			    															<?php if($value['variable_id'] == $da['id']) 
			    															{ ?>
			    																<?php if($m == "DKT") 
			    																{ ?>
			    																<tr>
			    																	<td><b><?=$da['variable_name']?></b></td>
				    																<td><?=$da['variable_description']?></td>
			    																	<td>
			    																	<?php if($value['variable_score'] == 0) { ?>
			    																		<span class="label label-danger"><?=$value['variable_score']?></span>
			    																	<?php } else { ?>
			    																		<span class="label label-success"><?=$value['variable_score']?></span>
			    																	<?php } ?>
			    																	</td>
			    																</tr>
			    																<?php 
			    																} 
			    																else 
			    																{ ?>
			    																<tr>
				    																<td><b><?=$da['variable_name']?></b></td>
				    																<td><?=$da['variable_description']?></td>
			    																	<td><span class="label label-success"><?=$value['variable_score']?></span></td>
			    																</tr>
			    																<?php 
			    																} 
			    															}
			    														}
			    													}
			    												} ?>
			    												</table>
		    												<?php } ?>
		    											</div>
		    										</div>
							    				</div>
							    			</div>
							    		</div>
						    		</div>
						    	</li>
						    	<?php } ?>
						    <?php } ?>
						<?php } ?>
					<?php } ?>
	    			<li><i class="fa fa-clock-o bg-grey"></i></li>
    			</ul>
    		</div>
    		<div class="box-footer">

    		</div>
    	</div>
    	<?php } ?>
    </section>
</div>