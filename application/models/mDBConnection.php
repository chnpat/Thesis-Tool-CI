<?php 
	/**
	 *	mDBConnection is a model class for handling database connection.
	 *
	 *	mDBConnection is a model class for handling database operation, i.e. create, select,
	 *	update, and delete. This class provides 6 methods.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mDBConnection extends CI_Model{
		
		/**
		 *	__construct method is a constructor method for mDBConnection
		 *
		 *	__construct method is a constructor method for mDBConnection class
		 *	which will load a set of necessarily libraries and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent::__construct();
		}

		/**
		 *	create() is one of CRUDE functions for creating data in a database.
		 *
		 *	create() is one of CRUDE functions for creating data in a database. It will call
		 *	database helper to set all necessary query string.
		 *
		 *	@param 		string 	$select 	The 'select' string for specifying which column to query.
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		array 	$data 		The array of data that has a key which is the same as
		 *									database column name.
		 *
		 * 	@return 	boolean 	The 'create' status => 1 if the creation is successful. 0, Otherwise.
		 */
		public function create($select, $cond, $from, $data){

			/** Sets the 'select' statement to database helper. */
			$this->db->select($select);

			/** If the condition is not empty, sets the 'where' statement to database helper */
			if($cond != ""){
				$this->db->where($cond);
			}

			/** Sets the 'from' table to database helper */
			$this->db->from($from);

			/** Gets the data for the specified query and checks the number of result rows. */
			if($this->db->get()->num_rows() == 0){
				/** 
				 * If the insert data is not duplicated, operates the insert command to insert data 
				 * to database. 
				 */
				$this->db->insert($from, $data);

				/** Checks the number of affected row is greater than 0 or not. */
				if($this->db->affected_rows() > 0){
					return true;
				}
			}
			return false;
		}

		/**
		 *	select() is one of CRUDE functions for retrieving data from database.
		 *
		 * 	select() is one of CRUDE functions for retrieving data from database. It will call
		 *	database helper to set all necessary query string.
		 *
		 *	@param 		string 	$select 	The 'select' string for specifying which column to query.
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		string 	$limit 		The number of results that are limited when querying.
		 *
		 *	@return 	Array/boolean 		The query result array that has the same key as column name.
		 *									Otherwise, the boolean will be returned if no data to retrieve.
		 */
		public function select($select, $cond, $from, $limit = NULL){

			/** Sets the 'select' statement to database helper. */
			$this->db->select($select);

			/** If the 'condition' string is not empty, sets the 'where' statement to database helper. */
			if($cond != ""){
				$this->db->where($cond);
			}

			/** Sets the 'from' table to database helper. */
			$this->db->from($from);

			/** If the limit is set, sets the limit number to database helper. */
			if($limit != NULL){
				$this->db->limit($limit);
			}

			/** get the query result according to the specified query string. */
			$query = $this->db->get();

			/** If the query result has more than 0 row, returns the result array */
			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				/** Otherwise, returns the false boolean. */
				return false;
			}
		}

		/**
		 *	select_joined() is a model method for retrieving data from the database.
		 *
		 * 	select_joined() is a model method for retrieving data from the database. This method also allows
		 *	select command to specify to join the table. It will call database helper to set all necessary 
		 *	query string.
		 *
		 *	@param 		string 	$select 	The 'select' string for specifying which column to query.
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		string 	$join_tbl	The string that specifies which table to join with 'from' table	
		 *	@param 		string 	$join_cond	The string that specifies the condition for joining table.
		 *	@param 		string 	$join_type	The string that specifies type of joining 'left|right|inner'
		 *
		 *	@return 	Array/boolean 		The query result array that has the same key as column name.
		 *									Otherwise, the boolean will be returned if no data to retrieve.
		 */
		public function select_joined($select, $cond, $from, $join_tbl, $join_cond, $join_type = NULL){
			
			/** Sets a 'select' statement to the database helper. */
			$this->db->select($select);
			
			/** If the 'condition' string is not empty, sets a 'where' statement to the database helper. */
			if($cond != "")			{	$this->db->where($cond);	}
			
			/** Sets the 'from' table to database helper. */
			$this->db->from($from);
			
			/** If the 'join_type' is specified, sets the join command to the database helper. */
			if($join_type != NULL) 	{	$this->db->join($join_tbl, $join_cond, $join_type);		}
			else 					{	$this->db->join($join_tbl, $join_cond);					}

			/** Executes the query and retrieves the result in $query */
			$query = $this->db->get();
			
			/** 
			 * 	Checks whether the number of result rows are greater than 0 or not. If so, returns an
			 *	array of query result. Otherwise, returns false as a boolean.
			 */
			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}
		}

		/**
		 *	select_grouped() is a model method for retrieving the data which are grouped.
		 *
		 *	select_grouped() is a model method for retrieving the data which are grouped. The 'groupby'
		 *	statement can be specified to group the data together. It will call database helper to set
		 *	all necessary query string.
		 *
		 *	@param 		string 	$select 	The 'select' string for specifying which column to query.
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		string 	$groupby 	The 'groupby' string that specifies the column to group data.
		 *
		 *	@return 	array/boolean 		The query result array that has the same key as column name.
		 *									Otherwise, the boolean will be returned if no data to retrieve.
		 */
		public function select_grouped($select, $cond, $from, $groupby){

			/** Sets a 'select' statement to the database helper. */
			$this->db->select($select);

			/** If the 'condition' string is not empty, sets a 'where' statement to the database helper. */
			if($cond != ""){
				$this->db->where($cond);
			}

			/** Sets a 'from' table to the database helper. */
			$this->db->from($from);

			/** Sets a 'group_by' column to the database helper to specify where to group. */
			$this->db->group_by($groupby);

			/** Retrieves a query result from the specified query string. */
			$query = $this->db->get();

			/**
			 *	If the query results have more than one row, return the result as an array with column name 
			 *	as a key. If not, return a false boolean.
			 */
			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}
		}

		/**
		 *	update() is a model function that gets the data and updates the record in the database.
		 *
		 *	update() is a model function that gets the edited data array and updates the record in
		 * 	the database. It will call the database helper to specify all necessary query string.
		 *
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		array 	$data 		An array of edited data that has keys as same as column name.
		 *
		 *	@return 	boolean 			A boolean value that acknowledges the update status (pass/fail)	
		 */
		public function update($cond, $from, $data){

			/** If the 'condition' string is not empty, sets the 'where' statement to the database helper. */
			if($cond != ""){
				$this->db->where($cond);
			}

			/** Performs an update process by calling 'update' function from database helper class. */
			$this->db->update($from, $data);

			/** 
			 * 	Checks whether there is only one affected row from the 'update' process. If so, 
			 *	returns the update status as true. Otherwise, returns false.
			 */
			if($this->db->affected_rows() == 1){
				return true;
			}
			else{
				return false;
			}
		}

		/**
		 * 	delete() is a model function for deleting some records from the database.
		 *
		 * 	delete() is a model function for deleting some records from the database by using an array
		 *	to specify the delete condition
		 *
		 *	@param 		string 	$select 	The 'select' string for specifying which column to query.
		 *	@param 		string 	$cond 		The 'condition' string for checking the existence.
		 *	@param 		string 	$from 		The 'from' string that specifies which table to query.
		 *	@param 		array 	$del_arr	An array that specifies the delete condtion, e.g.
		 *									Array('id' => 10)    =   DELETE FROM table WHERE id = 10
		 *
		 *	@return 	boolean 			The boolean result of the delete process.
		 */
		public function delete($select, $cond, $from, $del_arr){
			
			/** Sets a 'select' statement to the database helper, */
			$this->db->select($select);

			/** If the 'condition' statement is not empty, sets a 'where' statement to the database helper. */
			if($cond != ""){
				$this->db->where($cond);
			}

			/** Sets a 'from' table to the database helper. */
			$this->db->from($from);

			/** 
			 * 	Checks whether the result from the above condtion is existed in the database or not.
			 *	If so, operates the delete command from the database helper class by passing an array of
			 *	delete condition. The success of the delete process will be acknowledged by returning true as 
			 * 	a boolean. Otherwise, returns false.
			 */
			if($this->db->get()->num_rows() > 0){
				$this->db->delete($from, $del_arr);
				return true;
			}
			return false;
		}
	}

?>