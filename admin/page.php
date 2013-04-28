<? 
	
	
	if (empty($_REQUEST['sort'])) { $sort=0; } else { $sort=$_REQUEST['sort']; }
	if (empty($_REQUEST['way'])) { $way="up"; } else { $way=$_REQUEST['way']; }
	if (empty($_REQUEST['page'])) { $page=1; } else { $page=$_REQUEST['page']; }
	
	$set_url="sort=$sort&way=$way&page=$page";
	$title="Page Manager";
	
	$table="tbl_page_pg";
	$dir="../upload/page_images/";
	
	switch ($action) {
		case "view":
			$title.=" - <span>List</span>";
		
			$df=new Dataface($table);
			
		
			$df->set_field(array("Title","pg_title",500));
			//$df->set_icon(array("edit","delete"));
			$df->set_icon(array("edit"));
			$df->set_page_size(30);
	
			$sql="select * from tbl_page_pg";
			$url="?pg=$pg";
			
			break;
		case "add":	
		case "edit":		
		
			$df=new Dataface($table);
			$df->set_dir($dir);
			
			if ($action=="add") { $text=array(); $text['pg_type']="||||"; } else { $text=$df->get_record($_REQUEST['id']); }
			
			$tmp=explode("|",$text['pg_type']);
			
			$title.=" - <span>" . ucwords($action) . "</span>";
			$back_url="?pg=$pg&" . $set_url;
			
			$df->set_form("text","Page Title","pg_title",$text['pg_title'],"class=\"text-large\"","");
			
			if ($tmp[0]!="") { $df->set_form("file",$tmp[0],"pg_pic",$text['pg_pic'],"size=\"60\" class=\"text-large\"",""); }
			if ($tmp[1]!="") { $df->set_form("htmleditor",$tmp[1],"pg_desc1",$text['pg_desc1'],"class=\"textarea-full\" style=\"height:300px\"",""); }
			if ($tmp[2]!="") { $df->set_form("textarea",$tmp[2],"pg_desc2",$text['pg_desc2'],"class=\"textarea-full\" style=\"height:300px\"",""); }
			if ($tmp[3]!="") { $df->set_form("htmleditor",$tmp[3],"pg_desc3",$text['pg_desc3'],"class=\"textarea-full\" style=\"height:300px\"",""); }
			if ($tmp[4]!="") { $df->set_form("htmleditor",$tmp[4],"pg_desc4",$text['pg_desc4'],"class=\"textarea-full\" style=\"height:300px\"",""); }

			
		
			
			
			
			
			//$df->set_form("image-list","Image Gallery","frame","../upload/page_images/","class=\"textarea\" style=\"width:97%; height:200px\"","");
		
			break;	
			
		case "add-action":
		case "edit-action":
			$df=new Dataface($table);
			$df->set_dir($dir);
			
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
<div class="submenu"><?php /*<a href="<? echo $url; ?>&id=0&action=add" class="add"><img src="skin/images/icon/new.png" /></a>*/ ?></div>
<div class="clearIt"></div>

<? $df->process_list($sql,$sort,$way, $page, " cellspacing=\"0\" class=\"cms-table\"", $url); ?>

<? $df->page_split("?pg=$pg&sort=$sort&way=$way",$page); ?>

<? } ?>


<? if ($action=="add" || $action=="edit") { ?>
<h1><? echo $title; ?></h1>
<div class="submenu"><a href="<? echo $back_url; ?>" class="back">&laquo;&nbsp;Back</a></div>
<? $df->process_form($_GET); ?>
<? } ?>