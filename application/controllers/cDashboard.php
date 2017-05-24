<?php 
	/**
	 *	cAssess class is a controller class for the assessment process.
	 *
	 *	This class is a controller class for managing
	 *	pattern assessment process, e.g. select metric choice, enter the values. 
	 * 	This class provides 8 methods
	 *
	 *	@package 	Controller
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class cDashboard extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cDashboard class
		 *
		 *	__construct method is a constructor method for cDashboard class
		 *	which will load a set of necessarily libraries and models for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model(array('mLogin', 'mPattern', 'mPatternDesc'));
		}

		/**
		 *	index() method is a simple load method to show data in dashboard.
		 *
		 *	index() method checks the login status as usual. Then, loads the pattern list, unreach limit pattern list,
		 *	reach limit pattern list, and pending pattern list which will use in the dashboard.
		 *	
		 *	@return 	void
		 */
		public function index(){

			/** Checks login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Dashboard';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];


				/**
				 * 	In case the user is 'pattern developer', the pattern list will be fetched the pattern
				 *	that the user is own only. 
				 */
				if($data['userObj']['user_role'] == 'Regular'){

					/**
					 * 	Fetches 4 lists of patterns which are pending-for-assessing list, unreach-limit list, 
					 *	reached-limit list, and all pattern list.
					 */
					$pending_list 	= $this->mPattern->get_only_ready_pattern_no_assessed($data['userObj']['id']);
					$unreach_list 	= $this->mPattern->get_pattern_unreach_limit_assess($data['userObj']['id']);
					$reach_list 	= $this->mPattern->get_pattern_reach_limit_assess($data['userObj']['id']);
					$pattern_list 	= $this->mPattern->get_pattern_by_developer($data['userObj']['id']);

					/** Assign the pattern lists into $detail object which will be passed into view. */
					$detail['pending_list'] 	= (!is_bool($pending_list))? $pending_list: array();
					$detail['unreach_list'] 	= (!is_bool($unreach_list))? $unreach_list: array();
					$detail['reach_list'] 		= (!is_bool($reach_list))? $reach_list: array();
					$detail['pattern_list'] 	= (!is_bool($pattern_list))? $pattern_list: array();

					/**
					 *	Count the number of pattern in all list for using in the widget. Then,
					 *	assigns the number into $detail object as well. 
					 */
					$detail['Number_of_patterns'] 	= count($detail['pattern_list']);
					$detail['Number_of_pending'] 	= count($detail['pending_list']);
					$detail['Number_of_unreach'] 	= count($detail['unreach_list']);
					$detail['Number_of_reach'] 		= count($detail['reach_list']);
				}
				else{
					$pending_list 	= $this->mPattern->get_only_ready_pattern_no_assessed();
					$unreach_list 	= $this->mPattern->get_pattern_unreach_limit_assess();
					$reach_list 	= $this->mPattern->get_pattern_reach_limit_assess();
					$pattern_list 	= $this->mPattern->get_pattern_all();

					$detail['pending_list'] 	= (!is_bool($pending_list))? $pending_list: array();
					$detail['unreach_list'] 	= (!is_bool($unreach_list))? $unreach_list: array();
					$detail['reach_list'] 		= (!is_bool($reach_list))? $reach_list: array();
					$detail['pattern_list'] 	= (!is_bool($pattern_list))? $pattern_list: array();

					$detail['Number_of_patterns'] 	= count($detail['pattern_list']);
					$detail['Number_of_pending'] 	= count($detail['pending_list']);
					$detail['Number_of_unreach'] 	= count($detail['unreach_list']);
					$detail['Number_of_reach'] 		= count($detail['reach_list']);
				}

				/** Load a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vDashboard', $detail);
				$this->load->view('templates/footer');	
			}		
		}
	}