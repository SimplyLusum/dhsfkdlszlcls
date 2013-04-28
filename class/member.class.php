<? 

class Member {
	
	var $id;
	var $db;

	/* Construction */
	function __construct() { 
		$this->db = DB::getInstance();
	}
	

	
	function save($vars) {
		
		$tmp=array();
		$tmp['m_name']=$vars['name'];
		$tmp['m_email']=$vars['email'];
		$tmp['m_username']=$vars['username'];
		$tmp['m_password']=$vars['password'];
		$tmp['m_phone']=$vars['phone'];
		$tmp['m_mobile']=$vars['mobile'];
		$tmp['m_address']=$vars['address'];
		$tmp['m_suburb']=$vars['suburb'];
		$tmp['m_city']=$vars['city'];
		$tmp['m_date']=mktime();
		$tmp['m_status']=0;
		
		
		$this->db->insert("tbl_member_m",$tmp); 
		
	
	}
	
	
	function is_exist($email) {
		$sql="Select * from tbl_member_m where m_email='" . mysql_real_escape_string($email) . "'";
		$rs=$this->db->Execute($sql);
		
		if (!$rs->EOF) { return $rs; } else { return false; }
	
	}
	
	

}