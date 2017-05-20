<?php 	ini_set('max_execution_time', 3000);
 		ini_set('memory_limit','120M'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
    	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    	
    	<!-- 
		****************************************
			CSS linkage
		****************************************
    	-->
    	<!-- Bootstrap Paper Theme -->
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>bootstrap.min.css" >
        <!-- FontAwesome 4.3.0 -->
        <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>assets/font-awesome/css/font-awesome.min.css">
    	<!-- Theme style -->
    	<link href="<?php echo URL; ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    	<!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    	<link href="<?php echo URL; ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    	<!-- Defined style sheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>style.css">
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/ckeditor/lib/highlight/styles/default.css"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h2 class="text text-danger"><b>Assessment Report</b></h2>
    			<h5 class="box-title col-12"><small><b>Pattern:</b>&nbsp;&nbsp;&nbsp;<?=$pattern['pattern_id']?> - <?=$pattern['pattern_name']?></small></h5><br/>
    			<?php if(!is_bool($description)) { ?>
    			<h5 class="box-title col-12"><small><b>Version:</b>&nbsp;&nbsp;<?php $ver_str = ""; foreach($description as $desc) { $ver_str = $ver_str.$desc['desc_version'].", ";} echo substr($ver_str, 0, -2);?></small></h5>
    			<?php } ?>
    		</div>
    		<div class="box-body">
    			<b>Overview Information of the Asessment</b>
    			<div class="table-responsive">
					<table class="table table-bordered">
						<tr class="font-weight-normal">
							<th class="text-center col-md-2"><h6><b>Pattern ID</b></h6></th>
							<th class="text-center col-md-2"><h6><b>Version</b></h6></th>
							<th class="text-center col-md-2"><h6><b>Assessor</b></h6></th>
							<th class="text-center col-md-6"><h6><b>Overall Result</b></h6></th>
						</tr>
		    			<?php foreach($report['result'] as $m => $var) { ?>
    					<tr>
		    				
							<?php $color = "";
								switch ($var['metric_abberv']) {
									case 'CRE':
										if($var['score'] <= 30 ) 	{ $color = "danger"; 	}
										else if($var['score'] <= 60) { $color = "success"; 	}
										else 							{ $color = "warning";	}
										break;
									case 'PAP':
									case 'CSD':
									case 'DKT':
										if($var['score'] <= 50 ) 	{ $color = "danger"; 	}
										else if($var['score'] <= 80) { $color = "warning"; 	}
										else 							{ $color = "success";	}
										break;
									default:
										break;
								} ?>
							<td class="text-center col-md-2 <?=$color?>"><?=$var['pattern_id']?></td>
							<td class="text-center col-md-2 <?=$color?>"><?=$var['desc_version']?></td>
							<td class="text-center col-md-2 <?=$color?>"><?=$this->mLogin->get_user_by_id($var['assessor_id'])[0]['user_name'];?></td>
							<td class="col-md-6 <?=$color?>"><b><?=$var['metric_abberv']?> =</b> <?=$var['score']?></td>
    					</tr>	
    					<?php } ?>
	    			</table>
				</div>
    		</div>
    	</div>
    	<?php foreach ($report['result'] as $key => $value) { ?>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h6><b>Assessment Detail of <?=$value['metric_name']?> (<?=$value['metric_abberv']?>)</b> by <span class="text text-primary"><i><?=$this->mLogin->get_user_by_id($value['assessor_id'])[0]['user_name'];?></i></span></h6>
    		</div>
    		<?php if($value['detail'] != NULL) { ?>
    			<table class="table table-bordered">
    				<tr>
    					<th class="col-xs-2" style="font-size: 8px;"><b>Name</b></th>
    					<th class="col-xs-8" style="font-size: 8px;"><b>Description</b></th>
    					<th class="col-xs-2" style="font-size: 8px;"><b>Score</b></th>
    				</tr>
    			<?php $topic = ""; ?>
    			<?php foreach($value['detail'] as $index => $data) { ?>
    				<?php if($value['metric_abberv'] == "CRE") { ?>
    					<?php if($topic != $data['remark']) { ?>
    					<tr>
    						<td class="info" style="font-size: 8px;"><b><u><?=$data['remark']?></u></b></td>
    						<td class="info"></td>
    						<td class="info"></td>
    					</tr>
    					<?php $topic = $data['remark'];?>
    					<?php } ?>
    				<?php } ?>
    				<tr>
    					<?php if($value['metric_abberv'] == "CRE") { ?>
    					<td class="col-xs-2 <?php echo ($data['variable_name'] == "Topic Score")? "bg-gray":""; ?>" style="font-size: 8px;"><?php echo ($data['variable_name'] == "Topic Score")? "<b>".str_replace('<sub>', '<span stlye="font-size: 4px">' , str_replace('</sub>', '</span>', $data['variable_name']))."</b>":str_replace('<sub>', '<span stlye="font-size: 4px">' , str_replace('</sub>', '</span>', $data['variable_name']));?></td>
    					<td class="col-xs-8 <?php echo ($data['variable_name'] == "Topic Score")? "bg-gray":""; ?>" style="font-size: 8px;"><?=$data['variable_description']?></td>
    					<td class="col-xs-2 <?php echo ($data['variable_name'] == "Topic Score")? "bg-gray":""; ?>" style="font-size: 8px;"><?=$data['variable_score']?></td>
    					<?php } else if($value['metric_abberv'] == "DKT") { ?>
	    					<?php $good = ""; 
	    					if($data['variable_score'] == 1){
	    						$good = "success";
	    					}
	    					else{
	    						$good = "danger";
	    					}
	    					?>
	    					<td class="col-xs-2 <?=$good ?>" style="font-size: 8px;"><b><?php echo str_replace('<sub>', '<span stlye="font-size: 4px">' , str_replace('</sub>', '</span>', $data['variable_name']));?></b></td>
	    					<td class="col-xs-8 <?=$good ?>" style="font-size: 8px;"><?=$data['variable_description']?></td>
	    					<td class="col-xs-2 <?=$good ?>" style="font-size: 8px;"><?=$data['variable_score']?></td>
    					<?php } else { ?>
    						<td class="col-xs-2" style="font-size: 8px;"><b><?php echo str_replace('<sub>', '<span stlye="font-size: 4px">' , str_replace('</sub>', '</span>', $data['variable_name']));?></b></td>
	    					<td class="col-xs-8" style="font-size: 8px;"><?=$data['variable_description']?></td>
	    					<td class="col-xs-2" style="font-size: 8px;"><?=$data['variable_score']?></td>
    					<?php } ?>
    				</tr>
    			<?php } ?>
    			</table>
    		<?php } else { ?>
    			<div class="well well-sm">
    				No detail for this metric.
    			</div>
    		<?php } ?>
    	</div>
    	<?php } ?>
    </body>
</html>