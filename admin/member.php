<? include ("../class/emailform.class.php");
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="down"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Member Manager";
	
	$table="tbl_member_m";

	
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
			$df->set_field(array("Reg Date","m_date",100,"datetime"));
			$df->set_field(array("Member Name","m_name",200));
			$df->set_field(array("Member Email","m_email",200));
			$df->set_icon(array("status","edit","delete"));
			$df->set_page_size(30);
	
			$sql="select * from tbl_member_m";
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","Member Name","m_name",$text['m_name'],"class=\"text-large\"","");
			$df->set_form("text","Email Address","m_email",$text['m_email'],"class=\"text-large\"","");
			$df->set_form("text","Username","m_username",$text['m_username'],"class=\"text-large\"","");
			$df->set_form("text","Password","m_password",$text['m_password'],"class=\"text-large\"","");
			$df->set_form("text","Home Phone","m_phone",$text['m_phone'],"class=\"text-large\"","");
			$df->set_form("text","Mod Phone","m_mobile",$text['m_mobile'],"class=\"text-large\"","");
			$df->set_form("text","Address","m_address",$text['m_address'],"class=\"text-large\"","");
			$df->set_form("text","Suburb","m_suburb",$text['m_suburb'],"class=\"text-large\"","");
			$df->set_form("text","City","m_city",$text['m_city'],"class=\"text-large\"","");



			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			foreach ($_POST as $row => $key) {
				$_POST[$row]=stripslashes(stripslashes($key));
			}
				
			
			if ($action=="add-action") {
				$array['m_date']=mktime();
				$df->save("add",$_POST, array());
			} else {
				$df->save("save",$_POST, array());
			}
			
			header ("location: ?pg=$pg&" . $set_url); die();
			
			break;	
			
		case "delete-action":			
		
			$df=new Dataface($table);
			$df->delete($_REQUEST['id']);

			header ("location: ?pg=$pg&" . $set_url); die();
			
		case "status-action":			
		
			$df=new Dataface($table);
			$result=$df->status($_REQUEST['id']);
			
			if ($result==1) {
				
				$text=$df->get_record($_REQUEST['id']);
				$tmp=array();
				$tmp['name']=$text['m_name'];
				$tmp['username']=$text['m_username'];
				$tmp['password']=$text['m_password'];
				
				$email=new EmailForm(7);
				$email->generate_email($tmp);
				$sendto= $text['m_email'];
				$email->send($text['m_name'],$sendto);
			
			}
			

			header ("location: ?pg=$pg&" . $set_url); die();
			
		case "order-action":			
		
			$df=new Dataface($table);
			$df->update_order($_REQUEST['id'],$_REQUEST['order'],$_REQUEST['move'],"");		

			header ("location: ?pg=$pg&" . $set_url); die();
			break;				
		
	}
	
	
	

?>

<? if ($action=="view") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a></div>

<? $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<? $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<? } ?>


<? if ($action=="add" || $action=="edit") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<? $df->process_form($_GET); ?>
<? } ?>