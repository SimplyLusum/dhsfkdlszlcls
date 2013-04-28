<?php

class EmailForm {

	var $id=0;  // email template id
	var $subject=""; // Email subject
	var $from_name=""; // Email From Name
	var $from_email; // Email From Email
	var $encoding="utf-8"; // default email encoding
	var $content="";
	var $mail_server=MAIL_SERVER;
	
	/* Construction */
	function __construct($id) { 
	   $this->id = $id;
	   // Place your query to load your email template here.
	   $this->db = DB::getInstance();
	   $row = $this->db->get('tbl_email_e','e_id=' . $this->id,'');		
	  
	   // Fetch data and store email information
	   $this->subject = $row['e_subject'];	
	   $this->from_name = $row['e_fromname'];	
	   $this->from_email = $row['e_fromemail'];	
	   $this->content = $row['e_desc'];
	}
	
	function set_subject($str) { $this->subject=$str; }
	function set_from_name($str) { $this->from_name=$str; }
	function set_from_email($str) { $this->from_email=$str; }
	
	/* Generate a email using email template + $_POST */
	function generate_email($post) {
		
		foreach ($post as $key => $value) {
			if (!is_array($key)) { // handle all controls
				$text=$value;
			} else { // handle checkbox
				$text="";
				foreach ($value as $row) { $text.=$row . ","; }
			}
			
			// Replace tags
			$this->content=str_replace("[" . $key . "]",$text,$this->content);
		}
		
	
	} 
	
	
	// Send email out using PHPMailer
	function send($name,$email) {
	
		$mail = new PHPMailer();
			
		$mail->IsSMTP(); 
		$mail->Host = $this->mail_server;  // Your Email Server
		$mail->ContentType = "html";
		$mail->CharSet = $this->encoding;		
		$mail->From = $this->from_email;
		$mail->FromName = $this->from_name;
		$mail->AddAddress($email, $name);
		$mail->AddReplyTo($this->from_email, $this->from_name);			
		$mail->IsHTML(true); 
		//$mail->WordWrap = 60;
			
		$mail->Subject = $this->subject;
		$mail->Body = $this->content;
			
		if(!$mail->Send())	{ return false;	} else {  return true; }
	}
	

} // end class

?>