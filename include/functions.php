<?php

function IsAdmin()
{
	
	if(!isset($_SESSION['uid']))
	{
		$url="login.php";
		header("Location: $url");
	}
}

function youtube($url,$width,$height) {
	$tmp=explode("?v=",$url);
	$tmp2=explode("&",$tmp[1]);
	
	echo "<object width=\"$width\" height=\"$height\">";
	echo "<param name=\"movie\" value=\"http://www.youtube.com/v/" . $tmp2[0] . "&hl=en_GB&fs=1&\"></param>";
	echo "<param name=\"allowFullScreen\" value=\"true\"></param>";
	echo "<param name=\"allowscriptaccess\" value=\"always\"></param>";
	echo "<embed src=\"http://www.youtube.com/v/" . $tmp2[0] . "&hl=en_GB&fs=1&\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\"></embed></object>";
	
}



function IsLogin()
{
	
	if(!isset($_SESSION['admin'])) { return false; } else { return true; }
}

function get_sort($id, $title, $order,$sort, $url) {
	
	if ($sort=="asc") { $side="desc"; } else { $side="asc"; }
	
	if ($id==$order) {
		echo "<a href=\"$url&order=$id&sort=$side\">$title</a> ";
		if ($sort=="asc") { echo "<img src=\"images/sort-down.gif\">"; } else { echo "<img src=\"images/sort-up.gif\">"; }
	} else {
		echo "<a href=\"$url&order=$id&sort=$sort\">$title</a>";
	}

}

function show_date($date) {
	if ($date!="") {
		return substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4);
	} else {
		return "N/A";
	}

}

function show_datetime($date) {
	if ($date!="") {
		return substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4) . " " . substr($date,11,8);
	} else {
		return "N/A";
	}
}

function NSQL($strSQL)
{
	//return str_replace("'", "\'", $strSQL);
	return addslashes($strSQL);
	//return $strSQL;
}

function DSQL($str,$action=0) {
	$str=stripslashes($str);
	if ($action==1) { 
		$str=htmlspecialchars($str);
	}
	return $str;
}


function get_today($str) {
	$today=getdate();
	
	switch($str) {
		case "y": $string=$today["year"]; break;
		case "m": $string=$today["mon"]; break;
		case "d": $string=$today["mday"]; break;
		case "h": $string=$today["hours"]; break;
		case "mi": $string=$today["minutes"]; break;
		case "s": $string=$today["seconds"]; break;
		
		case "date": $string=date("Y/m/d"); break;
		case "datetime": $string=$today["year"] . "-" . $today["mon"] . "-" . $today["mday"] . " " .  $today["hours"] . ":" . $today["minutes"] . ":" . $today["seconds"]; break;
		case "file": $string=$today["year"] . $today["mon"] . $today["mday"] . $today["hours"] . $today["minutes"] . $today["seconds"]; break;
		
	}
	
	return $string;
}

function get_date($date,$str) {
	
	$month=array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	//$temp=explode("/"$date);
	
	switch($str) {
	
		case "y": $string=substr($date,0,4); break;
		case "m": $string=substr($date,5,2); break;
		case "M": $string=$month[substr($date,5,2)-1]; break;
		case "d": $string=substr($date,8,2); break;
		case "h": $string=substr($date,11,2); break;
		case "mi": $string=substr($date,14,2); break;
		case "s": $string=substr($date,17,2); break;
	}
	return $string;
	
}

function date_convert($date,$command,$type) {

	if ($type==1) { // Date Only
		$temp=explode("/",$date);
		switch ($command) {
			case "mysql": $string=$temp[2] . "/" . $temp[1] . "/" . $temp[0]; break;
			case "show": $string=substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4); break;
		}
		return $string;
	} else { //Date + Time
		switch ($command) {
			case "mysql": $string=substr($date,6,4) . "/" . substr($date,3,2) . "/" . substr($date,0,2) . substr($date,10,9); break;
			case "show": $string=substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4) . substr($date,10,9); break;
		}
		return $string;	
	}
}

