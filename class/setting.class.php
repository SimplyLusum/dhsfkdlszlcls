<? 

class Setting {
	
	var $id;
	var $db;
	var $info;

	

	/* Construction */
	function __construct() { 
		$this->db = DB::getInstance();
	}
	

	
	static function get_value($code) { 
			
		$db = DB::getInstance();
		$info = $db->get('tbl_setting_set','set_name=?',$code);		
		
		return $info['set_value']; 
	}
	
	

}