<?php
	require_once "iAssessResult.php";
	/**
	 *	mPAPResult is a model class for managing the result of PAP metric
	 *
	 *	mDKTResult is a model class for managing the result of PAP metric and includes the CRUDE operation,
	 *	i.e. create, get, update, and delete.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mPAPResult extends CI_Model implements iAssessResult{
		
		/**
		 *	__construct method is a constructor method for mDKTResult
		 *
		 *	__construct method is a constructor method for mDKTResult class
		 *	which will load a set of necessarily libraries, models, and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		/**
		 *	create_result() is a model function for creating a new PAP metrics' result.
		 *
		 *	create_result() is a model function for creating a new PAP metrics' result into the database.
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

			/** Sets the result object that matches the column name. */
			$result 	= array(
				'pattern_id' 	=> $pat_id,
				'desc_version' 	=> $ver,
				'metric_id' 	=> 3,
				'score' 		=> $this->calculate_result($data),
				'assessor_id' 	=> $ass_id
				);

			/** Set the 'condition' statement to the database helper. */
			$cond 		= "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 3 AND assessor_id = ".$ass_id;

			/** Calls the 'create' command of the database helper. */
			$this->d->create('*', $cond, 'assess_result',$result);

			/** Retreives the fresh-created to get the new result ID */
			$result_id 	= $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];

			/** Runs through the result detail and stores an object of each variable into the database. */
			foreach ($data as $key => $value) {
				$var_cond 	= "result_id = ".$result_id." AND variable_id = ".$key;
				$var 		= array(
					'result_id' 		=> $result_id,
					'variable_id' 		=> $key,
					'variable_score' 	=> $value
					);
				$this->d->create('*', $var_cond, 'assess_result_detail', $var);
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
			$condition 		= "id = 3";

			/** Sets the 'condition' statement to the database helper for metric detail table */
			$detail_cond 	= "metric_id = 3";

			/** Calls the 'select' command to get metric and metric detail object from the database. */
			$metr 			= $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail 	= $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');

			/** Sets the metric detail into metric object. */
			$metr['detail'] = $metr_detail;

			return (is_bool($metr))?array():$metr;
		}

		/**
		 * 	get_result_all() is a model function for getting all result of PAP metric of the specified pattern
		 *
		 *	get_result_all() is a model function for getting all result of PAP metric of the specified pattern
		 *	description. This function is not concerned on who assessed.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *
		 *	@return 	array 					An array of PAP metric results of the specified pattern.
		 */
		public function get_result_all($pat_id, $ver=NULL){

			/** Sets the 'condition' statement to the database helper */
			$condition 	= "pattern_id ='".$pat_id."' AND metric_id = 3";

			/** Concatenate the description version to the 'condition' statement if the parameter is set. */
			$condition 	= $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");

			/** Gets the array of the result object from the database */
			$result 	= $this->d->select('*', $condition, 'assess_result');

			if($result != NULL){

				/** Runs through all result of PAP metric in the database */
				foreach ($result as $key => $value){

					/** Sets the 'condition' statement to the database helper for the result detail */
					$detail_cond 	= "result_id = ".$value['result_id'];

					/** Calls the 'select' command of the database helper */
					$detail 		= $this->d->select('*', $detail_cond, 'assess_result_detail');

					$result[$key]['detail'] = $detail;
				}
			}
			return $result;
		}

		/**
		 *	get_result_no_detail() is a model function for getting all result with no detail.
		 *
		 *	get_result_no_detail() is a model function for getting all result with no detail by passing
		 *	pattern ID and the version number as parameters.
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
			$condition 	= "pattern_id ='".$pat_id."' AND metric_id = 3";

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
			$condition 	= "metric_id = 3";

			/** Concatenate the 'condition' statement to the database helper. */
			$condition 	= $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");

			/** Calls the 'select' command of the database helper with the 'join' statement specified. */
			$result 	= $this->d->select_joined(	
												'metric_variable.id, metric_variable.metric_id, metric_variable.variable_name, metric_variable.variable_description, assess_result_detail.*', 
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
			$condition 	= "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 3";

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

			/** Sets the result object that uses to update in the database. */
			$result 	= array(
				'pattern_id' 	=> $pat_id,
				'desc_version' 	=> $ver,
				'metric_id' 	=> 3,
				'score' 		=> $this->calculate_result($data),
				'assessor_id' 	=> $ass_id
				);

			/** Sets the 'condition' statement to the database helper. */
			$cond 		= "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 3 AND assessor_id = ".$ass_id;

			/** Calls the 'update' command of the database helper. */
			$this->d->update($cond, 'assess_result',$result);

			/** Then, update detail of the result recursively. */
			$result_id 	= $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];
			foreach ($data as $key => $value) {
				$var_cond 	= "result_id = ".$result_id." AND variable_id = ".$key;
				$var 		= array(
					'result_id' 		=> $result_id,
					'variable_id' 		=> $key,
					'variable_score' 	=> $value
					);
				$this->d->update($var_cond, 'assess_result_detail', $var);
			}
		}

		/**
		 *	calculate_result() is a model function for calculating the final result from the passing parameters
		 *
		 *	calculate_result() is a model function for calculating the final result from the passing parameters
		 *	which is different from the PAP metric.
		 *
		 *	@param 		array 		$pat 		An array that represents the pattern object.
		 *	@param 		array 		$desc 		An array that represents the pattern description object.
		 *	@param 		integer 	$ass_id 	The assessor ID that is an integer.
		 *
		 *	@return 	array 					The result object that is the calculated result. 
		 */
		function calculate_result($data){
			$CP 	= (array_key_exists('71', $data) AND array_key_exists('72', $data) AND $data['72'] != 0.00)? $data['71']/$data['72']: NULL;
			$AP 	= (array_key_exists('73', $data) AND array_key_exists('74', $data) AND $data['74'] != 0.00)? $data['73']/$data['74']: NULL;
			$OP 	= (array_key_exists('75', $data) AND array_key_exists('76', $data) AND $data['76'] != 0.00)? $data['75']/$data['76']: NULL;
			$AscP 	= (array_key_exists('77', $data) AND array_key_exists('78', $data) AND $data['78'] != 0.00)? $data['77']/$data['78']: NULL;
			$GenP 	= (array_key_exists('79', $data) AND array_key_exists('80', $data) AND $data['80'] != 0.00)? $data['79']/$data['80']: NULL;
			$AgrP 	= (array_key_exists('81', $data) AND array_key_exists('82', $data) AND $data['82'] != 0.00)? $data['81']/$data['82']: NULL;
			$ComP 	= (array_key_exists('83', $data) AND array_key_exists('84', $data) AND $data['84'] != 0.00)? $data['83']/$data['84']: NULL;
			$NP 	= 0;
			if($CP 		!= NULL){ 	$NP++; } else { $CP 	= 0; }
			if($AP 		!= NULL){ 	$NP++; } else { $AP 	= 0; }
			if($OP 		!= NULL){ 	$NP++; } else { $OP 	= 0; }
			if($AscP 	!= NULL){ 	$NP++; } else { $AscP 	= 0; }
			if($GenP 	!= NULL){ 	$NP++; } else { $GenP 	= 0; }
			if($AgrP 	!= NULL){ 	$NP++; } else { $AgrP 	= 0; }
			if($ComP 	!= NULL){ 	$NP++; } else { $ComP 	= 0; }
			return (($CP + $AP + $OP + $AscP + $GenP + $AgrP + $ComP)/$NP)*100;
		}
	}
?>