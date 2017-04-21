<?php
	/****************************************
	*	QAM-OODP Support Tool
	*****************************************
	*	Class: mLogin
	*	Developer: Charnon Pattiyanon
	*	Create on: 20/04/17
	*
	*
	*	Description: This class is a model class for computing the
	*	login or user management logics. This class provides 3 methods
	*	including:
	*		create_user()   => it uses for creating a new user account.
	*		get_user()		=> it uses for get user information.
	*		login()			=> it checks user authority for logging in the tool.
	*****************************************
	*/
	class mLogin extends CI_Model{
		/****************************************** 
		*	Function: create_user(array data): boolean
		*******************************************
		*	input:	(1) data 	type: array 	=> contains user information including username, password, email.
		*	output:	
		*		type: 	boolean
		*		description: 	the creation status as a boolean. 
		*						true means the new user account is created successfully.
		*						Otherwise, the new user account is duplicated with the existing account.
		*	Description: 	This function used to get the validated user information from the registration form and
		*					insert the information into the system database.
		*******************************************
		*/
		public function create_user($data){
			$condition = "user_email ="."'".$data['user_email']."'";
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 0){	// check if the user account already existed in the database
				$this->db->insert('user_login', $data); // insert the user account into database
				if($this->db->affected_rows() > 0){
					return true;
				}
			}
			else{
				return false;
			}
		}
		/****************************************** 
		*	Function: get_user(array data): array
		*******************************************
		*	input:	(1) data 	type: array 	=> contains user email address.
		*	output:	
		*		type: 	array || boolean
		*		description: 	the user account information array. 
		*						if the user account existed in the database, the function will return an array of
		*						user account information including user_name, user_email, user_status, user_role, 
		*						user_password.
		*						Otherwise, it returns boolean false if the user account is not existed.
		*	Description: 	This function used to get the user information from the system database.
		*******************************************
		*/
		public function get_user($data){
			$condition = "user_email ="."'".$data['email']."'";
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();   // query user account information from database using email as a key.
			if($query->num_rows() == 1){ // check if the query result is existed.
				return $query->result_array(); // returns as an array of query result. array(array('user_name' => xxx, ...))
			}
			else{
				return false;
			}
		}

		public function get_user_by_id($id){
			$condition = "id = ".$id;
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1){
				return $query->result_array();
			}
			else{
				return false;
			}
		}

		public function get_user_all(){
			$this->db->select('*');
			$this->db->from('user_login');
			return $this->db->get()->result_array();
		}

		public function update_user($data){
			$condition = "id =". $data['id'];
			$this->db->where($condition);
			$this->db->update('user_login', $data);
			if($this->db->affected_rows() == 1){
				return true;
			}
			else{
				return false;
			}
		}

		public function delete_user($id){
			$this->db->select('*');
			$this->db->where('id', $id);
			$this->db->from('user_login');
			if($this->db->get()->num_rows() > 0){
				$this->db->delete('user_login', array('id' => $id));
				return true;
			}
			else{
				return false;
			}
		}

		/****************************************** 
		*	Function: login(array data): boolean
		*******************************************
		*	@param	(1) data 	type: array 	=> contains user information including email, password.
		*	@return	
		*		type: 	boolean
		*		description: 	the login status as a boolean. 
		*						true means the user account submitted for login is valid.
		*						Otherwise, the user account submitted is invalid.
		*	Description: 	This function used to get the login status by checking username and password.
		*******************************************
		*/
		public function login($data){
			$condition = "user_email =" . "'" . $data['email'] . "' AND " . "user_password =" . "'" . $data['password'] . "' AND user_status = 1"; // user_status should be 1 which means the account is verified by administrators
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			return ($query->num_rows() == 1)? true:false;
		}

		public function change_password($email, $old, $new){
			$condition = "user_email = '".$email."' AND user_password ='".$old."'";
			$this->db->select('*');
			$this->db->from('user_login');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$updateData = array(
					'user_email' => $email,
					'user_password' => $new
					);
				$result = $this->update_user($updateData);
				return $result;
			}
			else{
				return 'Old password is not matched with the data in the database';
			}
		}
	}

?>