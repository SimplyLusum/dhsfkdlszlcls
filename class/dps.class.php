<? 
require_once("include/pxaccess.inc");
class DPS_PxAccess {
	
	var $PxAccess_Url    = "https://www.paymentexpress.com/pxpay/pxpay.aspx";
	var $PxAccess_Userid = "eXeed_Dev";
	var $PxAccess_Key = "beceb819e0114d108a33573c93203ad4b732a7573f18591c405a27eae6991b59";
	var $Mac_Key = "405a27eae6991b59";
	var $data1 = "";
	var $data2 = "";
	var $data3 = "";
	var $type = "Purchase";
	var $amount = "";	
	var $reference = "";	
	var $currency="NZD";
	
	function __construct($userid="",$pxaccess_key="",$mac_key="") { 
		if ($userid!="") {
			$this->PxAccess_Userid=$userid;
			$this->PxAccess_Key=$pxaccess_key;
			$this->Mac_Key=$mac_key;
		}
	}
	
	function make_payment($amount=0,$reference=0,$data1="",$data2="",$data3="") {
		
		$this->data1=$data1;
		$this->data2=$data2;
		$this->data3=$data3;
		
		$this->amount=$amount;
		$this->reference=$reference;
		
		
	
		$pxaccess = new PxAccess($this->PxAccess_Url, $this->PxAccess_Userid, $this->PxAccess_Key, $this->Mac_Key);
		
		if (isset($_REQUEST["result"])) {  return $this->print_result($pxaccess); } else {  $this->redirect_form($pxaccess); }
	  	
	}
	
	
	function redirect_form($pxaccess) {
	
	//  global $pxaccess;

	  
	  $http_host   = getenv("HTTP_HOST");
	  $request_uri = getenv("SCRIPT_NAME");
	  $server_url  = "http://$http_host";
	  //$script_url  = "$server_url/$request_uri"; //using this code before PHP version 4.3.4
	  //$script_url  = "$server_url$request_uri"; //Using this code after PHP version 4.3.4
	  $script_url = (version_compare(PHP_VERSION, "4.3.4", ">=")) ?"$server_url$request_uri" : "$server_url/$request_uri";
		
	  
	  # the following variables are read from the form
	  #Set up PxPayRequest Object
	  
	  $request = new PxPayRequest();

	  $request->setAmountInput($this->amount);
	  $request->setTxnData1($this->data1);# whatever you want to appear
	  $request->setTxnData2($this->data2);		# whatever you want to appear
	  $request->setTxnData3($this->data3);		# whatever you want to appear
	  $request->setTxnType($this->type);
	  $request->setInputCurrency($this->currency);
	  $request->setMerchantReference($this->reference); # fill this with your order number
	  $request->setEmailAddress("");
	  $request->setUrlFail($script_url);
	  $request->setUrlSuccess($script_url);
	  
	  
	  #Call makeResponse of PxAccess object to obtain the 3-DES encrypted payment request 
	  $request_string = $pxaccess->makeRequest($request);
	  
	  header("Location: $request_string");
		
	}


	function print_result($pxaccess)	{
		//global $pxaccess;
		
		$enc_hex = $_REQUEST["result"];
		#getResponse method in PxAccess object returns PxPayResponse object 
		#which encapsulates all the response data
		
		$rsp = $pxaccess->getResponse($enc_hex);
		
		$array=array(); // return array
		
		if ($rsp->getStatusRequired() == "1") {
			$result = "An error has occurred.";
			$array['status']="fail";
			$array['text']="An error has occurred";
		}
		elseif ($rsp->getSuccess() == "1")	{
			$array['status']="success";
			$array['text'] = "The transaction has been approved";
		} else {
			$array['status']="fail";
			$array['text'] = "The transaction was declined.";
		}
		
		$array['authcode']=$rsp->getAuthCode();
		$array['reference']=$rsp->getMerchantReference();
		$array['dpsref']=$rsp->getDpsTxnRef();
		
		return $array;
		
		
		/*# the following are the fields available in the PxPayResponse object
		$Success           = $rsp->getSuccess();   # =1 when request succeeds
		$Retry             = $rsp->getRetry();     # =1 when a retry might help
		$StatusRequired    = $rsp->getStatusRequired();      # =1 when transaction "lost"
		$AmountSettlement  = $rsp->getAmountSettlement();    
		$AuthCode          = $rsp->getAuthCode();  # from bank
		$CardName          = $rsp->getCardName();  # e.g. "Visa"
		$DpsTxnRef	     = $rsp->getDpsTxnRef();
		
		# the following values are returned, but are from the original request
		$TxnType           = $rsp->getTxnType();
		$TxnData1          = $rsp->getTxnData1();
		$TxnData2          = $rsp->getTxnData2();
		$TxnData3          = $rsp->getTxnData3();
		$CurrencyInput     = $rsp->getCurrencyInput();
		$EmailAddress      = $rsp->getEmailAddress();
		$MerchantReference = $rsp->getMerchantReference();
		
		$MerchantTxnId = $rsp->getMerchantTxnId();
		$CardNumber = $rsp->getCardNumber();
		$DateExpiry = $rsp->getDateExpiry();
		$CardHolderName = $rsp->getCardHolderName();*/
		
		
	}

} // end class
?>
