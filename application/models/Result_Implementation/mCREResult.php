<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mCREResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection','d');
			$this->load->model(array('mPattern', 'mPatternDesc'));
			$this->load->library(array('TextStatistics/Syllables', 'TextStatistics/Text', 'TextStatistics/TextStatistics'));
		}

		public function create_result($pat_id, $ver, $ass_id, $data){
			$result = $this->calculate_result($data["pattern"], $data["description"], $ass_id);
			$database_obj_overall = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 4,
				'score' => $result['Overall'],
				'assessor_id' => $ass_id
				);
			$condition = "pattern_id ='".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 4 AND assessor_id = ".$ass_id;
			$this->d->create('*', $condition, 'assess_result', $database_obj_overall);
			
			// Create Detail
			$result_id = $this->d->select('result_id', $condition, "assess_result",1)[0];
			foreach ($result['Each'] as $key => $value) {
				foreach (array(86,87,88,89) as $var_id) {
					$database_obj_each = array(
						'result_id' => $result_id['result_id'],
						'variable_id' => $var_id,
						'remark' => $key);
					switch ($var_id) {
						case 86:
							$database_obj_each['variable_score'] = $value['n_word'];
							break;
						case 87:
							$database_obj_each['variable_score'] = $value['n_sentence'];
							break;
						case 88:
							$database_obj_each['variable_score'] = $value['n_syllable'];
							break;
						default:
							$database_obj_each['variable_score'] = $value['score'];
							break;
					}
					$this->d->create('*', 'result_id = '.$result_id['result_id'].' AND variable_id = '.$var_id.' AND remark = "'.$key.'"', 'assess_result_detail', $database_obj_each);
				}
			}
		}

		public function get_metric(){
			$condition = "id = 4";
			$detail_cond = "metric_id = 4";
			$metr = $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail = $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');
			$metr['detail'] = $metr_detail;
			return (is_bool($metr))?array():$metr;
		}

		public function get_result($pat_id, $ver, $ass_id){
			$condition = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 4";
			$result = $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}

		public function get_result_detail($result_id){
			$condition = "$result_id = ".$result_id;
			$result = $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		public function calculate_result($pat, $desc, $ass_id){
			$topics = array(
						'Pattern Name',
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
	        			'Related Patterns');
			$desc_notags = array();
			foreach ($topics as $top) {
				switch ($top) {
					case 'Pattern Name':
						$str = $pat['pattern_name'];
						break;
					case 'Pattern Classification':
						$str = $desc['desc_classification'];
						break;
					case 'Also Known As':
						$str = $desc['desc_aka'];
						break;
					case 'Known Uses':
						$str = $desc['desc_known_uses'];
						break;
					case 'Sample Code':
						$str = $desc['desc_sample_code'];
						break;
					case 'Related Patterns':
						$str = $desc['desc_related_pattern'];
						break;
					default:
						$str = $desc["desc_".strtolower($top)];
						break;
				}
				$notags = strip_tags($str);
				$desc_notags[$top] = $notags;
			}
			$res = 0;
			$counter = 0;
			$result = array();
			foreach ($desc_notags as $k => $d) {
				if(str_word_count($d) > 100){
					$n_word = str_word_count($d);
					$n_syllable = Syllables::totalSyllables($d);
					$n_sentence = Text::sentenceCount($d);
					$top_CRE = 206.835 -  (1.015 * (Text::wordCount($d)/Text::sentenceCount($d))) - (86.4 * (Syllables::totalSyllables($d)/Text::wordCount($d)));
					$result['Each'][$k]['score'] = $top_CRE;
					$result['Each'][$k]['n_word'] = $n_word;
					$result['Each'][$k]['n_sentence'] = $n_sentence;
					$result['Each'][$k]['n_syllable'] = $n_syllable;
					$res = $res + $top_CRE;
					$counter ++;
				}
			}
			if($counter > 0){
				$result['Overall'] = ($res/$counter);
			}else{
				$result['Each'] = array();
				$result['Overall'] = 0;
			}

			return $result;
		}

		public function update_result($pat_id, $ver, $ass_id, $data){
			$pat = $this->mPattern->get_pattern($pat_id);
			$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version'])[0]: array();
			
			$result = $this->calculate_result($pat, $desc, $ass_id);
			$database_obj_overall = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 4,
				'score' => $result['Overall'],
				'assessor_id' => $ass_id
				);
			$condition = "pattern_id ='".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 4 AND assessor_id = ".$ass_id;
			$this->d->update($condition, 'assess_result', $database_obj_overall);
			
			// Update Detail
			$result_id = $this->d->select('result_id', $condition, "assess_result",1)[0];
			foreach ($result['Each'] as $key => $value) {
				foreach (array(86,87,88,89) as $var_id) {
					$database_obj_each = array(
						'result_id' => $result_id['result_id'],
						'variable_id' => $var_id,
						'remark' => $key);
					switch ($var_id) {
						case 86:
							$database_obj_each['variable_score'] = $value['n_word'];
							break;
						case 87:
							$database_obj_each['variable_score'] = $value['n_sentence'];
							break;
						case 88:
							$database_obj_each['variable_score'] = $value['n_syllable'];
							break;
						default:
							$database_obj_each['variable_score'] = $value['score'];
							break;
					}
					$update_cond = "result_id = ".$result_id['result_id']." AND variable_id = ".$var_id." AND remark = '".$key."'";
					$this->d->update($update_cond, 'assess_result_detail', $database_obj_each);
				}
			}
		}
	}
?>