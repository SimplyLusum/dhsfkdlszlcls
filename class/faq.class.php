<?php 

class Faq {
	
	var $id;
	var $db;
	var $info;

	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();
	}
	

	
	function get_list($group) {
		$sql="Select * from tbl_faq_faq where faq_status=1 and group_id = '$group' order by faq_order";
		return $this->db->getAll($sql);
	}
	
	function get() { return $this->info; }	


}