<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mCREResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model(array('mDBConnection'));
			$this->load->library(array('TextStatistics/Syllables', 'TextStatistics/Text', 'TextStatistics/TextStatistics'));
		}

		public function create_result($data){
			$this->calculate_result($data["pattern"], $data["description"], $data["ass_id"]);
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

		// public function get_result_w_detail($pat_id, $ver, $ass_id){
		// 	$condition = "pattern_id = '".$pat_id."' AND desc_version = ".$ver." AND assessor_id = ".$ass_id." AND metric_id = 4";
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
			foreach ($desc_notags as $d) {
				if(str_word_count($d) > 100){
					$n_word = str_word_count($d);
					$n_syllable = Syllables::totalSyllables($d);
					$n_sentence = Text::sentenceCount($d);
					$top_CRE = 206.835 -  (1.015 * (Text::wordCount($d)/Text::sentenceCount($d))) - (86.4 * (Syllables::totalSyllables($d)/Text::wordCount($d)));
					echo $top_CRE."<br/>";
					$res = $res + $top_CRE;
					$counter ++;
					//$n_syllable = $this->Syllables->totalSyllables($d);
					// echo "1-".$n_syllable;
				}
			}
			echo "Average=".($res/$counter);
		}

		public function update_result($pat_id, $ver, $ass_id, $data){

		}

		public function delete_result($result_id){

		}

		public function count_syllable($str){

		}
	}
?>