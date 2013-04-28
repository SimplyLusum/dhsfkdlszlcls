<? 

class Page {

	var $db;
	var $info;
	var $dir="upload/page_images/";
	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();

		if ($id!=0) {
			$this->id=$id;
			$this->info = $this->db->get('tbl_page_pg','pg_id=?',$this->id);		
		}
	}
	
	
	function get() { return $this->info; }
	function get_title() { return $this->info['pg_title']; }
	function get_pic() { return $this->dir . $this->info['pg_pic']; }
	function get_content1() { return $this->info['pg_desc1']; }
	function get_content2() { return $this->info['pg_desc2']; }
	function get_content3() { return $this->info['pg_desc3']; }
	function get_content4() { return $this->info['pg_desc4']; }
	

}