<?php
	/**
	* 
	*/
	class mPatternDesc extends CI_Model{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_pattern_description($data){
			$condition = "pattern_id ='".$data["pattern_id"]."' AND desc_version = ".$data['desc_version'];
			return $this->d->create('*', $condition, 'pattern_description', $data);
		}

		public function get_pattern_description($id){
			$condition = "id ='".$id."'";
			return $this->d->select('*',$condition, 'pattern_description',1)[0];
		}

		public function get_pattern_description_by_pattern($pat_id, $ver = NULL){
			if($ver != NULL){
				$condition = "pattern_id = '".$pat_id."' AND desc_version ='".$ver."'";
			}
			else{
				$condition = "pattern_id ='".$pat_id."'";
			}
			return $this->d->select('*', $condition, 'pattern_description');
		}

		public function update_pattern_description($data, $pat_id=NULL, $id=NULL){
			if($id != NULL AND $pat_id == NULL){
				$condition = "id = ".$id;
			}
			else if($id == NULL AND $pat_id != NULL){
				$condition = "pattern_id = '".$pat_id."'";
			}
			else{
				$condition = "id = ".$id." AND pattern_id ='".$pat_id."'";
			}
			return $this->d->update($condition, "pattern_description", $data);
		}

		public function delete_pattern_description($id){
			$condition = "id = ".$id;
			return $this->d->delete('*', $condition, 'pattern_description', array('id' => $id));
		}
	}
?>