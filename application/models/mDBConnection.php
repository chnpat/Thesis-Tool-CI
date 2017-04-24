<?php 
	/**
	* 
	*/
	class mDBConnection extends CI_Model{
		
		public function __construct(){
			parent::__construct();
		}

		public function create($select, $cond, $from, $data){
			$this->db->select($select);
			if($cond != ""){
				$this->db->where($cond);
			}
			$this->db->from($from);
			if($this->db->get()->num_rows() == 0){
				$this->db->insert($from, $data);
				if($this->db->affected_rows() > 0){
					return true;
				}
			}
			return false;
		}

		public function select($select, $cond, $from, $limit = NULL){
			$this->db->select($select);
			if($cond != ""){
				$this->db->where($cond);
			}
			$this->db->from($from);
			if($limit == NULL){
				$this->db->limit($limit);
			}
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}
		}

		public function update($cond, $from, $data){
			if($cond != ""){
				$this->db->where($cond);
			}
			$this->db->update($from, $data);
			if($this->db->affected_rows() == 1){
				return true;
			}
			else{
				return false;
			}
		}

		public function delete($select, $cond, $from, $del_arr){
			$this->db->select($select);
			if($cond != ""){
				$this->db->where($cond);
			}
			$this->db->from($from);
			if($this->db->get()->num_rows() > 0){
				$this->db->delete($from, $del_arr);
				return true;
			}
			return false;
		}
	}

?>