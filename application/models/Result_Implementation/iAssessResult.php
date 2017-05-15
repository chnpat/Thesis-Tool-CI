<?php 
	interface iAssessResult {
		public function create_result($pat_id, $ver, $ass_id, $data);
		public function get_result($pat_id, $ver, $ass_id);
		public function update_result($pat_id, $ver, $ass_id, $data);
	}

?>