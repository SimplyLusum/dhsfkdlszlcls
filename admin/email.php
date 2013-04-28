<? 
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Email Manager";
	
	$table="tbl_email_e";

	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Subject","e_subject",300));
			$df->set_field(array("From Name","e_fromname",150));
			$df->set_field(array("From Email","e_fromemail",150));
			$df->set_icon(array("edit","delete"));
			$df->set_page_size(30);
	
			$sql="select * from tbl_email_e";
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			
			
			if ($action=="add") { $text=array(); } else { $text=$df->get_record($_REQUEST['id']); }
			

			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","From Name","e_fromname",$text['e_fromname'],"class=\"text-large\"","");
			$df->set_form("text","From Email","e_fromemail",$text['e_fromemail'],"class=\"text-large\"","");
			$df->set_form("text","Subject","e_subject",$text['e_subject'],"class=\"text-large\"","");
			$df->set_form("htmleditor",$tmp[1],"e_desc",$text['e_desc'],"class=\"textarea-full\" style=\"height:300px\"",""); 
			
			//$df->set_form("image-list","Image Gallery","frame","../upload/page_images/","class=\"textarea\" style=\"width:97%; height:200px\"","");
		
			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			
			foreach ($_POST as $row => $key) {
				$_POST[$row]=stripslashes(stripslashes($key));
			}
				
			
			if ($action=="add-action") {
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