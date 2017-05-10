<?php 
	/**
	* 
	*/
	class mAssessResult extends CI_Model
	{
		
		public function __construct()
		{
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}
	}
?>