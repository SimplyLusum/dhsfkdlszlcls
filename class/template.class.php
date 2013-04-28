<? 

class Template {

	var $db;
	var $info;
	var $dir="upload/template_images/";
	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();

		if ($id!=0) {
			$this->id=$id;
			$this->info = $this->db->get('tbl_template_tmp','tmp_id=?',$this->id);		
		}
	}
	
	function get_list() {
		$sql="Select * from tbl_template_tmp where tmp_status=1 order by tmp_order";
		return $this->db->getAll($sql);	
	}
	
	function get_dir() { return $this->dir; }
	

}