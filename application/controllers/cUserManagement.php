<?php 
	/**
	 * 	cUserManagement is a controller class for managing user information.
	 *
	 *	cUserManagement is a controller class for managing user account information, i.e.
	 *	create user, update user, and delete user. This class provides 5 methods.
	 *
	 *	@package 	Controller
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class cUserManagement extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cUserManagement
		 *
		 *	__construct method is a constructor method for cUserManagement class
		 *	which will load a set of necessarily libraries and models for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model('mLogin');
		}

		/**
		 *	index() is a simple load function for showing the user account list.
		 *
		 *	index() is a simple load function for showing the user account list. This view
		 *	allows only the administrator to access.
		 *
		 *	@return 	void
		 */
		public function index(){
			/** Checks login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Sets $data object for passing to the header file. */
				$data['title'] = 'User Management';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Fetches the user account list for passing to the view. */
				$list['rows'] = $this->mLogin->get_user_all();

				/** Loads a view along with the header and footer template files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vUserManagement', $list);
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 *	user_detail() is a controller function for loading user account detail view.
		 *
		 *	user_detail() is a controller function for loading a detail of specified user account.
		 *	This detail allows the administrator to edit the detail.
		 *
		 *	@param 		integer 	$id 	The user ID, i.e. an integer.
		 *
		 * 	@return 	void
		 */
		public function user_detail($id = NULL){
			/** Checks login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Sets $data object for passing to the header file. */
				$data['title'] = 'Add User';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Fetches the user account information */
				$result = $this->mLogin->get_user_by_id($id);

				/** Checks whether the user account information are fetched successfully or not. */
				if(!is_bool($result)){
					$detail['userDetail'] = $result[0];
				}
				else{
					$detail['userDetail'] = NULL;	
				}

				/** Loads a view along with header and footer template files. */
				$this->load->view('templates/header', $data);
				$this->load->view('user_management/vUserDetail', $detail);
				$this->load->view('templates/footer');
			}
		}

		/**
		 *	add_user() is a controller method for performing add user process.
		 *
		 *	add_user() is a controller method for performing add user process. It starts with validating
		 *	the input from the form. Then, gets the data from the input and passing create_data() model
		 *	method.
		 *
		 *	@return 	void
		 */
		public function add_user(){

			/** Sets the form validation rules for all inputs that uses the form validation library. */
			$this->form_validation->set_rules('fname','Username','required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('cpassword', 'Confirm_Password', 'required|matches[password]');
			$this->form_validation->set_rules('role', 'Role', 'required|greater_than[0]');

			/** Runs the form validation library. */
			if($this->form_validation->run() == FALSE){
				/** 
				 *	if the form validation is failed, show the error message and redirect to the user 
				 * 	detail form.
				 */
				$this->session->set_flashdata('detail_error', validation_errors());
				$this->user_detail();
			}
			else{
				/** Sets the role from the dropdown list value. */
				switch ($this->input->post('role')) {
					case '1':
						$role = 'Admin';
						break;
					case '2':
						$role = 'Assessor';
						break;
					case '3':
						$role = 'Regular';
						break;
					default:
						$role = 'Regular';
						break;
				}

				/** Gets user data from the input form */
				$data = array(
					'user_name' => $this->input->post('fname'),
					'user_email' => $this->input->post('email'),
					'user_password' => sha1($this->input->post('password')),
					'user_status' => ($this->input->post('status')? True:False),
					'user_role' => $role
					);	

				/** Calls the model method for creating user and adds into database. */
				$result = $this->mLogin->create_user($data);

				/** Checks whether the create user result is successful or not. */
				if($result == TRUE){
					$this->session->set_flashdata('user_msg', "A user (".$data['user_email'].") has been added successfully!");
					$this->index();			/** reload the index function and redirect to the dashboard */
				}
				else{
					$this->session->set_flashdata("error", "Email (".$data['user_email'].") has already been used!");
					$this->user_detail();	/** Show erro message in case the email is used */
				}
			}
		}

		/**
		 *	update_user() is a controller method for performing user update process.
		 *
		 *	update_user() is a controller method for performing user update process where starts with
		 *	form validation process. Then, passes the data into model method.
		 *
		 *	@param 		integer 	$id 	The user ID that is an integer.
		 *
		 *	@return 	void
		 */
		public function update_user($id){

			/** Sets the form validation rules for all inputs that uses the form validation library. */
			$this->form_validation->set_rules('fname','Username','required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('cpassword', 'Confirm_Password', 'required|matches[password]');
			$this->form_validation->set_rules('role', 'Role', 'required|greater_than[0]');

			/** Runs the form valiation library */
			if($this->form_validation->run() == FALSE){
				/** 
				 * 	if the form validation is failed, show the error message and redirect to 
				 * 	the user detail form.
				 */
				$this->session->set_flashdata('detail_error', validation_errors());
				$this->user_detail();
			}
			else{
				/** Fetches the user role from the dropdown list value. */
				switch ($this->input->post('role')) {
					case '1':
						$role = 'Admin';
						break;
					case '2':
						$role = 'Assessor';
						break;
					case '3':
						$role = 'Regular';
						break;
					default:
						$role = 'Regular';
						break;
				}
				/** get user data from the input form. */
				$data = array(
					'id' => $id,
					'user_name' => $this->input->post('fname'),
					'user_email' => $this->input->post('email'),
					'user_password' => sha1($this->input->post('password')),
					'user_status' => ($this->input->post('status')? True:False),
					'user_role' => $role
					);	

				/** insert data into database. */
				$result = $this->mLogin->update_user($data); 

				/** Checks whether the update result is correct or not. */
				if($result == TRUE){
					$this->session->set_flashdata("user_msg", "A user (".$data['user_email'].") has been updated successfully!");
					$this->index();	/** reload the index function and redirect to the dashboard. */
				}
				else{
					$this->session->set_flashdata("error", "Email (".$data['user_email'].") has already been used!");
					$this->user_detail();	/** Show erro message in case the email is used. */
				}
			}
		}

		/**
		 *	delete_user() is a controller method for performing delete user process.
		 *
		 *	delete_user() is a controller method for performing delete user process from the click in the 
		 *	user account list.
		 *
		 *	@param 		integer 	$id 	The user ID for deleting.
		 *
		 * 	@return 	void 
		 */
		public function delete_user($id){
			/**
			 *	Calls a model method for deleting the user account information following the specified
			 *	user ID inside the database. Then, checks the delete status is successful or not.
			 */
			if($this->mLogin->delete_user($id)){
				$this->session->set_flashdata("user_msg", "A user (ID:".$id.") has been deleted successfully!");
				$this->index();
			}
			else{
				$this->session->set_flashdata("user_error", "Delete user id: ".$id." failed!");
				$this->index();
			}
		}
	}