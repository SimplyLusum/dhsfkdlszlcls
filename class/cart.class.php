<? 

class Cart {
	
	var $item=array();
	var $db;
	var $info;
	var $city=0;
	var $city_name="";
	var $shipping=0;
	var $install=0;
	var $measure=0;
	var $transid=0;
	var $ref=0;

	

	/* Construction */
	function __construct($id=0) { 
		$this->db = DB::getInstance();
		if ($id!=0) { $this->ref=$id; }
	}
	
	function grant_payment($ref) {
		$db = DB::getInstance();
		$sql="Update tbl_transaction_tra set tra_status='paid' where tra_id='" . $ref . "'";
		
		$db->execute($sql);
	}
	
	function save_info ($vars) {
		$this->info['tra_fname']=$vars['fname'];
		$this->info['tra_lname']=$vars['lname'];
		$this->info['tra_email']=$vars['email'];
		$this->info['tra_address']=$vars['address'];
		$this->info['tra_suburb']=$vars['suburb'];
		$this->info['tra_city']=$this->city_name;
		$this->info['tra_postcode']=$vars['postcode'];
		$this->info['tra_phone']=$vars['phone'];
		$this->info['tra_instruction']=$vars['instruction'];
	}
	
	
	function get_total() {
		$total=0;
		foreach ($this->item as $row) {
			$total+=$row[2];
		}
		$total+=$this->shipping + $this->install + $this->measure;
		return $total;
	}
	
	
	
	function set_install($answer) {
		if ($answer=="yes") { 
			$db = DB::getInstance();
			$sql="Select * from tbl_shipping_shp where shp_id='" . $this->city . "'";
			$rs=$db->execute($sql);
				
			$this->install=$rs->Fields("shp_price2");
		} else {
			$this->install=0;
		}
	}

	function set_measurement($answer) {
		if ($answer=="yes") { 
			$db = DB::getInstance();
			$sql="Select * from tbl_shipping_shp where shp_id='" . $this->city . "'";
			$rs=$db->execute($sql);
				
			$this->measure=$rs->Fields("shp_price3");
		} else {
			$this->measure=0;
		}
		
		
	}
	
	function save_transaction() {
		$db = DB::getInstance();
		$item="";
		foreach ($this->item as $row) {
			$item.=$row[0] . " (" . $row[1] . ") @ " . $row[2] . "\n";
		}

		$vars=array();
		$vars['tra_shipping']=$this->shipping;
		$vars['tra_install']=$this->install;
		$vars['tra_measure']=$this->measure;
		$vars['tra_total']=$this->get_total();
		$vars['tra_date']=mktime();
		$vars['tra_desc']=$item;
		$vars=array_merge($vars,$this->info);
		
		$id=$db->insert("tbl_transaction_tra",$vars); 
		
		$this->transid=$id;
		
		$tmp=array();
		foreach ($this->item as $row) {
			$tmp['itm_tra_id']=$id;
			$tmp['itm_title']=$row[0];
			$tmp['itm_desc']=$row[1];
			$tmp['itm_price']=$row[2];
			$db->insert("tbl_transitem_itm",$tmp); 
		}
		
		return $id;
	}
	
	function get_shipping_list() {
		$sql="select * from tbl_shipping_shp where shp_status=1 order by shp_order";
		return $this->db->getAll($sql);
	}
	
	function set_city($id) { 
		$db = DB::getInstance();
		$this->city=$id;
		$sql="Select * from tbl_shipping_shp where shp_id='" . $this->city . "'";
		$rs=$db->execute($sql);
		
		$this->city_name=$rs->Fields("shp_title"); 
		$this->shipping=$rs->Fields("shp_price1"); 
	}
	function get_city() { return $this->city_name; }

	function add_to_cart($title,$desc,$total) { $this->item[]=array($title,$desc,$total); }
	function remove_from_cart($id) {  unset($this->item[$id]); }
	function get_cart_list() { return $this->item; 	}
	function get_info() { return $this->info; 	}
	function empty_cart() {  unset ($this->item); }
	
	function get_shipping() { return $this->shipping; }
	function get_install() { return $this->install; }


}