function showdate ($str,$date) {

	$temp=explode(" ",$date);
	$date=$temp[0];
	@$time=$temp[1];

	$date=explode("-",$date);
	@$time=explode(":",$time);
	
	
	if (count($time)<2) {
		$date=@date($str,mktime(0,0,0,$date[1],$date[2],$date[0]));
	} else {
		$date=@date($str,mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]));
	}
	
	return $date;
}


function set_log($myid,$str,$g_dbconn)
{
   $today=getdate();
   $now=$today["year"] . "/" . $today["mon"] . "/" . $today["mday"]  . " " . $today["hours"] . ":" . $today["minutes"] . ":" . $today["seconds"];
   $SQLQuery="Insert into tbl_log (log_m_id ,log_date,log_action) values(" . $myid . ",'" . $now . "','" . $str . "')";              
   $g_dbconn->Execute($SQLQuery);		
}   

function image_resize($source,$target,$width,$height,$quality) {

     $size = getimagesize($source);

     // scale evenly
     $ratio = $size[0] / $size[1];
     if ($ratio >= 1){
          $scale = $width / $size[0];
     } else {
          $scale = $height / $size[1];
     }
     // make sure its not smaller to begin with!
     if ($width >= $size[0] && $height >= $size[1]){
          $scale = 1;
     }

     $im_in = imagecreatefromjpeg ($source);
     $im_out = imagecreatetruecolor($size[0] * $scale, $size[1] * $scale);
	 
     imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $size[0] * $scale, $size[1] * $scale, $size[0], $size[1]);
     imagejpeg($im_out, $target, $quality);
     imagedestroy($im_out);
     imagedestroy($im_in);
}


function MonthToWord($mon) {

	$month=array("January","February","March","April","May","June","July","August","September","October","November","December");
	
	return $month[$mon-1];
}



function str_right($str,$pos) {
	
	return substr($str,strlen($str)-$pos,$pos);
}



function getDataFromDB($g_dbconn,$db_def,$tablename,$type=1,$where="",$orderby="") {

	if ($orderby=="") { $orderby=$db_def[$tablename][2]; }
	
	switch ($type) { 
		case "1": $SQLQuery="Select * from " . $db_def[$tablename][0] . " " . $where . " order by " . $orderby; 
		  		  $data=$g_dbconn->Execute($SQLQuery);
				  break;  // Get all records
		case "2": $SQLQuery="Select count(" . $db_def[$tablename][1] . ") as num from " . $db_def[$tablename][0] . " " . $where; 
				  $info=$g_dbconn->Execute($SQLQuery);
				  $data=$info->Fields("num");
		  		  break;  // Get total number of records
	}

	//echo $SQLQuery;
	return $data;
	
}

function update_order($source_id,$source_order,$way,$g_dbconn,$table,$where,$order_field,$pk_field) {


	if ($way=="up") {
		$SQLQuery="Select * from $table $where $order_field<$source_order order by $order_field desc limit 0,1";
	} else {	
		$SQLQuery="Select * from $table $where $order_field>$source_order order by $order_field limit 0,1";
	}
	
	$rs_target=$g_dbconn->Execute($SQLQuery);
	
	if (!$rs_target->EOF) {
		$target_id=$rs_target->Fields($pk_field);
		$target_order=$rs_target->Fields($order_field);
		
		$SQLQuery="update $table set $order_field=$target_order where $pk_field=" . $source_id;
		$g_dbconn->Execute($SQLQuery);
		
			
		$SQLQuery="update $table set $order_field=$source_order where $pk_field=" . $target_id;
		$g_dbconn->Execute($SQLQuery);			
		
	}
	
}

