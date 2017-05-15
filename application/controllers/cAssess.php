<?php 
	class cAssess extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array(
				'mLogin','mPattern', 'mPatternDesc',
				'Result_implementation/mAssessResult', 
				'Result_implementation/mPAPResult',
				'Result_implementation/mCREResult',
				'Result_implementation/mCSDResult',
				'Result_implementation/mDKTResult'));
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Assessment List';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$assess_list = $this->mPattern->get_pattern_unreach_limit_assess();
				$done_list = $this->mPattern->get_pattern_reach_limit_assess();
				$detail['list'] = (!is_bool($assess_list))? $assess_list: array();
				$detail['done_list'] = (!is_bool($done_list))? $done_list: array();

				$this->load->view('templates/header', $data);
				$this->load->view('vAssessmentList', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		public function assess_choice($pat_id, $ver_num){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Quality Attributes Choices';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				
				$pat = $this->mPattern->get_pattern($pat_id);
				$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version']): array();
				if($desc[0]['desc_assess_count'] >= $pat['pattern_assess_limit'] AND $pat['pattern_assess_limit'] != 0){
					$this->index();
				}
				else{
					$detail = array(
						'pattern' => $pat,
						'pattern_description' => $desc[0]
						);

					$detail['metrics'] = array(
						'PAP' => $this->mPAPResult->get_metric(),
						'CRE' => $this->mCREResult->get_metric(),
						'CSD' => $this->mCSDResult->get_metric(),
						'DKT' => $this->mDKTResult->get_metric()
						);

					$detail['result'] = array(
						'PAP' => $this->mPAPResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'CRE' => $this->mCREResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'CSD' => $this->mCSDResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id']),
						'DKT' => $this->mDKTResult->get_result($pat_id, (float)$pat['pattern_assess_version'], $data['userObj']['id'])
						);

					$this->load->view('templates/header', $data);
					$this->load->view('Assessment/vAssessmentChoice', $detail);
					$this->load->view('templates/footer');	
				}
			}
		}

		public function assess_detail($pat_id, $ver_num, $metr = NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Assessment Details';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$pat = $this->mPattern->get_pattern($pat_id);

				if($metr == "CRE"){
					$detail['result'] = $this->get_metrics_w_results($pat['pattern_id'], $pat['pattern_assess_version'], $data['userObj']['id'], $this->get_metrics($metr));
					$this->session->set_flashdata("choice_msg", "Successful!! The Assessment of CRE Metric is automatically done.");
					$this->assess_choice($pat_id, $ver_num);	
				}
				else{
					$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$pat['pattern_assess_version'])[0]: array();

					$detail = array(
						'pattern' => $pat,
						'description' => $desc,
						'metr' => $metr,
						);
					$detail['result'] = $this->get_metrics_w_results($pat['pattern_id'], $pat['pattern_assess_version'], $data['userObj']['id'], $this->get_metrics($metr));
					$this->load->view('templates/header', $data);
					$this->load->view('Assessment/vAssessmentDetail', $detail);
					$this->load->view('templates/footer');
				}
			}
		}

		public function update_detail($pat_id, $ver_num, $metr = NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Assessment Details';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				$pat = $this->mPattern->get_pattern($pat_id);
				
				if($metr == 'CRE'){
					$this->mCREResult->update_result($pat_id, $ver_num, $data['userObj']['id'], array());
					$this->session->set_flashdata("choice_msg", "Successful!! The Assessment of CRE Metric is automatically updated.");
					$this->assess_choice($pat_id, $ver_num);
				}
				else{
					$this->assess_detail($pat_id, $ver_num, $metr);
				}
			}
		}

		public function update_result_proc($pat_id, $ver_num, $metr = NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$ass_id = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0]['id'];
				switch ($metr) {
					case 'PAP':
						$result = $this->mPAPResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] = $this->mPAPResult->get_result_detail($result['result_id']);
						}
						break;
					case 'CSD':
						$result = $this->mCSDResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] = $this->mCSDResult->get_result_detail($result['result_id']);
						}
						break;
					case 'DKT':
						$result = $this->mDKTResult->get_result($pat_id, $ver_num, $ass_id);
						if($result != NULL){
							$result['detail'] = $this->mDKTResult->get_result_detail($result['result_id']);
						}
						break;
					default:
						$result = NULL;
						break;
				}
				$metrics_list = $this->get_metrics($metr);
				
				if($result == NULL){
					foreach ($metrics_list as $k => $v) {
						$data = array();
						foreach ($v['detail'] as $key => $value) {
							if($this->input->post($value['id']."_var") != ""){
								$data[$value['id']] = (float)$this->input->post($value['id']."_var");
							}
						}
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
				else{
					foreach ($metrics_list as $k => $v) {
						$data = array();
						foreach ($v['detail'] as $key => $value) {
							if($this->input->post($value['id']."_var") != ""){
								$data[$value['id']] = (float)$this->input->post($value['id']."_var");
							}
						}
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
				$this->set_assess_count($pat_id, $ver_num, $ass_id);
				$this->assess_choice($pat_id, $ver_num);
				
			}
		}


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

		function get_metrics_w_results($pat_id, $ver, $ass_id, $metrics_list){
			if($metrics_list != NULL){
				foreach ($metrics_list as $metr) {
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
								$pat = $this->mPattern->get_pattern($pat_id);
								$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, $pat['pattern_assess_version'])[0]: array();
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
					if($result != NULL){
						$metrics_list[$metr['metric_abberv']]["result"] = $result["score"];
						$count = 0;
						foreach ($metr['detail'] as $det) {
							foreach ($result['detail'] as $v) {
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

		function set_assess_count($pat_id, $ver_num, $ass_id){
			$current_assess_count = count($this->mAssessResult->get_result_group_by_assessor($pat_id, $ver_num));
			$data = array(
				'desc_assess_count' => $current_assess_count
				);
			$this->mPatternDesc->update_assess_count($pat_id, (float)$ver_num, $data);
			$pat_data = array(
				'pattern_id' => $pat_id,
				'pattern_status' => 'Assessed'
				);
			$this->mPattern->update_pattern($pat_data);
		}
	}