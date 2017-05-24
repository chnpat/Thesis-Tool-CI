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
	class cAssess extends CI_Controller{

		/**
		 *	__construct method is a constructor method for cAssess
		 *
		 *	__construct method is a constructor method for cAssess class
		 *	which will load a set of necessarily libraries and helpers for use.
		 * 
		 *	@return void
		 */
		public function __construct(){
			parent::__construct();
			$this->load->library( array( 	'session', 
											'form_validation' ));
			$this->load->model( array(
				'mLogin',
				'mPattern',
				'mPatternDesc',
				'Result_implementation/mAssessResult', 
				'Result_implementation/mPAPResult',
				'Result_implementation/mCREResult',
				'Result_implementation/mCSDResult',
				'Result_implementation/mDKTResult'));
			$this->load->helper('file');
		}

		/**
		 *	index() method is a simple load assessment list method.
		 *
		 *	index() method checks the login status as usual. Then, loads the pattern list
		 *	for the assessment which it will separate into the pending for assessing table (which
		 *	means patterns that either assessed or not but not reach its assessment limit) and
		 *	the completed assessed table (which includes patterns that reach its limit)
		 *	
		 *	@return 	void
		 */
		public function index(){

			/** Check login status */
			if(!$this->session->userdata( 'logged' )){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Assessment List';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Get a list of unreach and reach patterns and assign the $detail object */
				$assess_list 			= $this->mPattern->get_pattern_unreach_limit_assess();
				$done_list 				= $this->mPattern->get_pattern_reach_limit_assess();
				$detail['list'] 		= (!is_bool($assess_list))? 	$assess_list: array();
				$detail['done_list'] 	= (!is_bool($done_list))? 	$done_list	: array();

				/** Load a view along with the template header and footer files. */
				$this->load->view('templates/header', $data);
				$this->load->view('vAssessmentList', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		/**
		 *	assess_choice() method is a method for loading metric choices list.
		 *
		 *	assess_choice() method is a method for loading metric choices list. it starts by checking
		 *	the login status as usual. Then, loads a list of applicable metrics and sets the results if
		 *	the results are already existing.
		 *
		 *	@param 		string 	$pat_id 	the pattern ID that is a string in the predefined format.
		 *	@param 		float 	$ver_num 	the version number that is a float number, e.g. 1.0, 1.2.
		 *
		 *	@return 	void
		 */
		public function assess_choice($pat_id, $ver_num){

			/** Check login status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Quality Attributes Choices';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				
				/** Get pattern and pattern description objects for fetching result */
				$pat 		= $this->mPattern->get_pattern($pat_id);
				$desc 		= (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version']): array();

				/** Check whether the pattern is exceed its assessment limit or not */
				if($desc[0]['desc_assess_count'] >= $pat['pattern_assess_limit'] AND $pat['pattern_assess_limit'] != 0){
					/** If so, redirect to the assessment pattern list */
					$this->index();
				}
				else{
					/** 
					 *	Initiate the $detail object which handle all necessary information for the view to show.
					 *	Starts with passing patttern and pattern description objects into the $detail object 
					 */
					$detail 	= array(
						'pattern' 				=> $pat,
						'pattern_description' 	=> $desc[0]
						);

					/** Then, retrieve all metric objects and pass into the $detail object 
					 *	where the metric are fetch with its corresponding variables.
					 *
					 *	Example Data:
					 *	$detail['metrics'] 	=> 	['PAP'] =>	['id'] 			=> 1,
					 *									=>	['metric_name']	=> 'Pattern Application Proportion',
					 *									=>	...
					 *						=>	['CRE']	=>	...
					 */
					$detail['metrics'] = array(
						'PAP' 	=> $this->mPAPResult->get_metric(),
						'CRE' 	=> $this->mCREResult->get_metric(),
						'CSD' 	=> $this->mCSDResult->get_metric(),
						'DKT' 	=> $this->mDKTResult->get_metric()
						);

					/**
					 *	Then, retrieve result objects that relates to each metrics
					 *	This $detail object is only fetch the result level (not its variable)
					 *
					 *	Example Data:
					 *	$detail['result']	=>	['PAP']	=>	[0]	=>	['result_id']	=>	1,
					 *											=>	['assessor_id']	=>	1,
					 *											=>	...
					 *						=>	['CRE']	=>	[0]	=>	...
					 *						=>	...
					 */
					$detail['result'] = array(
						'PAP'	=> $this->mPAPResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'CRE' 	=> $this->mCREResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'CSD' 	=> $this->mCSDResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'DKT' 	=> $this->mDKTResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id'])
						);

					/** Load a view along with the template header and footer files. */
					$this->load->view('templates/header', $data);
					$this->load->view('Assessment/vAssessmentChoice', $detail);
					$this->load->view('templates/footer');	
				}
			}
		}

		/**
		 *	assess_detail()	method is a controller class for loading assessment detail view.
		 *
		 *	assess_detail() method is a controller class for loading assessment detail view. it starts with
		 *	checking login status. Then, loads the detail or each variable in the specified metric. Also, the
		 *	view will show its corresponding pattern description as well.
		 *
		 *	@param 		string 	$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		float 	$ver_num 	The version number that is a float number, e.g. 1.0, 2.2.
		 *	@param `	string 	$metr 		The metric abbreviation string, i.e. PAP, CRE, CSD, or DKT.
		 *	
		 *	@return 	void
		 */
		public function assess_detail($pat_id, $ver_num, $metr = NULL){

			/** Check Login Status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else {
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Assessment Details';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				
				/** Get pattern objects for fetching results */
				$pat 	= $this->mPattern->get_pattern($pat_id);

				/** For CRE Metric, it will automatically calculate the result */
				if($metr == "CRE"){
					/** 
					 *	Call the function for getting a list of metric which mapped with result. 
					 *	Especially, this function will call the create method of CRE metric.
					 */
					$detail['result'] 	= $this->get_metrics_w_results($pat['pattern_id'], $pat['pattern_assess_version'], $data['userObj']['id'], $this->get_metrics($metr));

					/** After setting the result, return to the metric choice view and show a success message. */
					$this->session->set_flashdata("choice_msg", "Successful!! The Assessment of CRE Metric is automatically done.");
					$this->assess_choice($pat_id, $ver_num);	
				}
				else {

					/**	If the selected metric is not CRE, then fetch the pattern description object. */
					$desc 	= (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version'])[0]: array();

					/** 
					 *	The list of images in the specified directory is fetched where each pattern and each version 
					 *	are having its separated folder.
					 */
					$design_img_list 	= get_dir_file_info("./images/DesignImg/".$pat['pattern_id']."/".$desc['id']."/");

					/**
					 *	Store fetched data into $detail object for passing to view.
					 *	The $detail object will include the pattern, pattern description,
					 *	metric detail, and design file list,
					 */
					$detail 	= array(
						'pattern' 		=> $pat,
						'description' 	=> $desc,
						'metr' 			=> $metr,
						'design_file' 	=> $design_img_list
						);
					$detail['result'] 	= $this->get_metrics_w_results($pat['pattern_id'], $pat['pattern_assess_version'], $data['userObj']['id'], $this->get_metrics($metr));

					/** Load a view along with its template header and footer files. */
					$this->load->view('templates/header', $data);
					$this->load->view('Assessment/vAssessmentDetail', $detail);
					$this->load->view('templates/footer');
				}
			}
		}

		/**
		 *	update_detail() is a special-case controller method for updating detail.
		 *
		 *	update_detail() is a special-case controller method for updating assessment detail.
		 *	Each asssessment criteria in the corresponding will be listed and it will allow assessor
		 *	to enter scores.
		 *
		 *	@param 		string 	$pat_id 	The pattern ID that ie a string with the predefined format.
		 *	@param 		float 	$ver_num	The version number that is a float number, e.g. 1.0, 2.2.
		 *	@param 		string 	$metr 		The metric abbreviation string, i.e. PAP, CRE, CSD, or DKT.
		 *
		 * 	@return 	void
		 */
		public function update_detail($pat_id, $ver_num, $metr = NULL){

			/** Check Login Status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Set $data object that handles data for a header file */
				$data['title'] 		= 'Assessment Details';
				$data['userObj'] 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				/** Get pattern objects for fetching results */
				$pat 	= $this->mPattern->get_pattern($pat_id);
				
				/** For CRE Metric, it will automatically calculate and update the result. */
				if($metr == 'CRE'){
					/**
					 * 	The function update_result in mCREResult class will be called to update the existing
					 * 	CRE result score.
					 */
					$this->mCREResult->update_result($pat_id, $ver_num, $data['userObj']['id'], array());

					/** Load the metric choice view to show the calculated CRE score with the success message. */
					$this->session->set_flashdata("choice_msg", "Successful!! The Assessment of CRE Metric is automatically updated.");
					$this->assess_choice($pat_id, $ver_num);
				}
				else{
					/** If it is not CRE metric, load the assessment detail view as usual. */
					$this->assess_detail($pat_id, $ver_num, $metr);
				}
			}
		}

		/**
		 * 	update_result_proc() is a method for operating result update process.
		 *
		 *	update_result_proc() is a method for operating result update process after the assessor changed
		 *	and submited scores in the assessment detail view. 
		 *
		 *	@param 		string 	$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		float 	$ver_num 	The version number that is a float number, e.g. 1.0, 2.2.
		 *	@param `	string 	$metr 		The metric abbreviation string, i.e. PAP, CRE, CSD, or DKT.
		 *	
		 *	@return 	void	
		 */
		public function update_result_proc($pat_id, $ver_num, $metr = NULL){
			
			/** Check Login Status */
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				/** Load the assessor ID from the session data to identify who are assessing the pattern. */
				$ass_id 	= $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0]['id'];

				/** 
				 * 	Load the existing result according to the selected metric and the assessor.
				 *	The loaded result will include its detail as well.
				 *
				 *	Example Data:
				 *	$result 	=>	['result_id']	=>	1,
				 *				=>	['score']		=>	25.00,
				 *				=>	['detail']		=>	[0]		=>	['variable_id']		=> 1,
				 *												=>	['variable_score']	=>	20.00,
				 *									=>	...
				 */
				switch ($metr){
					case 'PAP':
						$result 	= $this->mPAPResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] 	= $this->mPAPResult->get_result_detail($result['result_id']);
						}
						break;
					case 'CSD':
						$result 	= $this->mCSDResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] 	= $this->mCSDResult->get_result_detail($result['result_id']);
						}
						break;
					case 'DKT':
						$result 	= $this->mDKTResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] 	= $this->mDKTResult->get_result_detail($result['result_id']);
						}
						break;
					default:
						$result 	= NULL;
						break;
				}
				/**
				 *	Then, loads the metric information and the corresponding variable of the metric
				 *
				 *	Example Data:
				 *	$metrics_list	=>	['PAP']		=>	['id']				=>	1,
				 *									=>	['metric_name']		=>	'Pattern Application Proportion',
				 *									=>	['metric_abberv']	=>	'PAP',
				 *									=>	...
				 *									=>	['detail']			=>	[0]	=>	['id']				=>	1,
				 *																	=>	['variable_name']	=>	'...',
				 *																	=>	...
				 *															=>	...
				 */
				$metrics_list 	= $this->get_metrics($metr);
				
				/** Check the existence of the result */
				if($result == NULL){
					/**
					 * 	If there are no results existed, it means that the new result will be added instead
					 *	of being updated. The following loop will run over all metric available in the metric
					 *	list and get the result of each variable from the form.
					 */
					foreach ($metrics_list as $k => $v){
						$data 	= array();

						foreach ($v['detail'] as $key => $value){

							if($this->input->post($value['id']."_var") != ""){
								$data[$value['id']] 	= (float)$this->input->post($value['id']."_var");
							}
						}

						/** 
						 *	After retrieving the data from the form, The create_result() method of 
						 *	each type of metric will be called. These create_result() will both save the
						 *	result of each variable into database and calculate the metric score.
						 */
						switch ($metr) {
							case 'PAP':
								$this->mPAPResult->create_result($pat_id, $ver_num, $ass_id, $data);
								break;
							case 'CSD':
								$this->mCSDResult->create_result($pat_id, $ver_num, $ass_id, $data);
								break;
							case 'DKT':
								$this->mDKTResult->create_result($pat_id, $ver_num, $ass_id, $data);
								break;
							default:
								$result = NULL;
								break;
						}
					}
				}
				else {
					/**
					 *	If there are exist results, the edit result will update in the current one.
					 *	It will run through all metrics in the list
					 */
					foreach ($metrics_list as $k => $v) {
						$data 	= array();

						/** Then, the detail of each variable will be fetched from the form */
						foreach ($v['detail'] as $key => $value) {

							if($this->input->post($value['id']."_var") != ""){
								$data[$value['id']] 	= (float)$this->input->post($value['id']."_var");
							}
						}

						/** 
						 * 	Calls update_result() function in model classes based on type of the metric.
						 *	These functions will update the result individually.
						 */
						switch ($metr) {
							case 'PAP':
								$this->mPAPResult->update_result($pat_id, $ver_num, $ass_id, $data);
								break;
							case 'CSD':
								$this->mCSDResult->update_result($pat_id, $ver_num, $ass_id, $data);
								break;
							case 'DKT':
								$this->mDKTResult->update_result($pat_id, $ver_num, $ass_id, $data);
								break;
							default:
								$result = NULL;
								break;
						}
					}
				}

				/** 
				 *	Calls set_assess_count() functions for updating the number of assessment 
				 *	into the description in order to check whether it assess untill reaching its limit.
				 */
				$this->set_assess_count($pat_id, $ver_num, $ass_id);

				/** Re-load the metric choices view after updating the results */
				$this->assess_choice($pat_id, $ver_num);
			}
		}

		/**
		 * 	get_metrics() is a private method that gets all metric information.
		 *	
		 *	get_metrics() is a private method that gets all metric information along with
		 *	its corresponding variable. Then, the metric information will store into
		 *	an array of metric objects.
		 *
		 *	@param 		string 	$metr 	The metric abbreviation string, i.e. PAP, CRE, CSD, or DKT.
		 *
		 *	@return 	array 	an array of metric information along with an array of its corresponding variable.
		 */
		function get_metrics($metr){
			switch ($metr) {
				case 'PAP':
					$metrics_list = array($metr => $this->mPAPResult->get_metric());
					break;
				case 'CRE':
					$metrics_list = array($metr => $this->mCREResult->get_metric());
					break;
				case 'CSD':
					$metrics_list = array($metr => $this->mCSDResult->get_metric());
					break;
				case 'DKT':
					$metrics_list = array($metr => $this->mDKTResult->get_metric());
					break;
				default:
					$metrics_list = NULL;
					break;
			}
			return $metrics_list;
		}

		/**
		 * 	get_metrics_w_results() is a private method for fetching an array of metrics.
		 *
		 *	get_metrics_w_results() is a private method for fetching an array of metrics which mapped with its result.
		 *	Especially, the result will calculate automatically for the CRE metric.
		 *
		 *	@param 		string 	$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		float 	$ver 		The version number that is a float number, e.g. 1.0, 2.2.
		 *	@param `	integer $ass_id 	The assessor ID for the corresponding result that is a integer.
		 *
		 *	@return 	array 	array of metric information which is mapped with the result one-by-one and by assessor.
		 */
		function get_metrics_w_results($pat_id, $ver, $ass_id, $metrics_list){
			/** First, check whether an array of metric information is not exist or not. */
			if($metrics_list != NULL){
				/** Runs through all metrics in an array  */
				foreach ($metrics_list as $metr) {
					/**
					 * 	Check the type of the metric from the metric abbreviation. There is a specific behavior
					 *	when getting the CRE metric only that the result also be updated. Otherwise, the result
					 *	will be fetched into an array with its detail.
					 */
					switch ($metr['metric_abberv']) {
						case 'PAP':
							$result = $this->mPAPResult->get_result($pat_id, $ver, $ass_id);

							if($result != NULL){
								$result['detail'] = $this->mPAPResult->get_result_detail($result['result_id']);
							}
							break;

						case 'CRE':
							$result = $this->mCREResult->get_result($pat_id, $ver, $ass_id);

							if($result != NULL){
								$result['detail'] = $this->mCREResult->get_result_detail($result['result_id']);
							}
							if($result == NULL OR $result['detail'] == NULL){

								/** Fetch the pattern and pattern description objects */
								$pat = $this->mPattern->get_pattern($pat_id);
								$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, $pat['pattern_assess_version'])[0]: array();

								/** Sets a temporary object for CRE metric result for passing to create_function() method. */
								$tmp = array("pattern" => $pat, "description" => $desc, "ass_id" => $ass_id);
								$result = $this->mCREResult->create_result($pat_id, $ver, $ass_id, $tmp);
							}
							break;

						case 'CSD':
							$result = $this->mCSDResult->get_result($pat_id, $ver, $ass_id);
							if($result != NULL){
								$result['detail'] = $this->mCSDResult->get_result_detail($result['result_id']);
							}
							break;

						case 'DKT':
							$result = $this->mDKTResult->get_result($pat_id, $ver, $ass_id);

							if($result != NULL){
								$result['detail'] = $this->mDKTResult->get_result_detail($result['result_id']);
							}
							break;

						default:
							$result = NULL;
							break;
					}

					/** 
					 *	After fetching the result object, it is possible that there is not result exist
					 *	so this condtion handles this exceptional case.
					 */
					if($result != NULL){

						/** Starts mapping the score from the result objects to the metric information array */
						$metrics_list[$metr['metric_abberv']]["result"] = $result["score"];
						$count = 0;

						/**
						 * 	Runs through all variables in metric array and map with the variable score
						 *	in the result objects.
						 */
						foreach ($metr['detail'] as $det){
							foreach ($result['detail'] as $v){

								/** Map the ID of metric variable to the variable ID in the result objects. */
								if($det['id'] == $v['variable_id']){
									$metrics_list[$metr["metric_abberv"]]["detail"][$count]["var_score"] = $v["variable_score"];
								}
							}
							$count++;
						}
					}
				}
			}
			return $metrics_list;
		}

		/**
		 * 	set_assess_count() is a private method for setting assessment count to each description.
		 *
		 * 	set_assess_count() is a private method for setting assessment count to each pattern description.
		 *	For limiting the number of assessment result, this method will count up every time it is called.
		 *
		 *	@param 		string 	$pat_id 	The pattern ID that is a string with the predefined format.
		 *	@param 		float 	$ver_num 	The version number that is a float number, e.g. 1.0, 2.2.
		 *	@param `	integer $ass_id 	The assessor ID for the corresponding result that is a integer.
		 */
		function set_assess_count($pat_id, $ver_num, $ass_id){

			/** Fetches the current count of the assessment result of the pattern on the specified version. */
			$current_assess_count = count($this->mAssessResult->get_result_group_by_assessor($pat_id, $ver_num));

			/** Sets the current count of assessment result into an data array. */
			$data = array(
				'desc_assess_count' => $current_assess_count
				);

			/** Then, the update_assess_count() function will be called in order to increase by 1. */
			$this->mPatternDesc->update_assess_count($pat_id, (float)$ver_num, $data);

			/** Set a pattern data object to update the status of the pattern to assessed. */
			$pat_data = array(
				'pattern_id' => $pat_id,
				'pattern_status' => 'Assessed'
				);

			/** Perform the update process of the pattern. */
			$this->mPattern->update_pattern($pat_data);
		}
	}