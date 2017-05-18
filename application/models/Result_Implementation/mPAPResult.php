<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mPAPResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 3,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 3 AND assessor_id = ".$ass_id;
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
			$condition = "id = 3";
			$detail_cond = "metric_id = 3";
			$metr = $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail = $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');
			$metr['detail'] = $metr_detail;
			return (is_bool($metr))?array():$metr;
		}

		public function get_result_all($pat_id, $ver=NULL){
			$condition = "pattern_id ='".$pat_id."' AND metric_id = 3";
			$condition = $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");
			$result = $this->d->select('*', $condition, 'assess_result');
			if($result != NULL){
				foreach ($result as $key => $value) {
					$detail_cond = "result_id = ".$value['result_id'];
					$detail = $this->d->select('*', $detail_cond, 'assess_result_detail');
					$result[$key]['detail'] = $detail;
				}
			}
			return $result;
		}

		public function get_result($pat_id, $ver, $ass_id){
			$condition = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 3";
			$result = $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}

		public function get_result_detail($result_id){
			$condition = "$result_id = ".$result_id;
			$result = $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		public function update_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 3,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 3 AND assessor_id = ".$ass_id;
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
			$CP = (array_key_exists('71', $data) AND array_key_exists('72', $data) AND $data['72'] != 0.00)? $data['71']/$data['72']: NULL;
			$AP = (array_key_exists('73', $data) AND array_key_exists('74', $data) AND $data['74'] != 0.00)? $data['73']/$data['74']: NULL;
			$OP = (array_key_exists('75', $data) AND array_key_exists('76', $data) AND $data['76'] != 0.00)? $data['75']/$data['76']: NULL;
			$AscP = (array_key_exists('77', $data) AND array_key_exists('78', $data) AND $data['78'] != 0.00)? $data['77']/$data['78']: NULL;
			$GenP = (array_key_exists('79', $data) AND array_key_exists('80', $data) AND $data['80'] != 0.00)? $data['79']/$data['80']: NULL;
			$AgrP = (array_key_exists('81', $data) AND array_key_exists('82', $data) AND $data['82'] != 0.00)? $data['81']/$data['82']: NULL;
			$ComP = (array_key_exists('83', $data) AND array_key_exists('84', $data) AND $data['84'] != 0.00)? $data['83']/$data['84']: NULL;
			$NP = 0;
			if($CP != NULL){ $NP++; } else { $CP = 0; }
			if($AP != NULL){ $NP++; } else { $AP = 0; }
			if($OP != NULL){ $NP++; } else { $OP = 0; }
			if($AscP != NULL){ $NP++; } else { $AscP = 0; }
			if($GenP != NULL){ $NP++; } else { $GenP = 0; }
			if($AgrP != NULL){ $NP++; } else { $AgrP = 0; }
			if($ComP != NULL){ $NP++; } else { $ComP = 0; }
			return (($CP + $AP + $OP + $AscP + $GenP + $AgrP + $ComP)/$NP)*100;
		}
	}
?>