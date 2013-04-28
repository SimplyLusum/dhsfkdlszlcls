<? 

class Order_Basket
{
    var $item=array();
	var $info;
	var $city=0;
	var $city_name="";
	var $shipping=0;
	var $install=0;
	var $measure=0;
	var $transid=0;
    var $ref=0;
    var $xml='';
    var $name='';
    var $company='';
    var $surname='';
    var $address='';
    var $post='';
    var $customernumber='';
    
	private $_ordernumber = 0;
    private $_customerhash = '';
    
    const ORDER_EXPIRY = 172800; // 48 hours
    
    /**
     * @var Order_Basket instance
     */
    private static $instance;
    
    /**
     * constructor
     */
    private function __construct()
    {
        $this->_ordernumber = isset($_GET['order']) ? (int)$_GET['order'] : 0;
        $this->_customerhash = isset($_GET['customer']) ? $_GET['customer'] : '';
        
        $this->get();
    }
    
    public function __destruct()
    {
        //$this->save();
    }
    
    public static function getinstance() {
        if ( !self::$instance )
            self::$instance = new self();
        
        return self::$instance;
    }
    
    public function setOrderNumber( $number ) {
        $this->_ordernumber = $number;
    }
    
    public function getOrderNumber($order) {
        //return $this->_ordernumber;
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where order_id='" . $order . "'";

		$rs=$db->Execute($sql);
		
		if ($rs->EOF) {
			return false;
		} else {
			
			return $rs->Fields("id");
		}
		
    }
    
    public function getCustomerHash() {
        return $this->_customerhash;
    }
    
    public function save() {
        $db = DB::getInstance();
		//$db->debug=true;
		//ob_start();
       
       // if ( $this->_ordernumber && $this->customernumber ) {
            $query = '
                SELECT
                    A.*
                FROM
                    tbl_basket A
                WHERE
                    A.order_id = ' . intval($this->_ordernumber);
            
            $items = $db->getAll( $query );
            
            $vars['order_id'] = intval($this->_ordernumber);
            $vars['name'] = $this->name;
            $vars['company'] = $this->company;
            $vars['surname'] = $this->surname;
            $vars['address'] = $this->address;
            $vars['post'] = $this->post;
            $vars['city'] = $this->city;
            $vars['customernumber'] = $this->customernumber;
            $vars['imosxml'] = $this->xml;
            $vars['created'] = time();
            
           
            $id = $db->insert( 'tbl_basket', $vars );
            
            $query = 'DELETE FROM tbl_basket_items WHERE basket_id = ' . intval($id);
            $db->query( $query );
           // $db->debug=true;
			
            foreach ( $this->item as $v ) {
            	
			    $vars = array();
                
                $vars['basket_id'] = $id;
                $vars['title'] = $v[0];
                $vars['description'] = $v[1];
                $vars['price'] = $v[2];
				$vars['dimension'] = $v[3];
				$vars['volumn'] = $v[4];
				$vars['pstring'] = $v[5];
				$vars['assembly'] = $v[6];
				
                
                $db->insert( "tbl_basket_items", $vars );
            }
            
           // $this->_ordernumber = 0;
			
			
        //}
		

		
      //  die();
       /* $_SESSION['Basket'] = array(
			'basketid' => $_SESSION['basketid'],
            'item' => $this->item,
            'info' => $this->info,
            'city' => $this->city,
            'city_name' => $this->city_name,
            'shipping' => $this->shipping,
            'install' => $this->install,
            'measure' => $this->measure,
            'transid' => $this->transid,
            'ref' => $this->ref,
            'xml' => $this->xml,
            'name' => $this->name,
            'company' => $this->company,
            'surname' => $this->surname,
            'address' => $this->address,
            'post' => $this->post,
            'customernumber' => $this->customernumber,
            );*/
			
		return $id;	
    }
    
