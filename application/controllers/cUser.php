<?php if (defined('BASEPATH') OR exit('No direct script access allowed'));
	/****************************************
	*	QAM-OODP Support Tool
	*****************************************
	*	Class: cUser
	*	Developer: Charnon Pattiyanon
	*	Create on: 20/04/17
	*
	*
	*	Description: This class is a controller class for controlling
	*	user management process such as login, or register. This class provides 8 methods
	*	including:
	*		index()				=> it is a main function to check login status.
	*		login()				=> it uses for loading the login form view.
	*		register()			=> it uses for loading the registration form view.
	*		forgot()			=> it uses for loading the forgot password form view.
	*		login_process()		=> it uses for performing login process (check user account).
	*		register_process()	=> it uses for performing registration process after submiting the form.
	*		forgot_process()	=> it uses for performing forgot password process (send email).
	*		logout()			=> it allows user to log out from the system. (clear session).
	*****************************************
	*/
	class cUser extends CI_Controller{
		// Class constructer function
		public function __construct(){
			parent:: __construct();
			$this->load->model('mLogin');	// Load mLogin model class
			$this->load->library(array('session', 'form_validation')); // Load required libraries
			$this->load->helper(array('url', 'html', 'form'));	// Load required helper classes
		}
		/****************************************** 
		*	Function: index(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function checks the session whether the user logged in or not.
		*					If the user have already logged in, it will redirect to dashboard page.
		*					Otherwise, the login form will be shown.
		*******************************************
		*/
		public function index(){
			if(!$this->session->userdata('logged')){	// Check session about login status
				$this->login();
			}
			else{
				// Redirect to dashboard page
				echo "<script type='text/javascript'>window.location='".base_url()."cDashboard/index'</script>"; 
			}
		}
		/****************************************** 
		*	Function: login(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function just loads the login form view.
		*******************************************
		*/
		public function login(){
			$this->load->view('templates/login_header');	// shared header file
			$this->load->view('user_authentication/vLogin');
			$this->load->view('templates/login_footer');	// shared footer file
		}
		/****************************************** 
		*	Function: register(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function just loads the registration form view.
		*******************************************
		*/
		public function register(){
			$this->load->view('templates/login_header');	// shared header file
            $this->load->view('user_authentication/vRegistration');
            $this->load->view('templates/login_footer');	// shared footer file
        }
        /****************************************** 
		*	Function: forgot(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function just loads the forgot password form view.
		*******************************************
		*/
        public function forgot(){
        	$this->load->view('templates/login_header');	// shared header file
            $this->load->view('user_authentication/vForgot');
            $this->load->view('templates/login_footer');	// shared footer file
        }
        /****************************************** 
		*	Function: login_process(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function runs the login process by getting the input
		*					from the login form and check with the user account in the system
		*					database. 
		*******************************************
		*/
		public function login_process(){
			$this->form_validation->set_rules('input_email', 'Email', 'required');
			$this->form_validation->set_rules('input_password', 'Password', 'required');
			// form validation will check the required information
			if($this->form_validation->run()){
				$email = $this->input->post('input_email');
				$password = sha1($this->input->post('input_password')); // encrypt the password field
				$info = array(
					'email' => $email,
					'password' => $password 
					);
				$check = $this->mLogin->login($info); // check user account in the database
				// if the user account is valid
				if($check){
					$data = array(
						'email' => $email,
						'logged' => TRUE 
						);
					$this->session->set_userdata($data); // set the session user data
					echo "<script type='text/javascript'>alert('Login Successfully!')</script>";
					$this->index();	// reload the index function and redirect to dashboard
				}
				else{
					echo "<script type='text/javascript'>alert('Invalid Username and Password!')</script>";
					$this->index(); // invalid user account and return to login form
				}
			}
			else{
				echo "<script type='text/javascript'>alert('Please fill all required fields!')</script>";
				$this->index();	// the fields are not filled completely
			}
		}
		/****************************************** 
		*	Function: register_process(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function runs the register process by getting the input
		*					from the registration form and check duplication with the user account in the system
		*					database. 
		*******************************************
		*/
		public function register_process(){
			$this->form_validation->set_rules('regis_username','Username','required');
			$this->form_validation->set_rules('regis_email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('regis_password', 'Password', 'required');
			$this->form_validation->set_rules('regis_confirm_password', 'Confirm_Password', 'required|matches[regis_password]');
			if($this->form_validation->run() == FALSE){
				// if the form validation is failed, show the error message and redirect to the registration form
				$this->session->set_flashdata('registration_error', validation_errors());
				$this->register();
			}
			else{
				// get user data from the input form
				$data = array(
					'user_name' => $this->input->post('regis_username'),
					'user_email' => $this->input->post('regis_email'),
					'user_password' => sha1($this->input->post('regis_password')),
					'user_status' => false
					);	// set user status to false, need to wait for administrators to verify and set role
				$result = $this->mLogin->create_user($data); // insert data into database
				if($result == TRUE){
					echo "<script type='text/javascript'>alert('Registration Successfully! Please wait administrator to verify your application')</script>";
					$this->index();	// reload the index function and redirect to the dashboard
				}
				else{
					echo "<script type='text/javascript'>alert('Email has already been used!')</script>";
					$this->register();	// Show erro message in case the email is used
				}
			}
		}
		/****************************************** 
		*	Function: forgot_process(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function runs the forgot password process by getting an email address
		*					from the form and sends the recover password email to the given email addresss.
		*******************************************
		*/
		public function forgot_process(){
			$this->form_validation->set_rules('forgot_email', 'Email', 'required|valid_email');
			if($this->form_validation->run() == FALSE){
				// In case the email is in the wrong format, show error message and return the forgot password form
				$this->session->set_flashdata('forgot_error', validation_errors());
				$this->forgot();
			}
			else{
				$data = array('email' => $this->input->post('forgot_email') );
				// check whether the given email is existed in the database
				$check = $this->mLogin->get_user($data);
				if($check == false){
					// if not, show error message and ask for email address again
					$this->session->set_flashdata('forgot_error', 'Email is invalid! Please re-enter the email');
					$this->forgot();
				}
				else{
					// if so, set the configuration for sending email
					$config = array(
						'protocol' => 'smtp',
						'smtp_host' => SMTP_HOST,	// from constants.php file
						'smtp_port' => SMTP_PORT,
						'smtp_user' => SMTP_USER,
						'smtp_pass' => SMTP_PASS,
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'wordwrap' => TRUE,
						'validation' => TRUE
						);
					$this->load->library('email', $config);	// load email library
					
					// compose the message for using as email body
					$row = $check[0];
					$message = "You have submitted the forgot password form<br/>Email : ".$row['user_email']."<br/>Password : ".$row['user_password']."";

					$this->email->set_newline("\r\n");
					$this->email->from(SMTP_USER);
					$this->email->to($this->input->post('forgot_email'));
					$this->email->subject('QAM-OODP Tool Forgot Password');
					$this->email->message($message);

					// send an email to the given email address
					if($this->email->send()){
						$this->session->set_flashdata('forgot_success', 'Email has already been sent! Please check your email ('.$row['user_email'].')');
						$this->forgot();
					}
					else{
						echo "<script type='text/javascript'>alert('Error! Email cannot be sent. Please check the configuration')</script>";
						$this->forgot();
					}
				}
			}
		}
		/****************************************** 
		*	Function: logout(): void
		*******************************************
		*	input:	-
		*	output:	-
		*	Description: 	This function makes the current user logout from the system.
		*					Also, clear the session data and redirect to the login form.
		*******************************************
		*/
		public function logout(){
			$this->session->unset_userdata('email', $this->session->userdata('email'));
			session_destroy();	// destroy the session
			echo "<script type='text/javascript'>alert('Logout Successfully!')</script>";
			$this->load->view('templates/login_header');
			$this->load->view('user_authentication/vLogin');
			$this->load->view('templates/login_footer');
		}
	}
