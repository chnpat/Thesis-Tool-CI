<?php 
	/** Declares to use DOMPDF library for rendering the PDF file from HTML string. */
	use Dompdf\Dompdf;

	/**
	 *	cReport class is a controller class for displaying and generating the report.
	 *
	 *	This class is a controller class for managing
	 *	report generation process, e.g. shows the report on web, or export the report as PDF file. 
	 * 	This class provides 3 methods.
	 *
	 *	@package 	Controller
	 *	@copyright	2017, QAM Support Tool, Chulalongkorn University
	 *	@author 	Charnon Pattiyanon <charnon.pat@gmail.com>
	 *	@since 		20/05/17
	 *	@version 	$Revision: 1.0 $
	 *	@access 	public
	 */
	class cReport extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cReport
		 *
		 *	__construct method is a constructor method for cReport class
		 *	which will load a set of necessarily libraries and models for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model( array(
				'mLogin', 
				'mPattern', 
				'mPatternDesc',
				'Result_implementation/mAssessResult',
				'Result_implementation/mPAPResult',
				'Result_implementation/mCREResult',
				'Result_implementation/mCSDResult',
				'Result_implementation/mDKTResult'));
		}

		/**
		 * 	index() is a simple load function for showing the report generation view only.
		 *
		 *	index() is a simple load function for showing the report generation view which contains the
		 *	report generation form inside the view. The form includes the pattern to be generated and the
		 * 	slot for entering the version to generate,
		 *
		 *	@param 		array 	$report 	an array of report detail for showing.
		 *	
		 *	@return 	void
		 */
		public function index($report = NULL){
			/** Checks login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Report';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/**
				 *  Checks whether the current user is a pattern developer or not.
				 *	If so, the pattern dropdown will show the particular pattern due to
				 *	the current pattern developer ownership only.
				 */
				if($data['userObj']['user_role'] == 'Regular'){
					$detail['pattern_list'] 	= $this->mPattern->get_pattern_by_developer($data['userObj']['id']);	
				}
				else {
					$detail['pattern_list'] 	= $this->mPattern->get_pattern_all();
				}

				/**
				  *  Checks whether the report parameter are empty or not. If not,
				  *	 the assessment timeline will be showed according to the report.
				  */
				if($report != NULL){
					$detail['report'] 	= $report;
				}
				else{
					$detail['report'] 	= NULL;
				}

				/** Load a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vReport', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 * 	generate_report() is a callable function for showing the assessment report.
		 *
		 *	generate_report() is a callable function for showing the assessment report as 
		 *	a timeline depending on the specified pattern ID and version. This function 
		 *	is called after the report generation form is submitted. Then, the input 
		 *	pattern ID and its decription version will be retrieved from the form and
		 * 	used in the fetching process to get the result showing in the timeline.
		 *
		 *	@return 	void
		 */
		public function generate_report(){

			/** Retrieves input pattern ID and version number from the input form. */
			$pat_id 	= $this->input->post('report_pattern');
			$ver_num 	= $this->input->post('report_desc_version');

			/** Checks whether the input version number is a numerical data and not empty. */
			if(!is_numeric($ver_num) AND $ver_num != ""){
				/** 
				 * 	If the input version number is not numerical data and/or it's not an empty string,
				 *	shows an error message and reloads the report generator view.
				 */
				$this->session->set_flashdata('report_error', 'Invalid specified version number.');
				$this->index();
			}
			else{
				/** Assigns a null value to the version number object if the input is empty. */
				$ver_num 	= ($ver_num == "")? NULL: $ver_num;

				/** Fetches the corresponding pattern and its description for the report use.*/
				$pat 		= $this->mPattern->get_pattern($pat_id);
				$desc 		= $this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$ver_num);

				if(!is_bool($desc)){

					/**
					 *	 Retrieves the metric information and its corresponding results and store in
					 *	 $metric_w_result object. Other necessary information are fetched as well. 
					 */
					$metric_w_result['info']['assessor_list'] 	= $this->mAssessResult->get_result_group_by_assessor($pat_id, (float)$ver_num);
					$metric_w_result['info']['pattern_id'] 		= $pat_id;
					$metric_w_result['info']['version'] 		= $ver_num;
					$metric_w_result['data']['metric_list'] 	= array(
															"PAP" => $this->mPAPResult->get_metric(),
															"CRE" => $this->mCREResult->get_metric(),
															"CSD" => $this->mCSDResult->get_metric(),
															"DKT" => $this->mDKTResult->get_metric()
															);
					$metric_w_result['data']['result'] 			= array(
													"PAP" => $this->mPAPResult->get_result_all($pat_id, (float)$ver_num),
													"CRE" => $this->mCREResult->get_result_all($pat_id, (float)$ver_num),
													"CSD" => $this->mCSDResult->get_result_all($pat_id, (float)$ver_num),
													"DKT" => $this->mDKTResult->get_result_all($pat_id, (float)$ver_num)
													);
					/** Reloads the report generation view and passes the result object to show a timeline */
					$this->index($metric_w_result);
				}
				else{
					/**
					  * Handles the case that the specified version is numeric but not exists.	
					  *	If this case happens, shows an error message then reloads the report generation view.
					  */  
					$this->session->set_flashdata('report_error', 'The specified version number is not existing.');
					$this->index();
				}
			}
		}

		/**
		 *	generate_pdf() is a function to create a report as a PDF file.
		 *
		 *	generate_pdf() is a function to create an assessment report as a PDF file by using
		 *	DOMPDF library in order to render html into pdf file. 
		 *
		 *	@param 		string 	$pat_id 	The pattern ID which is a string with a predefined format.
		 *	@param 		float 	$ver 		The version number of the pattern description as a float number.
		 *
		 *	@return 	void 
		 */
		public function generate_pdf($pat_id, $ver = NULL){
			
			/** Loads DOMPDF as an helper class along with file helper class. */
			$this->load->helper(array('dompdf', 'file'));
			
			/** Sets Data for generating the PDF file. */
			$user_id 		= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0]['id'];
			$pattern 		= $this->mPattern->get_pattern($pat_id);
			$description 	= $this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$ver);
			$report 		= array();

			/**
			 *	Checks whether the pattern description is a boolean or not. (The description will be a boolean
			 *  if there is no pattern description with the specified version number in the database)
			 */ 
			if(!is_bool($description)){

				/** get the assessment result by the specified pattern. */
				$report['result'] 	= $this->mAssessResult->get_all_result_by_pattern($pat_id, (float)$ver);
				
				/** Operates the loop the get the result detail of each criteria or variable. */
				foreach ($report['result'] as $key => $value){
					$report['result'][$key]['detail'] 	= $this->mAssessResult->get_all_result_detail_w_metric($value['result_id']);
				}
			}
			else{
				$report = NULL;
			}

			/** Sets a $data array for passing to the PDF template view. */
			$data 	= array(
				'pattern' 		=> $pattern,
				'description' 	=> $description,
				'report'		=> $report
				);

			/** Loads a PDF template view and get as an object for passing to DOMPDF library. */
			$html 	= $this->load->view('vPDF', $data, TRUE);
			
			/** Calls DOMPDF helper function to create a PDF file.*/
     		pdf_create($html, 'Report-'.$user_id.'-'.date('YmdHis'));
		}
	}