    public function get() {
        if ( $this->_ordernumber ) {
            $db = DB::getInstance();
            
            $query = '
                SELECT
                    A.*,
                    B.title AS itemtitle,
                    B.description AS itemdescription,
                    B.price AS itemprice
                FROM
                    tbl_basket A
                LEFT JOIN
                    tbl_basket_items B ON (B.basket_id = A.id)
                WHERE
                    A.order_id = ' . intval($this->_ordernumber) . ' AND
                    A.created >= ' . ( time() - self::ORDER_EXPIRY );
            
            $items = $db->getAll( $query );
            
            if ( md5( @$items[0]['customernumber'] ) != $this->_customerhash )
                return false;
            
            if ( !empty( $items ) ) {
                $this->item = array();
                
                foreach ( $items as $v ) {
                    $this->name = $v['name'];
                    $this->company = $v['company'];
                    $this->surname = $v['surname'];
                    $this->address = $v['address'];
                    $this->post = $v['post'];
                    $this->city = $v['city'];
                    $this->customernumber = $v['customernumber'];
                    
                    $this->item[] = array( $v['itemtitle'], $v['itemdescription'], $v['itemprice'] );
                }
            }
        }
        else if ( isset($_SESSION['Basket']) ) {
            $Basket = $_SESSION['Basket'];
            
            $elements = array(
                'item' => array(),
                'info' => null,
                'city' => 0,
                'city_name' => '',
                'shipping' => 0,
                'install' => 0,
                'measure' => 0,
                'transid' => 0,
                'ref' => 0,
                'name' => '',
                'company' => '',
                'surname' => '',
                'address' => '',
                'post' => '',
                'customernumber' => '',
                );
            
            foreach ( $elements as $k => $v ) {
                if ( isset($Basket[ $k ]) ) {
                    $this->$k = $Basket[ $k ];
                }
            }
        }
    }
    
	function setId( $id = 0 ) { 
		if ( $id != 0 )
            $this->ref = $id;
	}
    
	function grant_payment( $ref ) {
		$db = DB::getInstance();
		$sql="Update tbl_transaction_tra set tra_status='paid' where tra_id='" . $ref . "'";
		
		$db->execute( $sql );
	}
	
	function get_transaction ($ref) {
		$db = DB::getInstance();	
		$db->debug=true;
		return $db->get("tbl_transaction_tra","trd_id=?",$ref);
		
	}
	
	function save_info( $vars ) {
		/*
		$this->info['tra_fname']=$vars['fname'];
		$this->info['tra_lname']=$vars['lname'];
		$this->info['tra_email']=$vars['email'];
		$this->info['tra_address']=$vars['address'];
		$this->info['tra_suburb']=$vars['suburb'];
		$this->info['tra_city']=$this->city_name;
		$this->info['tra_postcode']=$vars['postcode'];
		$this->info['tra_phone']=$vars['phone'];
		$this->info['tra_instruction']=$vars['instruction'];
		*/
		
		$db = DB::getInstance();
		
		$db->update("tbl_basket",$vars, "id=" . $_SESSION['basketid']);
		
		
	}
	
	
	function get_total() {
		$total=0;
		/*foreach ($this->item as $row) {
			$total+=$row[2];
		} */
		$db = DB::getInstance();
		$sql="Select sum(price) as num from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
		$rs=$db->execute( $sql );
		
		if (!is_null($rs->Fields("num"))) { $total=$rs->Fields("num"); } 
		
		//$total+=$this->shipping + $this->install + $this->measure;
		
		
		
		return $total;
	}
	
	
	
	function set_install($date) {
		$db = DB::getInstance();
		if ($date!="") { 
			
			
			$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
			$rs=$db->execute($sql);
			
			$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
			$rs=$db->execute($sql);
				
			$install=$rs->Fields("shp_price2");
		} else {
			$install=0;
		}
		
		$sql="Update tbl_basket set install='" . $install . "' where id='" . $_SESSION['basketid'] . "'";
		$db->execute($sql);
	}

	function set_measurement($date) {
		$db = DB::getInstance();
		if ($date!="") { 
			
			$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
			$rs=$db->execute($sql);
						
			$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
			$rs=$db->execute($sql);
				
			$measure=$rs->Fields("shp_price3");
			
		} else {
			$measure=0;
		}

		$sql="Update tbl_basket set measurement='" . $measure . "' where id='" . $_SESSION['basketid'] . "'";
		
		$db->execute( $sql );		
		
	}
	
	
	function set_shipping($answer) {
		$db = DB::getInstance();	
		if ($answer==1)	{
			$total=$this->get_shipping_cost();	
			$sql="Update tbl_basket set delivery='" . $total . "' where id='" . $_SESSION['basketid'] . "'";
			$db->execute( $sql );
		} else { 
			$sql="Update tbl_basket set delivery='0' where id='" . $_SESSION['basketid'] . "'";
			$db->execute( $sql );
		
		}		
		
	}
	
