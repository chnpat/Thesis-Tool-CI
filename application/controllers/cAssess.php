<?php 
	class cAssess extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model(array(
				'mLogin','mPattern', 'mPatternDesc', 
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
				$detail['list'] = (!is_bool($assess_list))? $assess_list: array();

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

		public function assess_detail($pat_id, $ver_num, $metr = NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Assessment Details';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				$pat = $this->mPattern->get_pattern($pat_id);
				$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, $pat['pattern_assess_version'])[0]: array();

				$detail = array(
					'pattern' => $pat,
					'description' => $desc
					);
				$detail['result'] = $this->get_metrics_w_results($pat['pattern_id'], $pat['pattern_assess_version'], $data['userObj']['id'], $this->get_metrics($metr));

				
				// foreach ($detail['result'] as $res) {
				// 	echo "<br/><b>Metric</b><br/>";
				// 	print_r($res);
				// }

				$this->load->view('templates/header', $data);
				$this->load->view('Assessment/vAssessmentDetail', $detail);
				$this->load->view('templates/footer');	
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
								$var = $this->mPAPResult->get_result_detail($result['result_id']);
							}
							break;
						case 'CRE':
							$result = $this->mCREResult->get_result($pat_id, $ver, $ass_id);
							if($result != NULL){
								$var = $this->mCREResult->get_result_detail($result['result_id']);
							}
							if($result == NULL OR $var == NULL){
								$pat = $this->mPattern->get_pattern($pat_id);
								$desc = (!is_bool($pat))?$this->mPatternDesc->get_pattern_description_by_pattern($pat_id, $pat['pattern_assess_version'])[0]: array();
								$tmp = array("pattern" => $pat, "description" => $desc, "ass_id" => $ass_id);
								$result = $this->mCREResult->create_result($tmp);
							}
							break;
						case 'CSD':
							$result = $this->mCSDResult->get_result($pat_id, $ver, $ass_id);
							if($result != NULL){
								$var = $this->mCSDResult->get_result_detail($result['result_id']);
							}
							break;
						case 'DKT':
							$result = $this->mDKTResult->get_result($pat_id, $ver, $ass_id);
							if($result != NULL){
								$var = $this->mDKTResult->get_result_detail($result['result_id']);
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
							foreach ($var as $v) {
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
	}