<?php 
	/**
	 *	cPattern class is a controller class for the CRUDE process of patterns.
	 *
	 *	This class is a controller class for managing
	 *	pattern CRUDE process, e.g. create a new pattern or update the pattern. 
	 * 	This class provides 5 methods
	 *
	 *	@package 	Controller
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class cPattern extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cPattern
		 *
		 *	__construct method is a constructor method for cPattern class
		 *	which will load a set of necessarily libraries and models for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array('mLogin','mPattern', 'mPatternDesc'));
		}

		/**
		 *	index() method is a simple load pattern list method.
		 *
		 *	index() method checks the login status as usual. Then, loads the pattern list
		 *	based on the user, i.e. if the user is 'pattern developer', the list will contain only the pattern
		 *	developer own pattern.
		 *	
		 *	@return 	void
		 */
		public function index(){
			/** Check login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Pattern List';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				
				/** 
				 * 	Checks whether the user is 'Administrator' or not. If not, the pattern list will contain only
				 *	pattern which own by the pattern developer only.
				 */
				if($data['userObj']['user_role'] == 'Admin'){
					$list 			= $this->mPattern->get_pattern_all();
					$detail['rows'] = (is_bool($list))? array():$list;
				}
				else{
					$list 			= $this->mPattern->get_pattern_by_developer($data['userObj']['id']);
					$detail['rows'] = (is_bool($list))? array():$list; 
				}

				/** Load a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vPattern', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 *	pattern_detail() is a controller method for loading pattern detail view.
		 *
		 *	pattern_detail() is a controller method for loading pattern detail view. By checking the login status,
		 *	the role will be getted. Then, gets the pattern list due to the user account and passing to the views.
		 *
		 *	@param 		string 	$id 	The pattern ID which is a string with predefined format.
		 *
		 *	@return 	void
		 */
		public function pattern_detail($id=NULL){
			/** Checks login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Sets $data object that handles data for a header file */
				$data['title'] 				= 'Pattern Detail';
				$data['userObj'] 			= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Fetches the pattern data and its corresponding pattern description. Then, passes to the view. */
				$detail['rows'] 			= $this->mPattern->get_pattern($id);
				$detail['desc_by_pattern'] 	= (is_bool($this->mPatternDesc->get_pattern_description_by_pattern($id)))? array():$this->mPatternDesc->get_pattern_description_by_pattern($id); 

				/** Loads only the view without any data in the 'adding' situation. */
				if(is_bool($detail['rows'])){
					$detail['rows'] 		= NULL;
				}

				/** Load a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('pattern_management/vPatternDetail', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 *	add_pattern() is a method for performing the adding process.
		 *
		 *	add_pattern() is a method for performing the adding process. It starts with checking the login status.
		 *	Then, the input will be retrieved from the input form and validated with the predefined rules. Finally,
		 *	passes the data to the model class.
		 *
		 *	@return 	void
		 */
		public function add_pattern(){
			/** Fetches the user object */
			$usr 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

			/** Sets the rules by form validation library */
			$this->form_validation->set_rules('pattern_id', 			'Pattern ID', 			'required');
			$this->form_validation->set_rules('pattern_name', 			'Pattern Name', 		'required');
			$this->form_validation->set_rules('pattern_assess_limit', 	'Pattern Assess Limit', 'required|greater_than[-1]');
			$this->form_validation->set_rules('pattern_assess_version', 'Pattern Assess Version', 'greater_than[-1]');

			/** Runs the form validation library to check the input follows the predefined rules */
			if($this->form_validation->run() == FALSE){

				/** If the validation is failed, returns to detail voew and shows the error message. */
				$this->session->set_flashdata('pattern_error', validation_errors());
				$this->pattern_detail();
			}
			else{

				/** Sets $data object for passing to create_pattern() method. */
				$data 	= array(
					'pattern_id' 				=> $this->input->post('pattern_id'),
					'pattern_name'		 		=> $this->input->post('pattern_name'),
					'pattern_assess_limit' 		=> $this->input->post('pattern_assess_limit'),
					'pattern_assess_version' 	=> (float)$this->input->post('pattern_assess_version'),
					'pattern_status' 			=> $this->input->post('pattern_status')
					);

				/** 
				 *	Checks the user role. If the user is administrator, all patterns will be fetched,
				 *	Otherwise, patterns which corresponds with the specified user will be fetched only.
				 */
				if($usr['user_role'] == 'Admin'){
					$data['pattern_creator_id'] 	= $this->input->post('pattern_creator_id');
				}
				else{
					$data['pattern_creator_id']	 	= $usr['id'];
				}

				/**	Sets $desc array to use as a data object for creating create_pattern_description method. */
				$desc = array(
					'pattern_id' 	=> $this->input->post('pattern_id'),
					'desc_version' 	=> (float)$this->input->post('pattern_assess_version')
				);

				/** Calls model method to create both pattern and its corresponding pattern description. */
				$result 		= $this->mPattern->create_pattern($data);
				$result_desc 	= $this->mPatternDesc->create_pattern_description($desc);

				/**
				 * 	Check whether the create transsaction result is successful or not. Then, shows the message
				 *	according to the create status.
				 */
				if($result){
					$this->session->set_flashdata("pattern_msg", 	"A pattern id: '".$this->input->post('pattern_id')."' has been added successfully!");
					$this->index();
				}
				else{
					$this->session->set_flashdata("pattern_error", 	"A pattern ID (".$this->input->post('pattern_id').") is duplicated!");
					$this->pattern_detail();
				}
			}

		}

		/**
		 * 	edit_pattern() is a method for performing update process on the existing pattern.
		 *
		 *	edit_pattern() is a method for performing update process on the existing pattern. It starts with
		 * 	setting rules for using in the form validation library. Then, the input will be set into an object
		 *	and passed into the model method.
		 *
		 *	@param 		string 	$id 	 The pattern ID which is a string with predefined format that is editing.
		 *
		 * 	@return 	void
		 */
		public function edit_pattern($id){

			/** Sets $usr variable to store the current user object. */
			$usr 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
			
			/** Sets rules for the form validation library */
			$this->form_validation->set_rules('pattern_id', 			'Pattern ID', 			'required');
			$this->form_validation->set_rules('pattern_name', 			'Pattern Name', 		'required');
			$this->form_validation->set_rules('pattern_assess_limit', 	'Pattern Assess Limit', 'required|greater_than[-1]');
			$this->form_validation->set_rules('pattern_assess_version', 'Pattern Assess Version', 'greater_than[-1]');
			
			/** Runs the form validation library */
			if($this->form_validation->run() == FALSE){

				/** If the form validation failed, shows the error message and reload the detail view. */
				$this->session->set_flashdata('pattern_error', validation_errors());
				$this->pattern_detail();
			}
			else{

				/** Sets $data object to handle the input data for passing to update method in model class. */
				$data = array(
					'pattern_id' 				=> $this->input->post('pattern_id'),
					'pattern_name' 				=> $this->input->post('pattern_name'),
					'pattern_assess_limit' 		=> $this->input->post('pattern_assess_limit'),
					'pattern_assess_version' 	=> $this->input->post('pattern_assess_version'),
					'pattern_status' 			=> $this->input->post('pattern_status')
					);

				/** 
				 * 	First, checks the user role in order to set the pattern creator ID. If the user is pattern developer,
				 *	their user ID will be automatically added as pattern creator ID. Otherwise, the input from dropdown
				 *	list will be used instead.
				 */
				if($usr['user_role'] == 'Admin'){
					$data['pattern_creator_id'] 	= $this->input->post('pattern_creator_id');
				}
				else{
					$data['pattern_creator_id'] 	= $usr['id'];
				}

				/** Calls the update_pattern() method from the model class to handle with data in the database. */
				$result = $this->mPattern->update_pattern($data);

				/** 
				 * 	Checks the result of the update process. If it's success, shows the message and return to the 
				 *	pattern list view. Otherwisem the error message will show and the detail view will be loaded instead.
				 */
				if($result){
					$this->session->set_flashdata("pattern_msg", 	"A pattern id: '".$this->input->post('pattern_id')."' has been updated successfully!");
					$this->index();
				}
				else{
					$this->session->set_flashdata("pattern_error", 	"A pattern (ID:".$id.") update process is failed!");
					$this->pattern_detail($id);
				}
			}
		}

		/**
		 * 	delete_pattern() is a method for performing the deletion of the specified pattern.
		 *
		 *	delete_pattern() is a method for performing the delete process to the specified pattern by calling
		 * 	the delete_pattern() method from the model class
		 *
		 *  @param 		string 	id 		The pattern ID which is a string with predefined format that is deleting.
		 *
		 *	@return 	void
		 */
		public function delete_pattern($id){

			/**
			 *	Calls the delete_pattern() method from the mPattern model class in order to delete the specified pattern
			 *	according to the ID parameter from the database. The delete_pattern() method will return the delete status
			 * 	as a boolean. Then, shows the message on the pattern list view,
			 */
			if($this->mPattern->delete_pattern($id)){
				$this->session->set_flashdata("pattern_msg", 	"A pattern (ID:".$id.") has been deleted successfully!");
				$this->index();
			}
			else{
				$this->session->set_flashdata("pattern_error", 	"A pattern (ID:".$id.") cannot be deleted!");
				$this->index();
			}
		}
	}