	function set_assembly($answer) {
		$db = DB::getInstance();
		if ($answer==1)	{
			$total=$this->get_assembly_cost();	
			$sql="Update tbl_basket set assembled='" . $total . "' where id='" . $_SESSION['basketid'] . "'";
			
			$db->execute( $sql );
		} else { 
			$sql="Update tbl_basket set assembled='0' where id='" . $_SESSION['basketid'] . "'";
			$db->execute( $sql );
		
		}
		
	}
	
	function get_measurement_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->execute($sql);
					
		$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
		
		$rs=$db->execute($sql);
		
		return $rs->Fields("shp_price3");		
	
	}
	
	
	function get_installation_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->execute($sql);
					
		$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
		
		$rs=$db->execute($sql);
		
		return $rs->Fields("shp_price2");		
	
	}	
	
	function save_transaction($paytype) {
		$db = DB::getInstance();
		$item="";
		/*
		foreach ($this->item as $row) {
			$item.=$row[0] . " (" . $row[1] . ") @ " . $row[2] . "\n";
		}*/
		
		$sql="Select * from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
		$rs=$db->getAll($sql);
		
		foreach ($rs as $row) {
			$item.=$row['title'] . "(Dimension:" . $row['dimension'] . ", Price: $" . number_format($row['price'],2) . ", Volumn: " . $row['volumn'] . ")\n";
		}
		
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);		
		
		$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
		$rs_cty=$db->execute($sql);
		
		$vars=array();
		$vars['tra_fname']=$rs->Fields("name");
		$vars['tra_lname']=$rs->Fields("surname");
		$vars['tra_email']=$rs->Fields("email");
		$vars['tra_address']=$rs->Fields("address");
		$vars['tra_suburb']=$rs->Fields("suburb");
		$vars['tra_city']=$rs_cty->Fields("shp_title");
		$vars['tra_postcode']=$rs->Fields("post");
		$vars['tra_subtotal']=$this->get_total();
		$vars['tra_shipping']=$rs->Fields("delivery");
		$vars['tra_admincost']=$this->get_admin_cost();
		$vars['tra_assembly']=$rs->Fields("assembled");
		$vars['tra_install']=$rs->Fields("install");
		$vars['tra_measure']=$rs->Fields("measurement");
		$vars['tra_total']=$this->get_amount();
		$vars['tra_date']=mktime();
        $vars['tra_desc']=$item;
        $vars['tra_paytype']=$paytype;
		$vars['tra_imosxml']=$rs->Fields("imosxml");
        $vars['tra_type']=$rs->Fields("type");
		$vars['tra_measure_date']=$rs->Fields("measurement_date");
		$vars['tra_install_date']=$rs->Fields("install_date");

		$id=$db->insert( "tbl_transaction_tra", $vars ); 
		
		return $id;
	}
	
	function get_shipping_list() {
		$sql="select * from tbl_shipping_shp where shp_status=1 order by shp_order";
		return DB::getInstance()->getAll($sql);
	}
	
	function set_city($id) { 
		$db = DB::getInstance();
		$this->city=$id;
		$sql="Select * from tbl_shipping_shp where shp_id='" . $this->city . "'";
		$rs=$db->execute($sql);
		
		$this->city_name=$rs->Fields("shp_title"); 
		$this->shipping=$rs->Fields("shp_price1"); 
	}
	
	function get_city() { 
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);		
		
		$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
		$rs_cty=$db->execute($sql);
	
		return $rs_cty->Fields("shp_title");
	
	}

	function add_to_cart( $title, $desc, $total, $dimension, $volumn, $pstring, $assembly ) { $this->item[] = array( $title, $desc, $total, $dimension, $volumn, $pstring, $assembly ); }
	function remove_from_cart($id) {
        unset($this->item[$id]);
        $this->save();
    }
	function get_cart_list() { 
		$db = DB::getInstance();
		$sql="Select * from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
		
		return $db->getAll($sql);
		
	}
	function get_info() { 
		$db = DB::getInstance();
		return $db->get("tbl_basket","id=?",$_SESSION['basketid']);
	}
	
	
	function get_admin_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);
		
		$total=0;
		
		if ($rs->Fields("type")=="assembled") {
			$sql="Select * from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
			$rs_itm=$db->Execute($sql);
			
			while (!$rs_itm->EOF) { 
				$sql="Select * from tbl_code_cod where cod_name='" . $rs_itm->Fields("title") . "'";
				$rs_cod=$db->Execute($sql);
				
				if (!$rs_cod->EOF) { $total+=$rs_cod->Fields("cod_price"); } else { }
				
			$rs_itm->MoveNext(); }
		
		} else {
			
			$sql="Select count(basket_id) as num from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
			$rs_itm=$db->Execute($sql);	
			
			$total=$rs_itm->Fields("num")*15;		
		
		}
		
		return $total;
		return 0;
	
	}
	
	
	function get_shipping_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	

		$total=0;
		
		//if ($rs->Fields("type")=="assembled") {
			$sql="Select sum(volumn) as num from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
			$rs_itm=$db->Execute($sql);	
			if (!is_null($rs_itm->Fields("num"))) { $volumn=$rs_itm->Fields("num"); } else { $volumn=0; }
		//} else {
			// flatpack
		
		//}
		
		$sql="Select * from tbl_shipping_shp where shp_id='" . $rs->Fields("city") . "'";
		$rs_shp=$db->Execute($sql);	
		
		return $rs_shp->Fields("shp_price1") * $volumn;
		
	}
	
	function get_assembly_cost() { 
		$db = DB::getInstance();
	
		
		$total=0;
		
		
			$sql="Select * from tbl_basket_items where basket_id='" . $_SESSION['basketid'] . "'";
			$rs_itm=$db->Execute($sql);
			
			while (!$rs_itm->EOF) { 
				$sql="Select * from tbl_code_cod where cod_name='" . $rs_itm->Fields("title") . "'";
				$rs_cod=$db->Execute($sql);
				
				if (!$rs_cod->EOF) { 
					if ($rs_itm->Fields("assembly")==1) { 	$total+=$rs_cod->Fields("cod_ass_price1");  } else { 	$total+=$rs_cod->Fields("cod_ass_price2"); }
				} else { }
				
			$rs_itm->MoveNext(); }
		
		
		
		return $total;
	
	
	}
	
	function get_summary() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		
		
		$tmp=array();
		$tmp['subtotal']=$this->get_total() + $this->get_admin_cost();
		$tmp['measurement']=$rs->Fields("measurement");
		$tmp['assembly']=$rs->Fields("assembled");
		$tmp['shipping']=$rs->Fields("delivery");
		$tmp['installation']=$rs->Fields("install");
		
		return $tmp;
		
	}
	
	
	function get_amount() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		
		$install=$rs->Fields("install");
		$measurement=$rs->Fields("measurement");
		$assembly=$rs->Fields("assembled");
		$shipping=$rs->Fields("delivery");
		
		$total=$this->get_total() + $this->get_admin_cost() + $assembly + $shipping + $install + $measurement;

		return $total;
	}
	
	function get_install_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		return $rs->Fields("install");
	}
	
	function get_measure_cost() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		return $rs->Fields("measurement");
	}	
	
	function is_assembly() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		if ($rs->Fields("assembled")==0) { return false; } else { return true; }
		
	}
	
	function is_measurement() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		if ($rs->Fields("measurement")==0) { return false; } else { return true; }
		
	}	
	
	function is_shipping() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		if ($rs->Fields("delivery")==0) { return false; } else { return true; }
		
	}	
	
	function is_install() {
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where id='" . $_SESSION['basketid'] . "'";
		$rs=$db->Execute($sql);	
		if ($rs->Fields("install")==0) { return false; } else { return true; }
		
	}			
	
	function empty_cart() {  unset ($this->item); }
	
	function get_shipping() { return $this->shipping; }
	function get_install() { return $this->install; }
}