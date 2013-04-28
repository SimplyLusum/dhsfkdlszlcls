<?php

/**
 * Process IMOS Order class
 */
class Order_Imosnet
{
    private $_rawXML;
    
    private $_data;
    
    private $_isPosted = false;
    
    /**
     * constructor
     */
    public function __construct()
    {
        // fix for php 5.2.2 bug
        if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])){
            $GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents('php://input');
        }
        
        if ( $GLOBALS['HTTP_RAW_POST_DATA'] ) {
            $this->_rawXML = $GLOBALS['HTTP_RAW_POST_DATA'];
            
			
            if ( $this->parseXML() ) {
				
                file_put_contents( 'include/Order/log/' . time() . '.log', $this->_rawXML );
                
                $this->parseXML();
                
                $this->_isPosted = true;
            } else {
                file_put_contents( 'include/Order/log/' . time() . '_error.log', $GLOBALS['HTTP_RAW_POST_DATA'] );
            }
        }
    }
    
    /**
     * is XML posted this request
     * @return bool is valid XML posted
     */
    public function isXMLPosted()
    {
        return $this->_isPosted;
    }
    
    public function isPosted($order) {
        //return $this->_isPosted;
		$db = DB::getInstance();
		$sql="Select * from tbl_basket where  order_id='" . $order . "'";
		$rs=$db->Execute($sql);
		
		if ($rs->EOF) {
			return false;
		} else {
			return true;
		}
    }
    
    public function getRawXML() {
        return $this->_rawXML;
    }
    
    public function getData() {
        return $this->_data;
    }
    
    private function parseXML() {
        $a = @simplexml_load_string( $this->_rawXML );
        
        if ( !$a )
            return false;
        
        $data = new SimpleXMLElement( $this->_rawXML );
        
        $this->_data = new stdClass();
        $this->_data->products = array();
        foreach ( $data->Order->BuilderList->Set as $v ) {
		
			$string=(string) $v->PVarString;
			$tmp=explode("|",(string) $v->PVarString);
			$w=explode(":=",$tmp[1]); $width=$w[1];
			$d=explode(":=",$tmp[2]); $depth=$d[1];
			$h=explode(":=",$tmp[3]); $height=$h[1];
			$valume=($width/1000)*($depth/1000)*($height/1000);
			
			$assembly=1;
			
			$res=strpos($string, "FRAME_DOOR_SWITCH:=FRAME_DOOR_HINGE");
			if ($res!=false) { $assembly=2; } 
            $this->_data->products[] = array(
			
                'name' => (string) $v->ARTICLE_TEXT_INFO1,
                'desc' => (string) $v->ARTICLE_TEXT_INFO2,
                'price' => (double) $v->ARTICLE_PRICE_INFO1,
                'Program' => (string) $v->Program,
                'count' => (int) $v->Count,
				
				'dimension' => (string) $v->ARTICLE_TEXT_INFO2,
				
				'volumn' => $valume,
				
				'pstring' => (string) $v->PVarString,
				
				'assembly' => $assembly
                );
        }
        $this->_data->name = (string) $data->Order->Head->INFO1;
        $this->_data->company = (string) $data->Order->Head->INFO2;
        $this->_data->surname = '';
        $this->_data->address = (string) $data->Order->Head->INFO3;
        $this->_data->post = (string) $data->Order->Head->INFO4;
        $this->_data->city = (string) $data->Order->Head->INFO5;
        $this->_data->customernumber = (string) $data->Order->Head->INFO6;
        
        $this->_data->ordernumber = '';
        foreach ( $data->Order->attributes() as $k => $v ) {
            if ( $k == 'No' ) {
                $this->_data->ordernumber = (string) $v;
                break;
            }
        }
        
        return true;
    }
}