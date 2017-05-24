<?php 
	/**
	 * 	cChangePassword	is a controller class for managing change password process.
	 *
	 *	cChangePassword is a controller class that handle all functionality for
	 *	user or admin to change their user account password. This class provide 2 methods.
	 *
	 *	@package 	Controller
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class cChangePassword extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cChangePassword
		 *
		 *	__construct method is a constructor method for cChangePassword class
		 *	which will load a set of necessarily libraries, helpers, and models for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->helper('form', 'url', 'html');
			$this->load->model('mLogin');
		}

		/**
		 *	index() method is a simple load for change password view.
		 *
		 *	index() method checks the login status. Then, the required data will be fetched and shows the view.
		 *
		 *	@return 	void
		 */
		public function index(){
			/** Check login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file. */
				$data['title'] 		= 'Change Password';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Get a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vChangePassword');
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 *	change_process() is a method for perfoming the change password process.
		 *
		 *	change_process() is a method for performing the change password process, 
		 *	First, get the old password with a set of new password. Then, checks that the old password
		 *	is correct. 
		 *
		 *	@return 	void
		 */
		public function change_process(){

			/** 
			 * 	Sets rules for form validation helper for checking the new password 
			 *	and password confirmation are matched.
			 */
			$this->form_validation->set_rules('oldPassword', 	'Old Password', 		'required');
			$this->form_validation->set_rules('newPassword', 	'New Password', 		'required');
			$this->form_validation->set_rules('cNewPassword', 	'Confirm New Password', 'required|matches[newPassword]');

			/** Runs the form validation */
			if($this->form_validation->run() == FALSE){
				/** If not, reload the change password view and shows error */
				$this->index();
			}
			else{
				/** If so, the old password and new password are retrieved from form. Then, encrypts with SHA1. */
				$oldPassword 	= sha1($this->input->post('oldPassword'));
				$newPassword 	= sha1($this->input->post('newPassword'));

				/** Calls the change_password method to match the old password with the database and update to new one. */
				$result 	= $this->mLogin->change_password($this->session->userdata('email'), $oldPassword, $newPassword);

				/** If the old password is not matched with the database one. */
				if(is_string($result)){

					/** Set flash data to display 'no match' error */
					$this->session->set_flashdata('nomatch', $result);
				}
				/** Checks whether the result of changing is a boolean or not. */
				if(is_bool($result)){

					/** Check the changing process is successful or not. */
					if($result){
						/** Show successful message. */
						$this->session->set_flashdata('success', 	'Password has been changed successfully.');
					}
					else{
						/** Show error message. */
						$this->session->set_flashdata('error', 		'Password changing process failure!');
					}
				}
				$this->index();
			}
		}
	}