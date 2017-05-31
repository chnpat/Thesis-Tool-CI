<?php
	require_once "iAssessResult.php";
	/**
	 *	mCREResult is a model class for managing the result of CRE metric
	 *
	 *	mCREResult is a model class for managing the result of CRE metric and includes the CRUDE operation,
	 *	i.e. create, get, update, and delete.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mCREResult extends CI_Model implements iAssessResult
	{
		
		/**
		 *	__construct method is a constructor method for mCREResult
		 *
		 *	__construct method is a constructor method for mCREResult class
		 *	which will load a set of necessarily libraries, models, and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection','d');
			$this->load->model(array(
				'mPattern', 
				'mPatternDesc')
			);

			$this->load->library(array(
				'TextStatistics/Syllables', 
				'TextStatistics/Text', 
				'TextStatistics/TextStatistics')
			);
		}

		/**
		 *	create_result() is a model function for creating a new CRE metrics' result.
		 *
		 *	create_result() is a model function for creating a new CRE metrics' result into the database.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 * 	@param 		float 		$ver 		The version number of the pattern description that relates
		 *										to the creating result.
		 *	@param 		integer 	$ass_id 	The assessor ID that is an integer.
		 *	@param 		array 		$data 		An array of the result data that has keys as same as the
		 *										column name.
		 *
		 *	@return 	void
		 */
		public function create_result($pat_id, $ver, $ass_id, $data){

			/** Calls the 'calculate_result' function of this class for calculating the final CRE score */
			$result 	= $this->calculate_result($data["pattern"], $data["description"], $ass_id);

			/** Sets the result object that matches the column name. */
			$database_obj_overall = array(
				'pattern_id' 	=> $pat_id,
				'desc_version' 	=> $ver,
				'metric_id' 	=> 4,
				'score' 		=> $result['Overall'],
				'assessor_id' 	=> $ass_id
				);

			/** Set the 'condition' statement to the database helper. */
			$condition 		= "pattern_id ='".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 4 AND assessor_id = ".$ass_id;

			/** Calls the 'create' command of the database helper. */
			$this->d->create('*', $condition, 'assess_result', $database_obj_overall);
			
			/** Retreives the fresh-created to get the new result ID */
			$result_id 		= $this->d->select('result_id', $condition, "assess_result",1)[0];

			/** Runs through the result detail and stores an object of each variable into the database. */
			foreach ($result['Each'] as $key => $value) {
				foreach (array(86,87,88,89) as $var_id) {
					$database_obj_each = array(
						'result_id' 	=> $result_id['result_id'],
						'variable_id' 	=> $var_id,
						'remark' 		=> $key);
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

		/**
		 *	get_metric() is a model function for getting a list of metric information and its detail.
		 *
		 *	get_metric() is a model function for getting a list of metric information and its detail from 
		 *	the database.
		 *
		 *	@return 	array 				An array of the metric object with its detail inside.
		 */
		public function get_metric(){
			/** Sets the 'condition' statement to the database helper for metric table */
			$condition 		= "id = 4";

			/** Sets the 'condition' statement to the database helper for metric detail table */
			$detail_cond 	= "metric_id = 4";

			/** Calls the 'select' command to get metric and metric detail object from the database. */
			$metr 			= $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail 	= $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');

			/** Sets the metric detail into metric object. */
			$metr['detail'] = $metr_detail;

			return (is_bool($metr))?array():$metr;
		}

		/**
		 * 	get_result_all() is a model function for getting all result of CRE metric of the specified pattern
		 *
		 *	get_result_all() is a model function for getting all result of CRE metric of the specified pattern
		 *	description. This function is not concerned on who assessed.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *
		 *	@return 	array 					An array of CRE metric results of the specified pattern.
		 */
		public function get_result_all($pat_id, $ver=NULL){

			/** Sets the 'condition' statement to the database helper */
			$condition 	= "pattern_id ='".$pat_id."' AND metric_id = 4";

			/** Adds on the description version to the 'condition' statement if the parameter is set. */
			$condition 	= $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");

			/** Gets the array of the result object from the database */
			$result 	= $this->d->select('*', $condition, 'assess_result');
			
			if($result != NULL){

				/** Runs through all result of CRE metric in the database */
				foreach ($result as $key => $value) {

					/** Sets the 'condition' statement to the database helper for the result detail */
					$detail_cond 	= "result_id = ".$value['result_id'];

					/** Calls the 'select' command of the database helper */
					$detail 		= $this->d->select('*', $detail_cond, 'assess_result_detail');

					$result[$key]['detail'] = NULL;
					$temp 			= array();

					/** Checks whether the result includes its detail or not. */
					if($detail != NULL){

						/** If it included, runs through each detail and store into result object */
						foreach($detail as $index => $d){
							$temp[$d['remark']][$d['id']]['result_id'] 		= $d['result_id'];
							$temp[$d['remark']][$d['id']]['variable_id'] 	= $d['variable_id'];
							$temp[$d['remark']][$d['id']]['variable_score'] = $d['variable_score'];
						}
						$result[$key]['detail'] 	= $temp;
					}
				}
			}
			return $result;
		}


		/**
		 *	get_result_no_detail() is a model function for getting all result with no detail.
		 *
		 *	get_result_no_detail() is a model function for getting all result with no detail by passing
		 *	pattern Id and the version number as parameters.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *
		 *	@return 	array 					An array of the assessment result without mapping with its
		 *										detail.
		 */
		public function get_result_no_detail($pat_id, $ver=NULL){

			/** Sets a 'condition' statement to the database helper. */
			$condition 	= "pattern_id ='".$pat_id."' AND metric_id = 4";

			/** Concatenate the 'condition' statement to the database helper. */
			$condition 	= $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");

			/** Calls the 'select' command of the database helper. */
			$result 	= $this->d->select('*', $condition, 'assess_result');
			return $result;
		}

		/**
		 *	get_joined_detail_result() is a model function for getiing the description-mapped result details.
		 *
		 * 	get_joined_detail_result() is a model function for getting the result details which are mapped
		 *	with the metric detail description.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *
		 *	@return 	array 					An array of the assessment result which mapped with the
		 *										metric detail description.		
		 */
		public function get_joined_detail_result($pat_id, $ver = NULL){

			/** Sets a 'condition' statement to the database helper. */
			$condition 	= "metric_id = 4";

			/** Concatenate the 'condition' statement to the database helper. */
			$condition 	= $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");

			/** Calls the 'select' command of the database helper with the 'join' statement specified. */
			$result 	= $this->d->select_joined(	'metric_variable.id, metric_variable.metric_id, metric_variable.variable_name, metric_variable.variable_description, assess_result_detail.*', 
												$condition,
												'assess_result_detail',
												'metric_variable',
												'assess_result_detail.variable_id = metric_variable.id',
												'left' );
			return $result;
		}

		/**
		 *	get_result() is a model function for getting an individual result of the specified pattern
		 *
		 *	get_result() is a model function for getting an individual result of the specified pattern.
		 *	This function is scope the retrieved array to just the pattern that assessed by the specified
		 *	assessor.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *	@param 		integer 	$ass_id 	The assessor ID of the result that is an integer.
		 *
		 *	@return 	array 					An array of the specified result.		
		 */
		public function get_result($pat_id, $ver, $ass_id){

			/** Sets a 'condition' statement to the database helper. */
			$condition 	= "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 4";
			
			/** Calls the 'select' command of the database helper. */
			$result 	= $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}

		/**
		 *	get_result_detail() is a model function for getting an array of result details
		 *
		 *	get_result_detail() is a model function for getting an array of result details to the specified
		 *	result ID.
		 *
		 *	@param 		integer 	$result_id 		The result ID that is an integer.
		 *
		 *	@return 	array 						An array of the result detail to the specified result.
		 */
		public function get_result_detail($result_id){

			/** Sets a 'condition' statement to the database helper. */
			$condition 	= "$result_id = ".$result_id;

			/** Calls the 'select' command of the database helper. */
			$result 	= $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		/**
		 *	calculate_result() is a model function for calculating the final result from the passing parameters
		 *
		 *	calculate_result() is a model function for calculating the final result from the passing parameters
		 *	which is different from the CRE metric.
		 *
		 *	@param 		array 		$pat 		An array that represents the pattern object.
		 *	@param 		array 		$desc 		An array that represents the pattern description object.
		 *	@param 		integer 	$ass_id 	The assessor ID that is an integer.
		 *
		 *	@return 	array 					The result object that is the calculated result. 
		 */
		public function calculate_result($pat, $desc, $ass_id){

			/** Defines the pattern topic as an array of string */
			$topics 	= array(
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

			/** Retrieves the string from the pattern description object. */
			foreach ($topics as $top) {
				switch ($top) {
					case 'Pattern Name':
						$str 	= $pat['pattern_name'];
						break;
					case 'Pattern Classification':
						$str 	= $desc['desc_classification'];
						break;
					case 'Also Known As':
						$str 	= $desc['desc_aka'];
						break;
					case 'Known Uses':
						$str 	= $desc['desc_known_uses'];
						break;
					case 'Sample Code':
						$str 	= $desc['desc_sample_code'];
						break;
					case 'Related Patterns':
						$str 	= $desc['desc_related_pattern'];
						break;
					default:
						$str 	= $desc["desc_".strtolower($top)];
						break;
				}
				/** Stripes out the HTML tags out of the pattern description. */
				$notags 			= strip_tags($str);
				$desc_notags[$top] 	= $notags;
			}
			$res 		= 0;
			$counter 	= 0;
			$result 	= array();
			/** Runs through all description of each topic. */
			foreach ($desc_notags as $k => $d) {

				/** Checks whether the string is longer than 100 words or not. */
				if(str_word_count($d) > 100){

					/** Counts the number of words in the content. */
					$n_word 	= str_word_count($d);

					/** Counts the number of syllables in the content. */
					$n_syllable = Syllables::totalSyllables($d);

					/** Counts the number of sentences in the content. */
					$n_sentence = Text::sentenceCount($d);

					/** Calculates the CRE score of the topic */
					$top_CRE 	= 206.835 -  (1.015 * (Text::wordCount($d)/Text::sentenceCount($d))) - (86.4 * (Syllables::totalSyllables($d)/Text::wordCount($d)));

					/** Sets the value to the result detail object. */
					$result['Each'][$k]['score'] 		= $top_CRE;
					$result['Each'][$k]['n_word'] 		= $n_word;
					$result['Each'][$k]['n_sentence'] 	= $n_sentence;
					$result['Each'][$k]['n_syllable'] 	= $n_syllable;
					$res 		= $res + $top_CRE;
					$counter++;
				}
			}
			if($counter > 0){
				/** Sets the overall score of the pattern description. */
				$result['Overall'] 	= ($res/$counter);
			}else{
				$result['Each'] 	= array();
				$result['Overall'] 	= 0;
			}

			return $result;
		}

		/**
		 *	update_result() is a model function for updating the result in the case of re-assess the pattern.
		 *
		 *	update_result() is a model function for updating the result in the case of re-assess the pattern.
		 *	This function receives the input from the view and update into the database.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *	@param 		integer 	$ass_id 	The assessor ID that is an integer.
		 *	@param 		array 		$deta 		An array of the result data that wants to be updated.
		 */
		public function update_result($pat_id, $ver, $ass_id, $data){

			/** Retrieves an array of pattern object. */
			$pat 	= $this->mPattern->get_pattern($pat_id);

			/** Retrieves an array of pattern description object. */
			$desc 	= (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version'])[0]: array();
			
			/** Calls the 'calculate_result' function to re-calculate the result score. */
			$result = $this->calculate_result($pat, $desc, $ass_id);

			/** Sets the result object that uses to update in the database. */
			$database_obj_overall 	= array(
				'pattern_id' 	=> $pat_id,
				'desc_version' 	=> $ver,
				'metric_id' 	=> 4,
				'score' 		=> $result['Overall'],
				'assessor_id' 	=> $ass_id
				);

			/** Sets the 'condition' statement to the database helper. */
			$condition 	= "pattern_id ='".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 4 AND assessor_id = ".$ass_id;

			/** Calls the 'update' command of the database helper. */
			$this->d->update($condition, 'assess_result', $database_obj_overall);
			
			/** Then, update detail of the result recursively. */
			$result_id 	= $this->d->select('result_id', $condition, "assess_result",1)[0];
			foreach ($result['Each'] as $key => $value) {
				foreach (array(86,87,88,89) as $var_id) {
					$database_obj_each 	= array(
						'result_id' 	=> $result_id['result_id'],
						'variable_id' 	=> $var_id,
						'remark' 		=> $key);
					switch ($var_id) {
						case 86:
							$database_obj_each['variable_score'] 	= $value['n_word'];
							break;
						case 87:
							$database_obj_each['variable_score'] 	= $value['n_sentence'];
							break;
						case 88:
							$database_obj_each['variable_score'] 	= $value['n_syllable'];
							break;
						default:
							$database_obj_each['variable_score'] 	= $value['score'];
							break;
					}
					$update_cond 	= "result_id = ".$result_id['result_id']." AND variable_id = ".$var_id." AND remark = '".$key."'";
					$this->d->update($update_cond, 'assess_result_detail', $database_obj_each);
				}
			}
		}
	}
?>