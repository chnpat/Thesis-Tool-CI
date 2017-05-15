<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mCSDResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 2,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 2 AND assessor_id = ".$ass_id;
			$this->d->create('*', $cond, 'assess_result',$result);
			$result_id = $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];
			foreach ($data as $key => $value) {
				$var_cond = "result_id = ".$result_id." AND variable_id = ".$key;
				$var = array(
					'result_id' => $result_id,
					'variable_id' => $key,
					'variable_score' => $value
					);
				$this->d->create('*', $var_cond, 'assess_result_detail', $var);
			}
		}

		public function get_metric(){
			$condition = "id = 2";
			$detail_cond = "metric_id = 2";
			$metr = $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail = $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');
			$metr['detail'] = $metr_detail;
			return (is_bool($metr))?array():$metr;
		}

		public function get_result($pat_id, $ver, $ass_id){
			$condition = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 2";
			$result = $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}


		public function get_result_detail($result_id){
			$condition = "result_id = ".$result_id;
			$result = $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		public function update_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 2,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 2 AND assessor_id = ".$ass_id;
			$this->d->update($cond, 'assess_result',$result);
			$result_id = $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];
			foreach ($data as $key => $value) {
				$var_cond = "result_id = ".$result_id." AND variable_id = ".$key;
				$var = array(
					'result_id' => $result_id,
					'variable_id' => $key,
					'variable_score' => $value
					);
				$this->d->update($var_cond, 'assess_result_detail', $var);
			}
		}

		function calculate_result($data){
			if(count($data) > 0){
				$A_used = count($data)/2;
				$sum = 0;
				$i = 47;
				while($i <= 70){
					if(array_key_exists($i, $data) AND array_key_exists($i+1, $data)){
						$a = $data[$i];
						$b = $data[$i+1];
						$res = ($b != 0)?$a/$b: 0;
						$sum = $sum + $res;
					}
					$i = $i+2;
				}
				return ($sum/$A_used)*100;
			}else{
				return 0;
			}
		}
	}
?>