<?php
	/**
	 *	mLogin is a model class for handling all user-oriented process.
	 *
	 *	mLogin is a model class for handling all user-oriented process, i.e. create user, get user,
	 *	update user, delete user, login, and change user password. This class provides 8 methods.
	 *
	 *	@package 	Model
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class mLogin extends CI_Model{

		/**
		 *	__construct method is a constructor method for mLogin class.
		 *
		 *	__construct method is a constructor method for mLogin class
		 *	which will load a set of necessarily libraries and helpers for use.
		 * 
		 *	@return		void
		 */
		public function __construct(){
			parent:: __construct();
		}

		/**
		 *	create_user() is a model function for creating a new user account.
		 *
		 *	create_user() is a model function for creating a new user account by getting a validated
		 *	user information from the registration form. Then, inserts into the database.
		 *
		 *	@param 		array 	$data 		An array of creating user account information that has a key
		 *									as same as the column name.
		 *	@return 	boolean 			A boolean that indicates the creating status.
		 */
		public function create_user($data){
			
			/** Set a 'condition' string for using with the database helper. */
			$condition 		= "user_email ="."'".$data['user_email']."'";
			
			/** Sets 'select', 'where', 'limit' statements and a 'from' table to the database helper. */
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);

			/** Gets the query result for the specified query string. */
			$query 			= $this->db->get();

			/** Checks if the user account already existed in the database */
			if($query->num_rows() == 0){ 

				/** Inserts the user account information into the database using data array. */
				$this->db->insert('user_login', $data); 

				/** Checks whether the afftect row is more than zero or not. If so, returns true. */
				if($this->db->affected_rows() > 0){
					return true;
				}
			}
			else{
				/** Otherwise, returns false. */
				return false;
			}
		}

		/**
		 * 	get_user() is a model function for getting the user account information.
		 *
		 *	get_user() is a model function for getting the user account information as an array
		 *	from the database based on the specified information in the passing data array.
		 *
		 * 	@param 		array 	$data 	a data array that specifies the email address of the interest user.
		 *
		 *	@return 	array/boolean	An array of the user account information including user name, email,
		 *								encrypted password, etc.
		 */
		public function get_user($data){
			/** Sets a 'condition' statement to the database helper. */
			$condition 		= "user_email ="."'".$data['email']."'";

			/** Sets 'select', 'where', 'limit' statements and a 'from' table to the database helper. */
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);

			/** Gets the query result from the database. */
			$query = $this->db->get();   

			/** Checks whether the specified user account is currently exisied in the database or not. */
			if($query->num_rows() == 1){

				/** If so, returns an array of the query result */
				return $query->result_array(); 
			}
			else{
				/** If not, returns a boolean as false to indicate the failure retrieval. */
				return false;
			}
		}

		/**
		 * 	get_user_by_id() is another model function for getting user account information.
		 *
		 *	get_user_by_id() is another model function for getting user account information from the database
		 *	by using a user ID instead of the email address as in get_user() function.
		 *
		 *	@param 		integer 	$id 	An user ID that is an integer to identify the user uniqueness.
		 *
		 *	@return 	array/boolean 		An array of user account information including user name, email,
		 *									encrypted password, etc.
		 */
		public function get_user_by_id($id){

			/** Sets the 'condition' string by using the user ID from the function parameter */
			$condition 	= "id = ".$id;

			/** Sets 'select', 'where', 'limit' statements and a 'from' table to the database helper. */
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);

			/** Get the query result from the database. */
			$query 		= $this->db->get();

			/** Checks whether the query result has only one user account or not. */
			if($query->num_rows() == 1){

				/** If so, returns an array of the query result. */
				return $query->result_array();
			}
			else{
				/** If not, return a boolean as false to indicate the failure retrieval. */
				return false;
			}
		}

		/**
		 *	get_user_all() is another function for getting all user account information.
		 *
		 *	get_user_all() is another function for getting all user account information without specific
		 *	user to retrieve.
		 *
		 *	@return 	array 		An array of all user accounts information in the database.
		 */
		public function get_user_all(){
			/** Sets a 'select' statement to the database helper. */
			$this->db->select('*');

			/** Sets a 'from' table to the database helper. */
			$this->db->from('user_login');

			/** Returns the query result as an array. */
			return $this->db->get()->result_array();
		}

		/**
		 *	update_user() is a model function for updating an existing user account information.
		 *
		 *	update_user() is a model function for updating an existing user account information according
		 *	to the specified $data array.
		 *
		 *	@param 		array 	$data 		An array of user account information that has keys as same as
		 *									the column name.
		 *
		 *	@return 	boolean 			A boolean status for the updating process.
		 */
		public function update_user($data){

			/** Sets a 'condition' string for using with the database helper. */
			$condition 		= "id =". $data['id'];

			/** Set a 'where' statement to the database helper. */
			$this->db->where($condition);

			/** Operates the 'update' command from the database helper class. */
			$this->db->update('user_login', $data);

			/** Checks whether the affected row is equal to one. Then, returns the update boolean status */
			if($this->db->affected_rows() == 1){
				return true;
			}
			else{
				return false;
			}
		}

		/**
		 *	detele_user() is a model function for deleting an existing user account.
		 *
		 *	delete_user() is a model function for deleting an existing user account from 
		 *	the database according to the specified user ID.
		 *
		 *	@param 		integer 	$id 	An integer of the specified user ID for deleting.
		 *
		 *	@return 	boolean 			A boolean status of the deleting process.
		 */
		public function delete_user($id){

			/** Sets 'select' and 'where' statement to the database helper. */
			$this->db->select('*');
			$this->db->where('id', $id);

			/** Sets 'from' table to the database helper. */
			$this->db->from('user_login');

			/** Gets the query result and checks whether the deleting user account is existing. */
			if($this->db->get()->num_rows() > 0){
				/** Operates the delete command from the database helper class by passing an user ID. */
				$this->db->delete('user_login', array('id' => $id));

				/** If the user account is deleted successfully, return true as a boolean status. */
				return true;
			}
			else{
				/** If not, return false. */
				return false;
			}
		}

		/**
		 *	login() is a model function that checks user authentication.
		 *
		 *	login() is a model function that checks user authentication by matching the user email and
		 *	the encrypted password with the one in the database. Then, return the login status as a boolean.
		 *
		 *	@param 		array 	$data 		An array of user login informatio, i.e. user email and encrypted
		 *									password which has keys as same as the column name.
		 *
		 *	@return 	boolean 			A login result as a boolean.
		 */
		public function login($data){
			/**
			 *	Sets a 'condition' string for the database helper by getting user email and encrypted password
			 *	from the passing $data array. NOTE that the user status must be 1 as well because it means that
			 *	the account has already been enabled by administrator.
			 */
			$condition 	= "user_email =" . "'" . $data['email'] . "' AND " . "user_password =" . "'" . $data['password'] . "' AND user_status = 1";

			/** Sets 'select', 'where', 'limit' statements and 'from' table to the database helper. */
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);

			/** Gets the query result for the specified query string */
			$query 		= $this->db->get();

			/** 
			 *	Checks whether the query result is existing or not. If so, returns true. 
			 *	Otherwise, return false 
			 */
			return ($query->num_rows() == 1)? true:false;
		}

		/**
		 *	change_password() is a model function to handle change password process.
		 *
		 *	change_password() is a model function to handle change password process by matching
		 *	user email and encrypted old password to the one in the database. If it's matched, 
		 *	it will allow to change the password to a new ecrypted one.
		 *
		 *	@param 		string 	$email 		A string of user email address which uses for validating.
		 *	@param 		string 	$old 		An ecnrypted old password which uses for validating.
		 *	@param 		string 	$new 		An encripted new password which will update in the database.
		 *
		 *	@return 	boolean/string		A update process result status.
		 */
		public function change_password($email, $old, $new){

			/** 
			 *	Sets a 'condition' string for using with the database helper by using email and old password 
			 *	from the passing parameters.
			 */
			$condition 	= "user_email = '".$email."' AND user_password ='".$old."'";

			/** Sets 'select', 'where', 'limit' statements and a 'from' table to the database helper.  */
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);

			/** Gets the query result for the specified query string. */
			$query 		= $this->db->get();

			/** Checks whether the user is currently existing or not. If not, the password cannot be changed */
			if($query->num_rows() > 0){

				/** If so, sets the update data array for passing the update_user() function. */
				$update_data = array(
					'user_email' 	=> $email,
					'user_password' => $new
					);

				/** Calls the update_user() function to update the user password */
				$result 	= $this->update_user($update_data);
				return $result;
			}
			else{
				return 'Old password is not matched with the data in the database';
			}
		}
	}

?>