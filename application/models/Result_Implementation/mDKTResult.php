<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mDKTResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_result($data){

		}

		public function get_metric(){
			$condition = "id = 1";
			$detail_cond = "metric_id = 1";
			$metr = $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail = $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');
			$metr['detail'] = $metr_detail;
			return (is_bool($metr))?array():$metr;
		}

		public function get_result($pat_id, $ver, $ass_id){
			$condition = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 1";
			$result = $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}

		// public function get_result_w_detail($pat_id, $ver, $ass_id){
		// 	$condition = "pattern_id = '".$pat_id."' AND desc_version = ".$ver." AND assessor_id = ".$ass_id." AND metric_id = 1";
		// 	$result = $this->d->select('*', $condition, 'assess_result', 1);
		// 	if(is_bool($result)){
		// 		return NULL;
		// 	}
		// 	$detail_cond = "result_id = ".$result[0]['result_id'];
		// 	$result_detail = $this->d->select('*', $detail_cond, 'assess_result_detail');
		// 	$final = array(
		// 		'result' => $result[0],
		// 		'result_detail' => (is_bool($result_detail))? NULL:$result_detail
		// 		);
		// 	return $final;
		// }

		public function get_result_detail($result_id){
			$condition = "$result_id = ".$result_id;
			$result = $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		public function update_result($pat_id, $ver, $ass_id, $data){

		}

		public function delete_result($result_id){

		}
	}
?>