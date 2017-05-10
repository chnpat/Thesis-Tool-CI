<div class="content-wrapper">
	<section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Pattern Description
        <small>Detail</small>
      </h1>
    </section>
    <section class="content">
    	<div class="col-12">
			<?php if($this->session->flashdata("desc_detail_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("desc_detail_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("desc_detail_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("desc_detail_error"); ?>
				</div>
		    <?php } ?>
		</div>
		<div class="row">
            <!-- left column -->
            <div class="col-md-12">
              	<!-- general form elements -->
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Add/Edit Pattern Description Details</h3>
                    </div><!-- /.box-header -->
                    <ul class="nav nav-tabs">
                    	<li class="<?php echo ($tabs == NULL)?'active':''; ?>"><a href="#desc_form" data-toggle="tab" aria-expanded="false" class="text text-danger">Description Form</a></li>
                    	<?php if(!empty($rows)) { ?>
                    	<li class="<?php echo ($tabs == true)? 'active': ''; ?>"><a href="#desc_img" data-toggle="tab" aria-expanded="false" class="text text-danger">Image Upload</a></li>
                    	<?php } ?>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                    	<div class="tab-pane fade <?php echo ($tabs == NULL)?'active in':''; ?>" id="desc_form">
		                    <form role="form" id="addPatternDesc" action="
			                    <?php if(empty($rows)) { 
			                    		echo base_url(); ?>cPatternDesc/add_desc/<?=$pat_id; ?>
			                    <?php } else {
			                    		echo base_url(); ?>cPatternDesc/edit_desc/<?=$rows['id']; ?>/<?=$pat_id; ?>
			                    <?php } ?>" method="post" role="form">
			                    <div class="box-body">
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                                	<label for="desc_version">Description Version No.</label>
			                                </div>
			                    		</div>
			                    		<div class="col-md-2">
			                    			<div class="form-group">
			                    				<?php if(empty($rows)){ ?>
			                                		<input type="text" class="form-control" id="desc_version" name="desc_version" value="<?php echo $rows['desc_version']; ?>" placeholder="e.g. 1.0" required>
			                                	<?php } else { ?>
			                                		<input type="text" class="form-control" id="desc_version" name="desc_version" value="<?php echo $rows['desc_version']; ?>" readonly required>
			                                	<?php } ?>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                                	<label for="desc_version">Pattern Name</label>
			                                </div>
			                    		</div>
			                    		<div class="col-md-4">
			                    			<div class="form-group">
			                    				<?php 
			                    				$this->load->model('mPattern');
			                    				$name = $this->mPattern->get_pattern($pat_id)['pattern_name'];
			                    				?>
			                                	<input type="text" class="form-control" id="pat_name" name="pat_name" value="<?php echo $name; ?>" readonly>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_classification">Pattern Classification</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-6">
			                    			<div class="form-group">
			                    				<input type="text" class="form-control" id="desc_classification" name="desc_classification" value="<?php echo $rows['desc_classification']; ?>">
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_aka">Also Known As</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-6">
			                    			<div class="form-group">
			                    				<input type="text" class="form-control" id="desc_aka" name="desc_aka" value="<?php echo $rows['desc_aka']; ?>">
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_intent">Intent</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_intent" id="desc_intent"><?php echo $rows['desc_intent'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_motivation">Motivation</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_motivation" id="desc_motivation"><?php echo $rows['desc_motivation'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_applicability">Applicability</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_applicability" id="desc_applicability"><?php echo $rows['desc_applicability'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_structure">Structure</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_structure" id="desc_structure"><?php echo $rows['desc_structure'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_participants">Participants</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_participants" id="desc_participants"><?php echo $rows['desc_participants'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_collaborations">Collaborations</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_collaborations" id="desc_collaborations"><?php echo $rows['desc_collaborations'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_consequences">Consequences</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_consequences" id="desc_consequences"><?php echo $rows['desc_consequences'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_implementation">Implementation</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_implementation" id="desc_implementation"><?php echo $rows['desc_implementation'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_sample_code">Sample Code</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_sample_code" id="desc_sample_code"><?php echo $rows['desc_sample_code'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_known_uses">Known Uses</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_known_uses" id="desc_known_uses"><?php echo $rows['desc_known_uses'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_related_pattern">Related Patterns</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group">
			                    				<textarea class="ckeditor" name="desc_related_pattern" id="desc_related_pattern"><?php echo $rows['desc_related_pattern'];?></textarea>
			                    			</div>
			                    		</div>
			                    	</div>
			                    	<div class="row">
			                    		<div class="col-md-3">
			                    			<div class="form-group">
			                    				<label for="desc_diagram">Diagram Contained</label>
			                    			</div>
			                    		</div>
			                    		<div class="col-md-9">
			                    			<div class="form-group col-md-6">
			                    				<div class="checkbox">
			                    					<label>
			                    						<input type="checkbox" name="is_UCD" id="is_UCD" value="Y" <?php echo ($rows['is_UCD'] == true)? "checked=''": ""; ?> />Use Case Diagram
			                    					</label>
			                    				</div>
			                    			</div>
			                    			<div class="form-group col-md-6">
			                    				<div class="checkbox">
			                    					<label>
			                    						<input type="checkbox" name="is_AD" id="id_AD" value="Y" <?php echo ($rows['is_AD'] == true)? "checked=''": ""; ?> />Activity Diagram
			                    					</label>
			                    				</div>
			                    			</div>
			                    			<div class="form-group col-md-6">
			                    				<div class="checkbox">
			                    					<label>
			                    						<input type="checkbox" name="is_CD" id="is_CD" value="Y" <?php echo ($rows['is_CD'] == true)? "checked=''": ""; ?> />Class Diagram
			                    					</label>
			                    				</div>
			                    			</div>
			                    			<div class="form-group col-md-6">
			                    				<div class="checkbox">
			                    					<label>
			                    						<input type="checkbox" name="is_SD" id="id_SD" value="Y" <?php echo ($rows['is_SD'] == true)? "checked=''": ""; ?> />Sequence Diagram
			                    					</label>
			                    				</div>
			                    			</div>
			                    			<div class="form-group col-md-6">
			                    				<div class="checkbox">
			                    					<label>
			                    						<input type="checkbox" name="is_BSM" id="is_BSM" value="Y" <?php echo ($rows['is_BSM'] == true)? "checked=''": ""; ?> />Behavioral State Machine
			                    					</label>
			                    				</div>
			                    			</div>
			                    		</div>
			                    	</div>
			                    </div>
			                    <div class="box-footer">
		                            <input type="submit" class="btn btn-primary" value="Submit" />
		                            <?php if(!empty($rows)) { ?>
		                            	<a href="<?php echo base_url()."cPatternDesc/index/".$pat_id; ?>" class="btn btn-default">Go back</a>
		                            	<a href="javascript:void(0);" onclick="deleteThis('<?php echo $rows['id']; ?>');" class="btn btn-danger">
		    								<i class="fa fa-trash"></i> Delete
		    							</a>
										<script type="text/javascript">
										    var url="<?php echo base_url();?>";
										    var pat_id = "<?php echo $pat_id; ?>";
										    function deleteThis(id){
										       	var r=confirm("Do you really want to delete this pattern description?");
										        if (r==true) {
										          	window.location = url+"cPatternDesc/delete_desc/"+pat_id+"/"+id;
										        } else {
										          return false;
										        }
										    }
										</script>
		                            <?php } else { ?>
		                            	<input type="reset" class="btn btn-default" value="Reset" />
		                            <?php } ?>
		                        </div> 
			                </form>
			            </div>
			            <div class="tab-pane fade <?php echo ($tabs == true)? 'active in': ''; ?>" id="desc_img">
			            	<div class="panel panel-default panel-noborder">
								<div class="panel-body">
							    	<div class="col-md-8">
						    			<?php if($this->session->flashdata('upload_msg')) { ?>
						    				<div class="alert alert-dismissible alert-success">
												<button type="button" class="close" data-dismiss="alert">&times;</button>
												<strong>Saved!</strong> <?php echo $this->session->flashdata("upload_msg"); ?>
											</div>
						    			<?php } ?>
						    			<?php if($this->session->flashdata('upload_error')) { ?>
						    				<div class="alert alert-dismissible alert-danger">
												<button type="button" class="close" data-dismiss="alert">&times;</button>
												<strong>Error!</strong> <?php echo $this->session->flashdata("upload_error"); ?>
											</div>
										<?php } ?>
							    	</div>
							    	<div class="col-md-4 text-right no-padding">
							    		<div class="row">
								    		<form role="form" id="img_upload_form" action="<?php echo base_url(); ?>cPatternDesc/upload_img/<?php echo $pat_id; ?>/<?php echo $rows['id']; ?>" method="post" enctype="multipart/form-data">
								    			<input type="file" name="img_file" size="20" class="pull-left" />
								    			<input type="submit" class="btn btn-primary btn-sm pull-left" value="upload" />
								    		</form>
							    		</div>
							    	</div>
								</div>
								<div class="panel-body">
									<div class="col-md-1"></div>
									<div class="col-md-10">
										<div class="row">
											<table class="table table-striped">
												<tr style="background-color: <?php echo TBL_GREY; ?>;">
													<th class="col-md-1 text-center" >ID</th>
													<th class="col-md-1 text-center">Preview</th>
													<th class="col-md-4 text-center">File Name</th>
													<th class="col-md-6 text-center">File Path</th>
													<th></th>
												</tr>
												<?php if(!empty($file_list)) { 
													$count = 1;
													foreach ($file_list as $file) { 
												?>
													<tr>
														<td class="text-center">
															<?php echo $count; ?>
														</td>
														<td>
															<?php echo "<img src='".base_url()."images/PatternImg/".$pat_id."/".$rows['id']."/".$file['name']."' class='img-thumbnail'>";?>
														</td>
														<td class="text-center">
															<?php echo $file['name']; ?>
														</td>
														<td>
															<div class="form-group">
																<div class="input-group">
																	<input type="text" name="img_path_<?php echo $count;?>" class="form-control" id="img_path_<?php echo $count;?>" value="<?php echo base_url()."images/PatternImg/".$pat_id."/".$rows['id']."/".$file['name']; ?>">
																	<span class="input-group-btn">
																		<button class="btn btn-default" onclick="copier(<?php echo $count; ?>)"><i class="fa fa-copy"></i></button>
																	</span>
																</div>
															</div>
														</td>
														<td>
															<a href="javascript:void(0);" onclick="deleteImg('<?php echo $pat_id;?>','<?php echo $rows['id']; ?>','<?php echo $file['name']; ?>');" >
											    				<p class="text-danger"><b>&times;</b></p>
											    			</a>
														</td>
													</tr>
												<?php
													$count++; 
													} 
												} ?>
											</table>
											<script type="text/javascript">
												var url="<?php echo base_url();?>";
											    function deleteImg(pat_id, id, name){
											       	var r=confirm("Do you really want to delete image ("+name+")?");
											        if (r==true) {
											          	window.location = url+"cPatternDesc/delete_img/"+pat_id+"/"+id+"/"+name;
											        } else {
											          return false;
											        }
											    }
												function copier(id){
										            document.getElementById('img_path_'+id).select();
										            document.execCommand('copy');
										        }
											</script>
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
							</div>
			            </div>
			        </div>
	            </div>
	        </div>
	    </div>
	</section>
</div>