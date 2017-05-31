<?php
	/**
	 *	mPatternDesc is a model class for managing a pattern description information
	 *	
	 *	mPatternDesc is a model class for managing a pattern description information in the database.
	 *	It also handle the basic CRUDE operations, i.e. create, get, update, and delete.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mPatternDesc extends CI_Model{
		
		/**
		 *	__construct method is a constructor method for mPatternDesc
		 *
		 *	__construct method is a constructor method for mPatternDesc class
		 *	which will load a set of necessarily libraries and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		/**
		 *	create_pattern_description() is a model function for creating a new pattern description.
		 *	
		 *	create_pattern_description() is a model function for creating a new pattern description in the
		 *	database. It receives the input data from the view and calls the database helper.
		 *
		 *	@param 		array 	$data 		An array of a pattern description object that has keys as same
		 *									as the column name.
		 *
		 *	@return 	boolean 			A boolean that represents the create process status.
		 */
		public function create_pattern_description($data){
			/**	Sets a 'condition' string to the database helper. */
			$condition 		= "pattern_id ='".$data["pattern_id"]."' AND desc_version = ".$data['desc_version'];

			/** Calls a 'create' function of the database helper and inserts into 'pattern_description' table */
			return $this->d->create('*', $condition, 'pattern_description', $data);
		}

		/**
		 * 	get_pattern_description() is a model function for fetching the pattern description.
		 *
		 *	get_pattern_description() is a model function for fetching the pattern description from the 
		 *	database by specifying the pattern description ID.
		 *
		 *	@param 		integer 	$id 	An integer that indicates the pattern description ID
		 *
		 *	@return 	array 				An array of the pattern description object that matches the
		 *									specified ID.
		 */
		public function get_pattern_description($id){

			/** Sets a 'condition' statement to the database helper. */
			$condition 		= "id ='".$id."'";

			/** Calls 'select' command of the database helper. */
			return $this->d->select('*',$condition, 'pattern_description',1)[0];
		}

		/**
		 * 	get_pattern_description_by_pattern() is a model function for fetching the pattern description.
		 *
		 *	get_pattern_description_by_pattern() is a model function for fetching the pattern description from
		 *	the database by specifying the pattern ID and/or the version number.
		 *
		 *	@param 		string 		@pat_id 	The pattern ID that is a string with the predefined format.
		 * 	@param 		float 		@ver 		The version number that is a floating number.
		 *
		 * 	@return 	array 					An array of pattern description objects that matches the 
		 *										pattern ID and/or the version number.
		 */
		public function get_pattern_description_by_pattern($pat_id, $ver = NULL){

			/** Checks whether the version number is specified or not. */
			if($ver != NULL){
				/** If so, sets the 'condition' statement to the database helper differently. */
				$condition 	= "pattern_id = '".$pat_id."' AND desc_version ='".$ver."'";
			}
			else{
				/** Otherwise, the 'condition' statement is set only the pattern ID */
				$condition 	= "pattern_id ='".$pat_id."'";
			}

			/** Calls the 'select' command of the database helper. */
			return $this->d->select('*', $condition, 'pattern_description');
		}

		/**
		 *	update_pattern_description() is a model function for updating pattern description in the database,
		 *
		 *	update_pattern_description() is a model function for updating pattern description in the database 
		 *	by passing the pattern description object and specifying the pattern ID and/or the pattern
		 *	description ID.
		 *
		 *	@param 		array 		$data 		An array of the pattern description object that has keys as
		 *										same as the column name.
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		integer 	$id 		The pattern description ID that is an integer.
		 *
		 *	@return 	boolean 				A boolean that indicates the update status.
		 */
		public function update_pattern_description($data, $pat_id=NULL, $id=NULL){

			/** 
			 * 	Checks whether the parameters are passed or not. Different parameters passing will make
			 *	the 'condition' statement is difference as well.
			 */
			if($id != NULL AND $pat_id == NULL){
				$condition 	= "id = ".$id;
			}
			else if($id == NULL AND $pat_id != NULL){
				$condition 	= "pattern_id = '".$pat_id."'";
			}
			else if($id != NULL AND $pat_id != NULL ){
				$condition 	= "id = ".$id." AND pattern_id ='".$pat_id."'";
			}
			else{
				$condition 	= "";
			}

			/** Calls the 'update' command of the database helper. */
			return $this->d->update($condition, "pattern_description", $data);
		}

		/**
		 * 	update_assess_count() is a model function for updating the assessment count.
		 *
		 *	update_assess_count() is a model function for updating the assessment count of the specified
		 *	pattern ID and/or the version number. The function requires 3 paramenters passing, i.e. the
		 *	pattern ID, the version number, and the pattern description object for updating.
		 *
		 *	@param 		string 		$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		float 		$ver 		The version number of the pattern description that is a 
		 *										floating number.
		 *	@param 		array 		$data 		An array of the pattern description object that specifies only
		 *										the desc_assess_count column.
		 *
		 *	@return 	boolean 				A boolean that indicates the 'update' process status.
		 */
		public function update_assess_count($pat_id, $ver, $data){

			/** Sets a 'condition' statement to the database helper. */
			$condition 		= "pattern_id = '".$pat_id."' AND desc_version = ".$ver;
			
			/** Calls the 'update' command for the database helper. */
			return $this->d->update($condition, "pattern_description", $data);
		}

		/**
		 *	delete_pattern_description() is a model function for deleting the specified pattern description.
		 *
		 *	delete_pattern_description() is a model function for deleting the specified pattern description
		 * 	follows the specified pattern description ID.
		 *
		 *	@param 		integer 	$id 	The pattern description ID that is an integer.
		 *
		 *	@return 	boolean 			A boolean that indicates the 'delete' process status.
		 */
		public function delete_pattern_description($id){

			/** Sets a 'condition' statement to the database helper. */
			$condition 		= "id = ".$id;
			
			/** Calls the 'delete' command of the database helper. */
			return $this->d->delete('*', $condition, 'pattern_description', array('id' => $id));
		}
	}
?>