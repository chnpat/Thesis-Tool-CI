<?php
	/**
	* 
	*/
	class mPattern extends CI_Model{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_pattern($data){
			$condition = "pattern_id ='".$data["pattern_id"]."'";
			return $this->d->create('*', $condition, 'pattern', $data);
		}

		public function get_pattern($id){
			$condition = "pattern_id = '".$id."'";
			$pattern = $this->d->select('*', $condition, 'pattern', 1);
			if(is_bool($pattern)){
				return $pattern;
			}
			return $pattern[0];
		}

		public function get_pattern_all(){
			$condition = "";
			$pattern = $this->d->select('*', $condition, 'pattern');
			return $pattern;
		}

		public function get_pattern_by_developer($dev_id){
			$condition = "pattern_creator_id = ".$dev_id."";
			$pattern = $this->d->select('*', $condition, 'pattern', 1);
			return $pattern;
		}

		public function get_pattern_reach_limit_assess($num){
			return array_filter($this->get_pattern_all(), function($pat) use($num){
				return $pat['pattern_assess_limit'] <= $num;
			});
		}

		public function update_pattern($data){
			$condition = "pattern_id ='".$data['pattern_id']."'";
			return $this->d->update($condition, 'pattern', $data);
		}

		public function delete_pattern($id){
			$condition = "pattern_id ='".$id."'";
			$pattern = $this->d->delete('*', $condition, 'pattern', array('pattern_id' => $id));
			if(!empty($this->d->select('*', $condition, 'pattern_description'))){
				$desc = $this->d->delete('*', $condition, 'pattern_description', array('pattern_id' => $id));
				return ($pattern AND $desc);
			}
			else{
				return $pattern;
			}
			
		}
	}
?>