function get_setting($code,$g_dbconn) {

	$SQLQuery="Select * from tbl_setting_set where set_code='" . $code . "'";
	$rs_set=$g_dbconn->Execute($SQLQuery);
	
	return $rs_set->Fields("set_value");


}

function rmdirr($dir) {
   if($objs = glob($dir."/*")){
       foreach($objs as $obj) {
           is_dir($obj)? rmdirr($obj) : unlink($obj);
       }
   }
   rmdir($dir);
}

function getimage($fileid,$g_dbconn,$image_def,$thumb=0) {
	$SQLQuery="Select * from tbl_file_file where file_id='" . $fileid . "'";

	$rs_file=$g_dbconn->Execute($SQLQuery);
	
	$dir=$image_def[$rs_file->Fields("file_type")][1];
	
	switch($thumb) {
		case 0: return $dir . $rs_file->Fields("file_filename");  break;
		case 1: return $dir . "s_" . $rs_file->Fields("file_filename");  break;
		case 2: return $dir . "p_" . $rs_file->Fields("file_filename");  break;
	}


}

function send_email($server,$fname,$femail,$tname,$temail,$subject,$body,$bcc="") {

	$mail = new PHPMailer();
	
		
	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = $server; 		 // specify main and backup server
//	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->ContentType = "text/html";
	$mail->CharSet = "iso-8859-1";		
	$mail->From = $femail;
	$mail->FromName = $fname;
	$mail->AddAddress($temail, $tname);
	
//	$mail->Mailer="mail";
	
	if ($bcc!="") {
		$mail->AddBCC($bcc,"admin");
	}

	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$mail->IsHTML(true);                                  // set email format to HTML
		
	$mail->Subject = $subject;
	$mail->Body    = $body;
	
	//$mail->SetLanguage("en", "../lib/phpmailer/language/");  
		
	if(!$mail->Send())	{
	   echo $mail->ErrorInfo;
	} else {
		return true;
	}
	
	die();

}

