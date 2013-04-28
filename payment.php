<?  include ("include/include.php"); 
    include ("class/dps.class.php"); 
    include ("class/emailform.class.php"); 
    require_once( 'include/Order/Basket.php' );
    $Basket = Order_Basket::getinstance();
    
	$dps=new DPS_PxAccess(); // use default test account;
    
	$result = $dps->make_payment( $Basket->get_amount(), $_SESSION['transid'] );
	
	if (is_array($result)) {
			
			if ($result['status']=="success") {
				
				$tra=$Basket->get_transaction($_SESSION['transid']);
				
				if ($tra['tra_status']=="pending") {			
					Order_Basket::grant_payment( $result['reference'] );
					$info=$Basket->get_info();	
					$tmp=array();
					$tmp['name']=$info['name']; $tmp['reference']=$_SESSION['transid'];
						
					$sendto= Setting::get_value("email_order");
						
					$email=new EmailForm(9);
					$email->generate_email($tmp);
					$email->send($info['name'],$info['email']);
					$email->send("Admin",$sendto);
					
					
					
					
				}
				header("Location: success"); die(); 
				
			} else {
			
				header("Location: fail"); die(); 
				
			}
		
	}
			
		

?>