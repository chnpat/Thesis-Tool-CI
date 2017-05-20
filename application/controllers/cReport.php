<?php 
	use Dompdf\Dompdf;
	class cReport extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model(array('mLogin', 'mPattern', 'mPatternDesc',
				'Result_implementation/mAssessResult',
				'Result_implementation/mPAPResult',
				'Result_implementation/mCREResult',
				'Result_implementation/mCSDResult',
				'Result_implementation/mDKTResult'));
		}

		public function index($report = NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Report';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				$detail['pattern_list'] = $this->mPattern->get_pattern_all();
				if($report != NULL){
					$detail['report'] = $report;
				}
				else{
					$detail['report'] = NULL;
				}
				$this->load->view('templates/header', $data);
				$this->load->view('vReport', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		public function generate_report(){
			$pat_id = $this->input->post('report_pattern');
			$ver_num = $this->input->post('report_desc_version');
			if(!is_numeric($ver_num) AND $ver_num != ""){
				$this->session->set_flashdata('report_error', 'Invalid specified version number.');
				$this->index();
			}
			else{
				$ver_num = ($ver_num == "")? NULL: $ver_num;

				$pat = $this->mPattern->get_pattern($pat_id);
				$desc = $this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$ver_num);
				if(!is_bool($desc)){
					$metric_w_result['info']['assessor_list'] = $this->mAssessResult->get_result_group_by_assessor($pat_id, (float)$ver_num);
					$metric_w_result['info']['pattern_id'] = $pat_id;
					$metric_w_result['info']['version'] = $ver_num;
					$metric_w_result['data']['metric_list'] = array(
															"PAP" => $this->mPAPResult->get_metric(),
															"CRE" => $this->mCREResult->get_metric(),
															"CSD" => $this->mCSDResult->get_metric(),
															"DKT" => $this->mDKTResult->get_metric()
															);
					$metric_w_result['data']['result'] = array(
													"PAP" => $this->mPAPResult->get_result_all($pat_id, (float)$ver_num),
													"CRE" => $this->mCREResult->get_result_all($pat_id, (float)$ver_num),
													"CSD" => $this->mCSDResult->get_result_all($pat_id, (float)$ver_num),
													"DKT" => $this->mDKTResult->get_result_all($pat_id, (float)$ver_num)
													);
					$this->index($metric_w_result);
				}
				else{
					$this->session->set_flashdata('report_error', 'The specified version number is not existing.');
					$this->index();
				}
			}
		}

		public function generate_pdf($pat_id, $ver=NULL){
			$this->load->helper(array('dompdf', 'file'));
			// Set Data
			$user_id = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0]['id'];
			$pattern = $this->mPattern->get_pattern($pat_id);
			$description = $this->mPatternDesc->get_pattern_description_by_pattern($pat_id, (float)$ver);
			$report = array();
			if(!is_bool($description)){
				$report['result'] = $this->mAssessResult->get_all_result_by_pattern($pat_id, (float)$ver);
				foreach ($report['result'] as $key => $value) {
					$report['result'][$key]['detail'] = $this->mAssessResult->get_all_result_detail_w_metric($value['result_id']);
				}
			}
			else{
				$report = NULL;
			}
			$data = array(
				'pattern' 		=> $pattern,
				'description' 	=> $description,
				'report'		=> $report
				);

			$html = $this->load->view('vPDF', $data, TRUE);
			
     		pdf_create($html, 'Report-'.$user_id.'-'.date('YmdHis'));
		}
	}