function watermark($file,$watermark) {

	$imagesource =  $file;
	$filetype = substr($imagesource,strlen($imagesource)-4,4);
	$filetype = strtolower($filetype);
	if($filetype == ".gif")  $image = @imagecreatefromgif($imagesource);  
	if($filetype == ".jpg")  $image = @imagecreatefromjpeg($imagesource);  
	if($filetype == ".png")  $image = @imagecreatefrompng($imagesource);  
	if (!$image) die();
	$watermark = @imagecreatefromgif($watermark);
	$imagewidth = imagesx($image);
	$imageheight = imagesy($image);  
	$watermarkwidth =  imagesx($watermark);
	$watermarkheight =  imagesy($watermark);
	$startwidth = (($imagewidth - $watermarkwidth)-10);
	$startheight = (($imageheight - $watermarkheight)-10);
	imagecopy($image, $watermark,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
	imagejpeg($image,$file,100);
	imagedestroy($image);
	imagedestroy($watermark);
}

function send_template_email($eid,$name,$email,$content,$g_dbconn,$attachment="",$reply_to="") {

	$SQLQuery="Select * from tbl_email_e where e_id='" . $eid . "'";
	$rs_e=$g_dbconn->Execute($SQLQuery);
				

	$mail = new PHPMailer();
	
	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = MAIL_SERVER; 		 // specify main and backup server
	$mail->ContentType = "text/html";
	$mail->CharSet = "iso-8859-1";		
	$mail->From = $rs_e->Fields("e_from_email");
	$mail->FromName = $rs_e->Fields("e_from_name");
	$mail->AddAddress($email, $name);
	
	if ($attachment!="") {
		$mail->AddAttachment($attachment);
	}
	
	if ($reply_to!="") {
		$mail->AddReplyTo($reply_to, '');
	}

	$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	$mail->IsHTML(true);                                  // set email format to HTML
	$mail->Subject = $rs_e->Fields("e_subject");
	
	$body=stripslashes($rs_e->Fields("e_desc"));
	
	for ($i=0; $i<count($content); $i+=2) {
		$body=str_replace("[" . $content[$i] . "]",$content[$i+1],$body);
	}
	
	$mail->Body    = $body;

	
	
	
	//$mail->SetLanguage("en", "lib/phpmailer/language/");  
		
	if(!$mail->Send())	{
	   echo $mail->ErrorInfo; die();
	} else {
		return true;
	}

}


function statistic($g_dbconn) {
	
	$today=getdate();
	$y=$today["year"]; $m=$today["mon"];
	
	$SQLQuery="Select * from tbl_visitor_v where v_year='" . $y . "' and v_month='" . $m . "'";
	$rs_check=$g_dbconn->Execute($SQLQuery);
	
	if ($rs_check->EOF){ 
		$SQLQuery="Insert into tbl_visitor_v(v_year,v_month,v_value) values('$y','$m','1')";
		$g_dbconn->Execute($SQLQuery);
	} else {
		$SQLQuery="Update tbl_visitor_v set v_value=v_value+1 where v_year=$y and v_month=$m";
		$g_dbconn->Execute($SQLQuery);
	}


}


function upload_file($_POST,$file,$dir,$rename=0,$resize=0) {

	$return=array();
		
	for ($i=0; $i<count($file); $i++) {

		if ($_FILES[$file[$i]]['tmp_name']!='none') {
			
			if ($rename==0) {
				$filename=$_FILES[$file[$i]]['name'];
			} else {
				$filename=md5(mktime()) . rand(10000,99999);
				$filename.=substr($_FILES[$file[$i]]['name'],strlen($_FILES[$file[$i]]['name'])-4,4);
			}
			
			if  (move_uploaded_file($_FILES[$file[$i]]['tmp_name'], $dir . $filename)) { 
					chmod($dir . $filename,0777); 
					if ($resize!=0) {
						$thumb=new thumbnail($dir . $filename);
						$thumb->size_width($resize);
						$thumb->save($dir . $filename);	
					}
					$return[$i]=$filename;
					
			} else { $return[$i]=""; }
		} else { $return[$i]=""; }	
	} // end for
	
	return $return;
}


function page_split_limit($param,$current,$total_page,$urlfriendly=0) { 

	if ($urlfriendly==1) { $con="-"; } else { $con="&page="; }

	if (($current!=1) && ($total_page!=0)) {  
		echo '<li><a href="' . $param . $con . '1">&lt;</a></li>'; 
	}

	if ($total_page>10 && $current>5) {
		$start=$current-4;
		$end=$current+5;
		if ($end > $total_page) { $end=$total_page; }
		if ($end-$start<10) { $start=$end-10; }
		for ($i=$start; $i<=$end; $i++) {
			echo '<li><a href="' . $param . $con . $i . '"';
			if ($i==$current) { echo ' class="selected"'; }
			echo '>' . $i . '</a></li>';
		}

	} else {
		if ($total_page<10) { $limit=$total_page; } else { $limit=10; }
		for ($i=1; $i<=$limit; $i++) {
			echo '<li><a href="' . $param . $con . $i . '"';
			if ($i==$current) { echo ' class="selected"'; }
			echo '>' . $i . '</a></li>';
		}
	}
	
	if (($current!=$total_page) && ($total_page!=0)) {
		echo '<li><a href="' . $param . $con . $total_page . '">&gt;</a></li>'; 
	}	

}

function parseUri() {
    if ( $_SERVER['REQUEST_URI'] ) {
        $uri = parse_url( $_SERVER['REQUEST_URI'] );
        if ( @$uri['query'] ) {
            parse_str( $uri['query'], $uri );
            
            foreach ( $uri as $k => $v ) {
                if ( !isset( $_GET[ $k ] ) )
                     $_GET[ $k ] = $v;
                
                if ( !isset( $_POST[ $k ] ) )
                     $_POST[ $k ] = $v;
            }
        }
    }
}

?>