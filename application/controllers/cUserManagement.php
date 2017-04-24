<?php 
	class cUserManagement extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model('mLogin');
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'User Management';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$list['rows'] = $this->mLogin->get_user_all();
				$this->load->view('templates/header', $data);
				$this->load->view('vUserManagement', $list);
				$this->load->view('templates/footer');	
			}		
		}

		public function user_detail($id = NULL){
			$data['title'] = 'Add User';
			$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
			$result = $this->mLogin->get_user_by_id($id);
			if(!is_bool($result)){
				$detail['userDetail'] = $result[0];
			}
			else{
				$detail['userDetail'] = NULL;	
			}
			$this->load->view('templates/header', $data);
			$this->load->view('user_management/vUserDetail', $detail);
			$this->load->view('templates/footer');
		}

		public function add_user(){
			$this->form_validation->set_rules('fname','Username','required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('cpassword', 'Confirm_Password', 'required|matches[password]');
			$this->form_validation->set_rules('role', 'Role', 'required|greater_than[0]');
			if($this->form_validation->run() == FALSE){
				// if the form validation is failed, show the error message and redirect to the user detail form
				$this->session->set_flashdata('detail_error', validation_errors());
				$this->user_detail();
			}
			else{
				switch ($this->input->post('role')) {
					case '1':
						$role = 'Admin';
						break;
					case '2':
						$role = 'Assessor';
						break;
					case '3':
						$role = 'Regular';
						break;
					default:
						$role = 'Regular';
						break;
				}
				// get user data from the input form
				$data = array(
					'user_name' => $this->input->post('fname'),
					'user_email' => $this->input->post('email'),
					'user_password' => sha1($this->input->post('password')),
					'user_status' => ($this->input->post('status')? True:False),
					'user_role' => $role
					);	
				$result = $this->mLogin->create_user($data); // insert data into database
				if($result == TRUE){
					//echo "<script type='text/javascript'>alert('A user (".$data['user_email'].") has been added successfully!')</script>";
					$this->session->set_flashdata('user_msg', "A user (".$data['user_email'].") has been added successfully!");
					$this->index();	// reload the index function and redirect to the dashboard
				}
				else{
					$this->session->set_flashdata("error", "Email (".$data['user_email'].") has already been used!");
					//echo "<script type='text/javascript'>alert('Email (".$data['user_email'].") has already been used!')</script>";
					$this->user_detail();	// Show erro message in case the email is used
				}
			}
		}

		public function update_user($id){
			$this->form_validation->set_rules('fname','Username','required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('cpassword', 'Confirm_Password', 'required|matches[password]');
			$this->form_validation->set_rules('role', 'Role', 'required|greater_than[0]');
			if($this->form_validation->run() == FALSE){
				// if the form validation is failed, show the error message and redirect to the user detail form
				$this->session->set_flashdata('detail_error', validation_errors());
				$this->user_detail();
			}
			else{
				switch ($this->input->post('role')) {
					case '1':
						$role = 'Admin';
						break;
					case '2':
						$role = 'Assessor';
						break;
					case '3':
						$role = 'Regular';
						break;
					default:
						$role = 'Regular';
						break;
				}
				// get user data from the input form
				$data = array(
					'id' => $id,
					'user_name' => $this->input->post('fname'),
					'user_email' => $this->input->post('email'),
					'user_password' => sha1($this->input->post('password')),
					'user_status' => ($this->input->post('status')? True:False),
					'user_role' => $role
					);	
				$result = $this->mLogin->update_user($data); // insert data into database
				if($result == TRUE){
					$this->session->set_flashdata("user_msg", "A user (".$data['user_email'].") has been updated successfully!");
					//echo "<script type='text/javascript'>alert('A user (".$data['user_email'].") has been updated successfully!')</script>";
					$this->index();	// reload the index function and redirect to the dashboard
				}
				else{
					$this->session->set_flashdata("error", "Email (".$data['user_email'].") has already been used!");
					//echo "<script type='text/javascript'>alert('Email (".$data['user_email'].") has already been used!')</script>";
					$this->user_detail();	// Show erro message in case the email is used
				}
			}
		}

		public function delete_user($id){
			if($this->mLogin->delete_user($id)){
				$this->session->set_flashdata("user_msg", "A user (ID:".$id.") has been deleted successfully!");
				//echo "<script type='text/javascript'>alert('A user (ID:".$id.") has been deleted successfully!')</script>";
				$this->index();
			}
			else{
				$this->session->set_flashdata("user_error", "Delete user id: ".$id." failed!");
				//echo "<script type='text/javascript'>alert('Delete user id: ".$id." failed!')</script>";
				$this->index();
			}
		}
	}