<?php 
	/**
	* 
	*/
	class mAssessResult extends CI_Model
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function get_result_group_by_assessor($pat_id, $ver = NULL){
			$condition = "pattern_id = '".$pat_id."'".(($ver != NULL)?" AND desc_version = ".$ver: "");
			return $this->d->select_grouped('result_id, assessor_id', $condition, 'assess_result', 'assessor_id');
		}

		public function get_all_result_by_pattern($pat_id, $ver = NULL){
			$condition = "pattern_id = '".$pat_id."'".(($ver != NULL)?" AND desc_version = ".$ver: "");
			return $this->d->select_joined( 'metric.*, assess_result.*', 
											$condition, 
											'assess_result',
											'metric',
											'assess_result.metric_id = metric.id',
											'left');
		}

		public function get_all_result_detail_w_metric($result_id){
			$condition = "result_id = ".$result_id;
			return $this->d->select_joined(	'metric_variable.id, metric_variable.metric_id, metric_variable.variable_name, metric_variable.variable_description, assess_result_detail.*', 
												$condition,
												'assess_result_detail',
												'metric_variable',
												'assess_result_detail.variable_id = metric_variable.id',
												'left' );
		}
	}
?>