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

		public function get_result_group_by_assessor($pat_id, $ver){
			$condition = "pattern_id = '".$pat_id."' AND desc_version ='".$ver."'";
			return $this->d->select_grouped('result_id, assessor_id', $condition, 'assess_result', 'assessor_id');
		}
	}
?>