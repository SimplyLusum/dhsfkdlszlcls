<? 
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="down"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Transaction Manager";
	
	$table="tbl_transaction_tra";

	
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Date","tra_date",120,"datetime"));
			$df->set_field(array("First Name","tra_fname",100));
			$df->set_field(array("Last Name","tra_lname",100));
			$df->set_field(array("total","tra_total",60));
			$df->set_field(array("Pay type","tra_paytype",80));
			$df->set_field(array("Status","tra_status",80));
			$df->set_icon(array("edit","delete"));
			$df->set_page_size(30);
	
			$sql="select * from tbl_transaction_tra";
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","First Name","tra_fname",$text['tra_fname'],"class=\"text-large\"","");
			$df->set_form("text","Last Name","tra_lname",$text['tra_lname'],"class=\"text-large\"","");
			$df->set_form("text","Email Address","tra_email",$text['tra_email'],"class=\"text-large\"","");
			$df->set_form("text","Address","tra_address",$text['tra_address'],"class=\"text-large\"","");
			$df->set_form("text","Suburb","tra_suburb",$text['tra_suburb'],"class=\"text-large\"","");
			$df->set_form("text","City","tra_city",$text['tra_city'],"class=\"text-large\"","");
			$df->set_form("text","Postcode","tra_postcode",$text['tra_postcode'],"class=\"text-large\"","");
			$df->set_form("text","Shipping Type","tra_type",$text['tra_type'],"class=\"text-large\"","");
			$df->set_form("text","Sub Total","tra_subtotal",$text['tra_subtotal'],"class=\"text-large\"","");
			$df->set_form("text","Shipping","tra_shipping",$text['tra_shipping'],"class=\"text-large\"","");
			$df->set_form("text","Install","tra_install",$text['tra_install'],"class=\"text-large\"","");
			$df->set_form("text","Measurement","tra_measure",$text['tra_measure'],"class=\"text-large\"","");
			$df->set_form("text","Total","tra_total",$text['tra_total'],"class=\"text-large\"","");
			$df->set_form("checkbox","Send Email","approve","Send notification email","style=\"text-large\"","");
			$df->set_form("textarea","Order Items","tra_desc",$text['tra_desc'],"class=\"textarea-full\" style=\"height:200px\"","");



			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			foreach ($_POST as $row => $key) {
				$_POST[$row]=stripslashes(stripslashes($key));
			}
				
			
			if ($action=="add-action") {
				$id=$df->save("add",$_POST, array());
			} else {
				$id=$_POST['id'];	
				$df->save("save",$_POST, array());
			}
			
			if (!empty($_POST['approve'])) {
				include ("../class/emailform.class.php");
				
				$rs=$df->get_row_data("tbl_transaction_tra","tra_id='" . $id . "'");
				$tmp=array();
				$tmp['name']=$rs['tra_fname']; $tmp['reference']=$id;
						
				$email=new EmailForm(10);
				$email->generate_email($tmp);
				//$email->send($rs['tra_fname'] . " " . $rs['tra_lname'],$rs['tra_email']);
				$email->send($rs['tra_fname'] . " " . $rs['tra_lname'],"danielworks@gmail.com");
			}
			
			header ("location: ?pg=$pg&" . $set_url); die();
			
			break;	
			
		case "delete-action":			
		
			$df=new Dataface($table);
			$df->delete($_REQUEST['id']);

			header ("location: ?pg=$pg&" . $set_url); die();
			
		case "status-action":			
		
			$df=new Dataface($table);
			$df->status($_REQUEST['id']);

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
<div class="submenu"></div>

<? $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<? $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<? } ?>


<? if ($action=="add" || $action=="edit") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<? $df->process_form($_GET); ?>
<? } ?>