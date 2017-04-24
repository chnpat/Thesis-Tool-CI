<?php 
	class cPattern extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array('mLogin','mPattern', 'mPatternDesc'));
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Pattern List';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$detail['rows'] = (is_bool($this->mPattern->get_pattern_all()))? array():$this->mPattern->get_pattern_all();
				$this->load->view('templates/header', $data);
				$this->load->view('vPattern', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		public function pattern_detail($id=NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Pattern Detail';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$detail['rows'] = $this->mPattern->get_pattern($id);
				$detail['desc_by_pattern'] = (is_bool($this->mPatternDesc->get_pattern_description_by_pattern($id)))? array():$this->mPatternDesc->get_pattern_description_by_pattern($id); 
				if(is_bool($detail['rows'])){
					$detail['rows'] = NULL;
				}
				$this->load->view('templates/header', $data);
				$this->load->view('pattern_management/vPatternDetail', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		public function add_pattern(){
			$this->form_validation->set_rules('pattern_id', 'Pattern ID', 'required');
			$this->form_validation->set_rules('pattern_name', 'Pattern Name', 'required');
			$this->form_validation->set_rules('pattern_assess_limit', 'Pattern Assess Limit', 'required|greater_than[-1]');
			$this->form_validation->set_rules('pattern_assess_version', 'Pattern Assess Version', 'greater_than[-1]');
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('pattern_error', validation_errors());
				$this->pattern_detail();
			}
			else{
				$data = array(
					'pattern_id' => $this->input->post('pattern_id'),
					'pattern_name' => $this->input->post('pattern_name'),
					'pattern_creator_id' => $this->input->post('pattern_creator_id'),
					'pattern_assess_limit' => $this->input->post('pattern_assess_limit'),
					'pattern_assess_version' => $this->input->post('pattern_assess_version'),
					'pattern_status' => $this->input->post('pattern_status')
					);
				$result = $this->mPattern->create_pattern($data);
				if($result){
					$this->session->set_flashdata("pattern_msg", "A pattern id: '".$this->input->post('pattern_id')."' has been added successfully!");
					$this->index();
				}
				else{
					$this->session->set_flashdata("pattern_error", "A pattern ID (".$this->input->post('pattern_id').") is duplicated!");
					$this->pattern_detail();
				}
			}

		}

		public function edit_pattern($id){
			$this->form_validation->set_rules('pattern_id', 'Pattern ID', 'required');
			$this->form_validation->set_rules('pattern_name', 'Pattern Name', 'required');
			$this->form_validation->set_rules('pattern_assess_limit', 'Pattern Assess Limit', 'required|greater_than[-1]');
			$this->form_validation->set_rules('pattern_assess_version', 'Pattern Assess Version', 'greater_than[-1]');
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('pattern_error', validation_errors());
				$this->pattern_detail();
			}
			else{
				$data = array(
					'pattern_id' => $this->input->post('pattern_id'),
					'pattern_name' => $this->input->post('pattern_name'),
					'pattern_creator_id' => $this->input->post('pattern_creator_id'),
					'pattern_assess_limit' => $this->input->post('pattern_assess_limit'),
					'pattern_assess_version' => $this->input->post('pattern_assess_version'),
					'pattern_status' => $this->input->post('pattern_status')
					);
				$result = $this->mPattern->update_pattern($data);

				if($result){
					$this->session->set_flashdata("pattern_msg", "A pattern id: '".$this->input->post('pattern_id')."' has been updated successfully!");
					$this->index();
				}
				else{
					$this->session->set_flashdata("pattern_error", "A pattern (ID:".$id.") update process is failed!");
					$this->pattern_detail($id);
				}
			}
		}
		public function delete_pattern($id){
			if($this->mPattern->delete_pattern($id)){
				$this->session->set_flashdata("pattern_msg", "A pattern (ID:".$id.") has been deleted successfully!");
				$this->index();
			}
			else{
				$this->session->set_flashdata("pattern_error", "A pattern (ID:".$id.") cannot be deleted!");
				$this->index();
			}
		}
	}