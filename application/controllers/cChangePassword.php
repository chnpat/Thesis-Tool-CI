<?php 
	class cChangePassword extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->helper('form', 'url', 'html');
			$this->load->model('mLogin');
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Change Password';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$this->load->view('templates/header', $data);
				$this->load->view('vChangePassword');
				$this->load->view('templates/footer');	
			}		
		}

		public function change_process(){
			$this->form_validation->set_rules('oldPassword', 'Old Password', 'required');
			$this->form_validation->set_rules('newPassword', 'New Password', 'required');
			$this->form_validation->set_rules('cNewPassword', 'Confirm New Password', 'required|matches[newPassword]');
			if($this->form_validation->run() == FALSE){
				$this->index();
			}
			else{
				$oldPassword = sha1($this->input->post('oldPassword'));
				$newPassword = sha1($this->input->post('newPassword'));
				$result = $this->mLogin->change_password($this->session->userdata('email'), $oldPassword, $newPassword);
				if(is_string($result)){
					$this->session->set_flashdata('nomatch', $result);
				}
				if(is_bool($result)){
					if($result){
						//echo "<script type='text/javascript'>alert('Change Successfully!')</script>";
						$this->session->set_flashdata('success', 'Password has been changed successfully.');
					}
					else{
						//echo "<script type='text/javascript'>alert('Change Failed!')</script>";
						$this->session->set_flashdata('error', 'Password changing process failure!');
					}
				}
				$this->index();
			}
		}
	}