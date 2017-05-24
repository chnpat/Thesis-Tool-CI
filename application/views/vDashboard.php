<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control Panel</small>
      </h1>
    </section>
    <section class="content">
    	<!-- For Summary -->
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Summary</b></h4>
    			<div class="box-tools pull-right">
    				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title data-original-title="Hide">
    					<i class="fa fa-minus"></i>
    				</button>
    			</div>
    		</div>
    		<div class="box-body">
    			<!-- Number of Patterns -->
    			<div class="col-md-3 col-sm-6 col-xs-12">
    				<div class="info-box bg-aqua">
    					<span class="info-box-icon"><i class="fa fa-book"></i></span>
    					<div class="info-box-content">
	    					<div class="info-box-text"><b>Patterns</b></div>
	    					<div class="info-box-number"><?=$Number_of_patterns?></div>
	    					<div class="progress">
	    						<div class="progress-bar" style="width: 100%"></div>
	    					</div>
	    					<span class="progress-description">No. of Patterns</span>
    					</div>
    				</div>
    			</div>
    			<!-- Number of Pending-for-Assessing Patterns -->
    			<div class="col-md-3 col-sm-6 col-xs-12">
    				<div class="info-box bg-yellow">
    					<span class="info-box-icon"><i class="fa fa-spinner"></i></span>
    					<div class="info-box-content">
	    					<div class="info-box-text"><b>Pending</b></div>
	    					<div class="info-box-number"><?=$Number_of_pending?> / <?=$Number_of_patterns?></div>
	    					<div class="progress">
	    						<div class="progress-bar" style="width: <?=($Number_of_patterns != 0)?round(($Number_of_pending/$Number_of_patterns), 2)*100: 0;?>%"></div>
	    					</div>
	    					<span class="progress-description"><?=($Number_of_patterns != 0)?round(($Number_of_pending/$Number_of_patterns), 2)*100: 0;?>% are pending</span>
    					</div>
    				</div>
    			</div>
    			<!-- Number of Assessed Patterns -->
    			<div class="col-md-3 col-sm-6 col-xs-12">
    				<div class="info-box bg-orange">
    					<span class="info-box-icon"><i class="fa fa-gears"></i></span>
    					<div class="info-box-content">
	    					<div class="info-box-text">Assessed</div>
	    					<div class="info-box-number"><?=$Number_of_unreach?> / <?=$Number_of_patterns?></div>
	    					<div class="progress">
	    						<div class="progress-bar" style="width: <?=($Number_of_patterns != 0)?round(($Number_of_unreach/$Number_of_patterns), 2)*100: 0;?>%"></div>
	    					</div>
	    					<span class="progress-description"><?=($Number_of_patterns != 0)?round(($Number_of_unreach/$Number_of_patterns), 2)*100: 0;?>% are assessed</span>
    					</div>
    				</div>
    			</div>
    			<!-- Number of Complete-Assessed Patterns -->
    			<div class="col-md-3 col-sm-6 col-xs-12">
    				<div class="info-box bg-green">
    					<span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
    					<div class="info-box-content">
	    					<div class="info-box-text">Completed</div>
	    					<div class="info-box-number"><?=$Number_of_reach?> / <?=$Number_of_patterns?></div>
	    					<div class="progress">
	    						<div class="progress-bar" style="width: <?=($Number_of_patterns != 0)?round(($Number_of_reach/$Number_of_patterns), 2)*100: 0;?>%"></div>
	    					</div>
	    					<span class="progress-description"><?=($Number_of_patterns != 0)?round(($Number_of_reach/$Number_of_patterns), 2)*100: 0;?>%  completed</span>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    	<!-- For Assessment Detail -->
    	<?php if($userObj['user_role'] == "Assessor" OR $userObj['user_role'] == "Admin") { ?>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Pending-for-Assessing Pattern List</b></h4>
    			<div class="box-tools pull-right">
    				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title data-original-title="Hide">
    					<i class="fa fa-minus"></i>
    				</button>
    			</div>
    		</div>
    		<div class="box-body table-responsive">
    			<table class="table table-striped table-bordered">
    				<tr style="background-color: <?=TBL_GREY?>">
    					<th class="col-md-2 text-center">Pattern ID</th>
		    			<th class="col-md-6 text-center">Pattern Name</th>
		    			<th class="col-md-2 text-center">Pattern Version</th>
		    			<th class="col-md-2"></th>
    				</tr>
    				<!-- Add a list -->
    				<?php if(count($unreach_list) != 0) { ?>
    					<?php foreach($unreach_list as $unreach) { ?>
    					<tr>
    						<td class="text-center"><?=$unreach['pattern_id']?></td>
    						<td><?=$unreach['pattern_name']?></td>
    						<td class="text-center"><?=$unreach['pattern_assess_version']?></td>
    						<td><a href="<?=base_url();?>cAssess/index" class="bth bg-orange btn-xs"><i class="fa fa-check-square-o"></i> Assess</a></td>
    					</tr>
    					<?php } ?>
                    <?php } ?>
    			</table>
                <?php if(count($unreach_list) == 0) { ?>
                <div class="well well-sm text-center">
                    No Data for this conditions.
                </div>
                <?php } ?>
    		</div>
    	</div>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Assessed Pattern List</b></h4>
    			<div class="box-tools pull-right">
    				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title data-original-title="Hide">
    					<i class="fa fa-minus"></i>
    				</button>
    			</div>
    		</div>
    		<div class="box-body table-responsive">
    			<table class="table table-striped table-bordered">
    				<tr style="background-color: <?=TBL_GREY?>">
    					<th class="col-md-2 col-sm-2 text-center">Pattern ID</th>
		    			<th class="col-md-6 col-sm-6 text-center">Pattern Name</th>
		    			<th class="col-md-3 col-sm-4 text-center">Pattern Version</th>
		    			<th class="col-md-1 hidden-sm hidden-xs"></th>
    				</tr>
    				<!-- Add a list -->
    				<?php if(count($reach_list) != 0) { ?>
    					<?php foreach($reach_list as $reach) { ?>
    					<tr>
    						<td class="text-center"><?=$reach['pattern_id']?></td>
    						<td><?=$reach['pattern_name']?></td>
    						<td class="text-center"><?=$reach['pattern_assess_version']?></td>
    						<td></td>
    					</tr>
    					<?php } ?>
                    <?php } ?>
    			</table>
                <?php if(count($unreach_list) == 0) { ?>
                <div class="well well-sm text-center">
                    No Data for this conditions.
                </div>
                <?php } ?>
    		</div>
    	</div>
    	<?php } ?>
    	<!-- For Pattern List -->
    	<?php if ($userObj['user_role'] == "Regular" OR $userObj['user_role'] == "Admin") { ?>
    	<div class="box box-danger">
    		<div class="box-header with-border">
    			<h4 class="box-title"><b>Your Pattern List</b></h4>
    			<div class="box-tools pull-right">
    				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title data-original-title="Hide">
    					<i class="fa fa-minus"></i>
    				</button>
    			</div>
    		</div>
    		<div class="box-body">
    			<table class="table table-bordered table-striped">
	    			<tr style="background-color: <?=TBL_GREY?>;">
	    				<th class="col-md-1 text-center">Pattern ID</th>
	    				<th class="col-md-3 text-center">Name</th>
	    				<th class="col-md-2 hidden-sm hidden-xs text-center">Creator Name</th>
	    				<th class="col-md-1 text-center">Assess on Version</th>
	    				<th class="col-md-2 hidden-sm hidden-xs text-center">Status</th>
	    			</tr>
	    			<?php if(count($pattern_list) != 0) { ?>
    					<?php foreach($pattern_list as $patt) { ?>
    					<tr>
    						<td class="text-center"><?=$patt['pattern_id']?></td>
    						<td><?=$patt['pattern_name']?></td>
    						<td class="text-center hidden-sm hidden-xs"><?=$this->mLogin->get_user_by_id($patt['pattern_creator_id'])[0]['user_name'];?></td>
    						<td class="text-center"><?=$patt['pattern_assess_version']?></td>
    						<?php if($patt['pattern_status'] == "Ready") { ?>
    							<td class="text-center hidden-sm hidden-xs"><div class="label label-success label-sm">Ready to be Assessed</div></td>
    						<?php } else if($patt['pattern_status'] == "Assessed") { ?>
    							<td class="text-center hidden-sm hidden-xs"><div class="label label-warning label-sm">Pattern Assessed</div></td>
    						<?php } else { ?>
    							<td class="text-center hidden-sm hidden-xs"><div class="label label-danger label-sm">Disabled for Assessing</div></td>
    						<?php } ?>
    					</tr>
    					<?php } ?>
                    <?php } ?>
	    		</table>
                <?php if(count($unreach_list) == 0) { ?>
                <div class="well well-sm text-center">
                    No Pattern Data are provided.
                </div>
                <?php } ?>
    		</div>
    	</div>
    	<?php } ?>
    </section>
</div>