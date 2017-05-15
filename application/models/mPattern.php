<?php
	/**
	* 
	*/
	class mPattern extends CI_Model{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection','d');
			$this->load->model('mPatternDesc', 'pd');
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

		public function get_pattern_unreach_limit_assess(){
			$pat_list = $this->get_pattern_all();
			if(!is_bool($pat_list)){
				return array_filter($pat_list, function($pat) {
					$cond = "pattern_id ='".$pat['pattern_id']."' AND desc_version =".(float)$pat['pattern_assess_version'];
					$counter = $this->d->select('desc_assess_count', $cond, 'pattern_description')[0]['desc_assess_count'];
					return (($pat['pattern_assess_limit'] == 0)? true:($counter < $pat['pattern_assess_limit'])) AND $pat['pattern_status'] != 'Disable';
			});
			}
			else{
				return $pat_list;
			}
		}
		public function get_pattern_reach_limit_assess(){
			$pat_list = $this->get_pattern_all();
			if(!is_bool($pat_list)){
				return array_filter($pat_list, function($pat) {
					$cond = "pattern_id ='".$pat['pattern_id']."' AND desc_version =".(float)$pat['pattern_assess_version'];
					$counter = $this->d->select('desc_assess_count', $cond, 'pattern_description')[0]['desc_assess_count'];
					return (($pat['pattern_assess_limit'] == 0)? false:($counter >= $pat['pattern_assess_limit'])) AND $pat['pattern_status'] != 'Disable';
			});
			}
			else{
				return $pat_list;
			}
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