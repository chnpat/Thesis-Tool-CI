<?php 
	class cDashboard extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model(array('mLogin', 'mPattern', 'mPatternDesc'));
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Dashboard';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];

				if($data['userObj']['user_role'] == 'Regular'){
					$detail['pending_list'] = $this->mPattern->get_only_ready_pattern_no_assessed($data['userObj']['id']);
					$detail['unreach_list'] = $this->mPattern->get_pattern_unreach_limit_assess($data['userObj']['id']);
					$detail['reach_list'] = $this->mPattern->get_pattern_reach_limit_assess($data['userObj']['id']);
					$detail['pattern_list'] = $this->mPattern->get_pattern_by_developer($data['userObj']['id']);

					$detail['Number_of_patterns'] = count($detail['pattern_list']);
					$detail['Number_of_pending'] = count($detail['pending_list']);
					$detail['Number_of_unreach'] = count($detail['unreach_list']);
					$detail['Number_of_reach'] = count($detail['reach_list']);
				}
				else{
					$detail['pending_list'] = $this->mPattern->get_only_ready_pattern_no_assessed();
					$detail['unreach_list'] = $this->mPattern->get_pattern_unreach_limit_assess();
					$detail['reach_list'] = $this->mPattern->get_pattern_reach_limit_assess();
					$detail['pattern_list'] = $this->mPattern->get_pattern_all();

					$detail['Number_of_patterns'] = count($detail['pattern_list']);
					$detail['Number_of_pending'] = count($detail['pending_list']);
					$detail['Number_of_unreach'] = count($detail['unreach_list']);
					$detail['Number_of_reach'] = count($detail['reach_list']);
				}

				$this->load->view('templates/header', $data);
				$this->load->view('vDashboard', $detail);
				$this->load->view('templates/footer');	
			}		
		}
	}