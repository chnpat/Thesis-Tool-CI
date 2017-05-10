<?php 
	interface iAssessResult {
		public function create_result($data);
		public function get_result($pat_id, $ver, $ass_id);
		public function update_result($pat_id, $ver, $ass_id, $data);
		public function delete_result($result_id); 
	}

?>