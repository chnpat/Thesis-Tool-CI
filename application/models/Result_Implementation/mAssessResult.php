<?php 
	/**
	 *	mAssessResult is a model class for managing the assessment result object.
	 *	
	 *	mAssessResult is a model class for managing the assessmnet result object that includes a simple CRUDE 
	 *	operations, i.e. get function only. This class provides 3 functions.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mAssessResult extends CI_Model{
		
		/**
		 *	__construct method is a constructor method for mAssessResult
		 *
		 *	__construct method is a constructor method for mAssessResult class
		 *	which will load a set of necessarily libraries, models, and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct()
		{
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		/**
		 *	get_result_group_by_assessor() is a model function for getting a result that grouped by assessor.
		 *
		 *	get_result_group_by_assessor() is a model function for getting a result that grouped by all assessor
		 *	This function will return a list of assessor that assessed the specified pattern description.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a floating
		 *										number.
		 *
		 *	@return 	array 					An array that contain a list of assessor ID who assessed the 
		 *										specified pattern.
		 */
		public function get_result_group_by_assessor($pat_id, $ver = NULL){

			/** Sets a 'condition' statement to the database helper. */
			$condition = "pattern_id = '".$pat_id."'".(($ver != NULL)?" AND desc_version = ".$ver: "");

			/** Calls a 'select' command of the database helper with the group statement specified. */
			return $this->d->select_grouped('result_id, assessor_id', $condition, 'assess_result', 'assessor_id');
		}

		/**
		 *	get_all_result_by_pattern() is a model function for getting all results without separating metrics.
		 *
		 *	get_all_result_by_pattern() is a model function for getting all existing results in the database of 
		 *	the specified pattern without separating into each metric.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with pre-defined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a floating
		 *										number.
		 *
		 *	@return 	array 					An array of assessment results that matches the specified
		 *										pattern.
		 */
		public function get_all_result_by_pattern($pat_id, $ver = NULL){

			/** Sets 'condition' statement to the database helper. */
			$condition = "pattern_id = '".$pat_id."'".(($ver != NULL)?" AND desc_version = ".$ver: "");

			/** Calls the 'select' command of the database helper with specifying the 'join' statement. */
			return $this->d->select_joined( 'metric.*, assess_result.*', 
											$condition, 
											'assess_result',
											'metric',
											'assess_result.metric_id = metric.id',
											'left');
		}

		/**
		 *	get_all_result_detail_w_metric() is a model function to get the assessment result that is mapped
		 *	to the metric description.
		 *
		 *	get_all_result_detail_w_metric() is a model function to get the assessment result that is mapped to
		 *	the metric description. It will return a result array which joined the metric description already.
		 *
		 *	@param 		integer 	$result_id 		The result ID that is an integer.
		 *
		 *	@return 	array 						An array of assessment results that is mapped with the
		 *											metric description.
		 */
		public function get_all_result_detail_w_metric($result_id){

			/** Sets the 'condition' statement to the database helper. */
			$condition = "result_id = ".$result_id;

			/** Calls the 'select' command of the database helper with the 'join' statement specified. */
			return $this->d->select_joined(	'metric_variable.id, metric_variable.metric_id, metric_variable.variable_name, metric_variable.variable_description, assess_result_detail.*', 
												$condition,
												'assess_result_detail',
												'metric_variable',
												'assess_result_detail.variable_id = metric_variable.id',
												'left' );
		}
	}
?>