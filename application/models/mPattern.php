<?php
	/**
	 *	mPattern is a model class that handles all pattern's CRUDE methods.
	 *
	 *	mPattern is a model class that handles all pattern's CRUDE methods, i.e. create, get,
	 *	update, and delete. This class provides 9 methods.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	*/
	class mPattern extends CI_Model{
		
		/**
		 *	__construct method is a constructor method for mPattern class
		 *
		 *	__construct method is a constructor method for mPattern class
		 *	which will load a set of necessarily libraries, models, and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection','d');
			$this->load->model('mPatternDesc', 'pd');
		}

		/**
		 *	create_pattern() is a model function for creating a new pattern record.
		 *
		 *	create_pattern() is a model function for creating a new pattern record into the database.
		 *	
		 *	@param 		array 	$data 		An array of pattern information which has keys as same as 
		 *									the column name.
		 *
		 *	@return 	boolean 			A boolean status for creating process.
		 */
		public function create_pattern($data){

			/** Sets a 'condition' string for using with the database helper. */
			$condition 	= "pattern_id ='".$data["pattern_id"]."'";
			
			/** Calls a mDBConnection class's create method for adding a new pattern into pattern table. */
			return $this->d->create('*', $condition, 'pattern', $data);
		}

		/**
		 *	get_pattern() is a model function to get a pattern by the specified pattern ID
		 *
		 *	get_pattern() is a model function to get a pattern object by using the specified pattern ID as 
		 *	a key for retrieval.
		 *
		 * 	@param 		string 	$id 		A pattern ID that is a string with the predefined format.
		 *	
		 *	@return 	array/boolean 		An array of pattern object with its corresponding information,
		 *									e.g. pattern ID, pattern name, etc.
		 */
		public function get_pattern($id){

			/** Sets a 'condition' string for using with the database helper. */
			$condition 	= "pattern_id = '".$id."'";

			/** Calls a select method of mDBConnection class to get an array of pattern information */
			$pattern 	= $this->d->select('*', $condition, 'pattern', 1);

			/** 
			 *	Checks whether the query result is a boolean or not. The query result will be false when
			 *	there is no pattern with the specified pattern ID in the database.
			 */
			if(is_bool($pattern)){
				return $pattern;
			}

			/** Otherwise, returns an array of retrieved pattern information (only one row). */
			return $pattern[0];
		}

		/**
		 *	get_pattern_all() is a model function to get all existing pattern in the database.
		 *
		 *	get_pattern_all() is a model function to get all existing pattern records in the database
		 *	and return it as an array which has keys as same as the column name.
		 *
		 *	@return 	array 		An array of pattern information which has keys as same as the column
		 *							name. 
		 *							Example Data:
		 *							[0]		=>	['pattern_id']		=>	'P-01',
		 *									=>	['pattern_name'] 	=>	'ABC Pattern',
		 *									=>	...
		 *							...
		 */
		public function get_pattern_all(){
			$condition 	= "";

			/** Calls a select function of mDBConnection class to retrieve all pattern information. */
			$pattern 	= $this->d->select('*', $condition, 'pattern');

			/** Returns an array of pattern information */
			return $pattern;
		}

		/**
		 *	get_only_ready_pattern_no_assessed() is a model function to get some pattern that is not assessed
		 *
		 *	get_only_ready_pattern_no_assessed() is a model function to get some pattern that is not assessed
		 *	and its status is ready. This function is used in the summary section of the dashboard. It can be
		 *	configure to retrieve only the patterns of the specified pattern creator by passing pattern
		 *	developer ID to the function.
		 *
		 *	@param 		integer 	$dev_id 	An integer that indicates the specific pattern developer ID.
		 *
		 *	@return 	array 		An array of patterns information for the specified pattern developer.
		 */
		public function get_only_ready_pattern_no_assessed($dev_id = NULL){

			/** Retrieve all pattern information in the database. */
			$pat_list = $this->get_pattern_all();

			/** Checks whether the pattern list is empty or the function returns false or not*/
			if(!is_bool($pat_list)){
				
				/** 
				 * 	If the pattern developer ID is specified, the pattern array is filtered only 
				 *	the patterns that are created by the specified pattern developer only.
				 */
				if ($dev_id != NULL) {
					$pat_list 	= array_filter($pat_list, function($pat) use($dev_id){
						return ($pat['pattern_creator_id'] == $dev_id);
					});
				}

				/** If not, the array will be filtered which only the patterns that its status and assess 
				 * count is zero are kept. 
				 */
				return array_filter($pat_list, function($pat) {
					$cond 		= "pattern_id ='".$pat['pattern_id']."' AND desc_version =".(float)$pat['pattern_assess_version'];
					$counter 	= $this->d->select('desc_assess_count', $cond, 'pattern_description')[0]['desc_assess_count'];
					return $pat['pattern_status'] == 'Ready' AND $counter == 0;
				});

			}
		}

		/**
		 *	get_pattern_by_developer() is a model function for fetching patterns of the specified developer
		 *
		 *	get_pattern_by_developer() is a model function for fetching patterns of the specified developer by
		 *	using the input developer ID.
		 *
		 *	@param 		integer 	$dev_id 	The pattern developer ID that is an integer.
		 *
		 *	@return 	array 		An array of patterns which are corresponding to the specified developer.
		 */
		public function get_pattern_by_developer($dev_id){

			/** Sets a 'condition' string to pass to the database helper */
			$condition 	= "pattern_creator_id = ".$dev_id."";

			/** Fetches the pattern list from 'pattern' table using the database helper command */
			$pattern 	= $this->d->select('*', $condition, 'pattern', 1);
			return $pattern;
		}

		/**
		 *	get_pattern_unreach_limit_assess() is a model function for fetching an unreached-limit patterns 
		 *	list.
		 *
		 *	get_pattern_unreach_limit_assess() is a model function for fetching an unreached-limit patterns
		 *	list for the specified pattern developer. The input developer ID can leave it as NULL for fetching 
		 * 	all patterns without limiting the developer.
		 *
		 *	@param 		integer 	$dev_id 	The pattern developer ID that is an integer.
		 *
		 *	@return 	array 		An array of patterns which its assessment count is not exceed its limit.
		 */
		public function get_pattern_unreach_limit_assess($dev_id=NULL){

			/** Fetches all pattern in the database into an array */
			$pat_list 	= $this->get_pattern_all();

			/** Checks whether there is any pattern in the database or not. */
			if(!is_bool($pat_list)){

				/** If the developer ID is specified, filters only patterns that relate to the developer ID */
				if($dev_id != NULL){
					$pat_list 	= array_filter($pat_list, function($pat) use($dev_id){
						return ($pat['pattern_creator_id'] == $dev_id);
					});
				}

				/** 
				 * 	returns an array that filters only the pattern that its assessment count is not exceed
				 *	its limit.
				 */
				return array_filter($pat_list, function($pat){
					$cond 		= "pattern_id ='".$pat['pattern_id']."' AND desc_version =".(float)$pat['pattern_assess_version'];
					$counter 	= $this->d->select('desc_assess_count', $cond, 'pattern_description')[0]['desc_assess_count'];
					return (($pat['pattern_assess_limit'] == 0)? true:($counter < $pat['pattern_assess_limit'])) AND $pat['pattern_status'] != 'Disable';
			});
			}
			else{
				return $pat_list;
			}
		}

		/**
		 *	get_pattern_reach_limit_assess() is a model function for fetching an reached-limit patterns 
		 *	list.
		 *
		 *	get_pattern_reach_limit_assess() is a model function for fetching an reached-limit patterns
		 *	list for the specified pattern developer. The input developer ID can leave it as NULL for fetching 
		 * 	all patterns without limiting the developer.
		 *
		 *	@param 		integer 	$dev_id 	The pattern developer ID that is an integer.
		 *
		 *	@return 	array 		An array of patterns which its assessment count is exceed its limit.
		 */
		public function get_pattern_reach_limit_assess($dev_id=NULL){

			/** Fetches all pattern in the database into an array */
			$pat_list 	= $this->get_pattern_all();

			/** Checks whether there is any pattern in the database or not. */
			if(!is_bool($pat_list)){

				/** If the developer ID is specified, filters only patterns that relate to the developer ID */
				if($dev_id != NULL){
					$pat_list 	= array_filter($pat_list, function($pat) use($dev_id){
						return ($pat['pattern_creator_id'] == $dev_id);
					});
				}

				/** 
				 * 	returns an array that filters only the pattern that its assessment count is exceed
				 *	or equal to its limit.
				 */
				return array_filter($pat_list, function($pat) {

					$cond 		= "pattern_id ='".$pat['pattern_id']."' AND desc_version =".(float)$pat['pattern_assess_version'];
					$counter 	= $this->d->select('desc_assess_count', $cond, 'pattern_description')[0]['desc_assess_count'];
					return (($pat['pattern_assess_limit'] == 0)? false:($counter >= $pat['pattern_assess_limit'])) AND $pat['pattern_status'] != 'Disable';
			});
			}
			else{
				return $pat_list;
			}
		}

		/**
		 *	update_pattern() is a model function for updating pattern data in the database.
		 *
		 *	update_pattern() is a model function for updating pattern data in the database follows the data
		 *	from the input array.
		 *
		 *	@param 		array 	$data 		An array of the pattern data which has keys as same as the
		 *									column name.
		 *
		 *	@return 	boolean 			A boolean that represents the update process status.
		 */
		public function update_pattern($data){

			/** Sets a 'condition' string for the database helper. */
			$condition 		= "pattern_id ='".$data['pattern_id']."'";

			/** Calls an update command of the database helper. */
			return $this->d->update($condition, 'pattern', $data);
		}

		/**
		 *	delete_pattern() is a model function for deleting a pattern data from the database.
		 *
		 *	delete_pattern() is a model function for deleting a pattern data from the database follows the
		 *  specified pattern id.
		 *
		 *	@param 		string 		$id 	The pattern Id that is a string with predefined format.
		 *
		 *	@return 	boolean 	A boolean that represents the delete process status
		 */
		public function delete_pattern($id){

			/** Sets a 'condition' statement for the database helper. */
			$condition 	= "pattern_id ='".$id."'";

			/** Calls a 'delete' command of the database helper and stores the status. */
			$pattern 	= $this->d->delete('*', $condition, 'pattern', array('pattern_id' => $id));

			/** Checks whether there is any pattern description related to the deleted pattern or not. */
			if(!empty($this->d->select('*', $condition, 'pattern_description'))){
				
				/** If so, continues to delete the pattern description in the database as well. */
				$desc 	= $this->d->delete('*', $condition, 'pattern_description', array('pattern_id' => $id));
				return ($pattern AND $desc);
			}
			else{
				return $pattern;
			}
			
		}
	}
?>